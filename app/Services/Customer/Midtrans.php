<?php

namespace App\Services\Customer;

use App\Models\Order;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

final class Midtrans
{
    private const BASE_URL = 'https://api.sandbox.midtrans.com';
    public const EXPIRY_MINUTES = 15;

    public function ready(): bool
    {
        return !config('payment.midtrans.is_production')
            && filled(config('payment.midtrans.server_key'))
            && filled(config('payment.midtrans.signature_secret'));
    }

    public function charge(Order $order): array
    {
        return $this->request('POST', '/v2/charge', [
            'payment_type' => 'qris',
            'transaction_details' => [
                'order_id' => $order->number,
                'gross_amount' => intdiv($order->total_cents, 100),
            ],
            'qris' => ['acquirer' => 'gopay'],
            'custom_expiry' => ['expiry_duration' => self::EXPIRY_MINUTES, 'unit' => 'minute'],
        ]);
    }

    public function status(string $number): ?array
    {
        return $this->request('GET', '/v2/'.rawurlencode($number).'/status', allowMissing: true);
    }

    public function cancel(string $number): void
    {
        $this->request('POST', '/v2/'.rawurlencode($number).'/cancel');
    }

    public function signatureMatches(array $notice): bool
    {
        $secret = config('payment.midtrans.signature_secret');
        if (!$this->ready()) {
            return false;
        }

        $signature = hash('sha512', $notice['order_id'].$notice['status_code'].$notice['gross_amount'].$secret);

        return hash_equals($signature, $notice['signature_key']);
    }

    public function qrUrl(array $data): string
    {
        $reference = preg_quote($data['transaction_id'], '~');
        foreach ($data['actions'] ?? [] as $action) {
            $url = $action['url'] ?? null;
            if (($action['name'] ?? null) === 'generate-qr-code' && ($action['method'] ?? null) === 'GET' &&
                is_string($url) && preg_match('~\Ahttps://api\.sandbox\.midtrans\.com/v2/qris/'.$reference.'/qr-code\z~', $url)) {
                return $url;
            }
        }

        // Get Status may omit the image action. GoPay QRIS uses the same URL with the verified transaction ID.
        return self::BASE_URL.'/v2/qris/'.$data['transaction_id'].'/qr-code';
    }

    private function request(string $method, string $path, array $body = [], bool $allowMissing = false): ?array
    {
        if (!$this->ready()) {
            throw new MidtransUnavailable('Midtrans Sandbox is not configured.');
        }
        try {
            $options = $body ? ['json' => $body] : [];
            $response = Http::acceptJson()->asJson()->withBasicAuth(config('payment.midtrans.server_key'), '')
                ->withoutRedirecting()->connectTimeout(3)->timeout(6)->send($method, self::BASE_URL.$path, $options);
        } catch (ConnectionException $failure) {
            preg_match('/cURL error (\d+)/', $failure->getMessage(), $error);

            throw new MidtransUnavailable('Could not connect to Midtrans Sandbox (transport '.($error[1] ?? 'unknown').').');
        }

        $data = $response->json();
        $code = is_array($data) ? filter_var($data['status_code'] ?? null, FILTER_VALIDATE_INT) : false;
        if ($allowMissing && $code === 404 && ($response->successful() || $response->status() === 404)) {
            return null;
        }
        // Get Status can return HTTP 200 with Midtrans status code 407 for expired QRIS.
        $expired = $allowMissing && $code === 407 && ($data['transaction_status'] ?? null) === 'expire';
        if (!$response->successful() || (!$expired && !in_array($code, [200, 201, 202], true))) {
            throw new MidtransUnavailable(
                sprintf('Midtrans Sandbox returned HTTP %d, API %s.', $response->status(), $code ?: 'unreported'),
                $response->status(),
            );
        }

        return $data;
    }
}
