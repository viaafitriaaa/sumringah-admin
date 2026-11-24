<?php

namespace App\Services\Customer;

use App\Models\Order;
use App\Models\Payment;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

final class MidtransPayments
{
    public function __construct(private Midtrans $midtrans, private Ordering $ordering) {}

    public function sync(Order $order, bool $allowCharge = false, bool $cancel = false, ?array $notice = null): Payment
    {
        $lock = Cache::lock('customer-midtrans:'.$order->number, 45);
        if (!$lock->get()) {
            throw new MidtransUnavailable('Payment is already being synchronized.');
        }

        try {
            $order->refresh();
            $payment = $order->payment()->firstOrFail();
            abort_unless($payment->provider === Payment::MIDTRANS, 404);
            if ($notice !== null && !$this->matches($notice, $order, $payment)) {
                abort(422, 'Data notifikasi tidak cocok.');
            }
            if ($payment->status === 'pending') {
                $this->syncPending($order, $payment, $allowCharge, $cancel, $notice);
            }

            return $payment->refresh();
        } finally {
            $lock->release();
        }
    }

    private function syncPending(Order $order, Payment $payment, bool $allowCharge, bool $cancel, ?array $notice): void
    {
        if (!$payment->charge_started_at && $notice === null) {
            if ($cancel || $payment->created_at->lte(now()->subMinutes(Midtrans::EXPIRY_MINUTES))) {
                $this->record($order, ['provider_status' => $cancel ? 'cancel' : 'expire'], 'cancelled');
            } elseif ($allowCharge) {
                $this->charge($order, $payment);
            }

            return;
        }

        $data = $this->midtrans->status($order->number);
        if ($data === null) {
            if ($allowCharge && !$payment->provider_reference && $notice === null) {
                $this->charge($order, $payment);

                return;
            }
            throw new MidtransUnavailable('Midtrans has not confirmed the transaction yet.');
        }

        $this->verify($data, $order, $payment, $notice);
        if ($cancel && strtolower($data['transaction_status']) === 'pending') {
            $failure = null;
            try {
                $this->midtrans->cancel($order->number);
            } catch (MidtransUnavailable $failure) {
                // A settlement can win the race, or cancel may have succeeded before the connection failed.
            }
            $data = $this->midtrans->status($order->number) ?? throw new MidtransUnavailable('Cancellation is not yet confirmed.');
            $this->verify($data, $order, $payment, $notice);
            if (strtolower($data['transaction_status']) === 'pending') {
                throw $failure ?? new MidtransUnavailable('Cancellation is not yet confirmed.');
            }
        }
        $this->recordStatus($order, $payment, $data);
    }

    private function charge(Order $order, Payment $payment): void
    {
        $firstAttempt = !$payment->charge_started_at;
        if ($firstAttempt) {
            $payment->update(['charge_started_at' => now()]);
        }
        try {
            $data = $this->midtrans->charge($order);
        } catch (MidtransUnavailable $failure) {
            // On the first attempt a definitive authentication refusal means no QR was created.
            if ($firstAttempt && in_array($failure->getCode(), [401, 403], true)) {
                $payment->update(['charge_started_at' => null]);
            }
            throw $failure;
        }
        if (strtolower($data['transaction_status'] ?? '') !== 'pending') {
            $data = $this->midtrans->status($order->number) ?? throw new MidtransUnavailable('Charge is not yet confirmed.');
        }
        $this->verify($data, $order, $payment);
        $this->recordStatus($order, $payment, $data);
    }

    private function verify(array $data, Order $order, Payment $payment, ?array $notice = null): void
    {
        if (!$this->matches($data, $order, $payment) || !is_string($data['transaction_status'] ?? null) ||
            ($notice !== null && $data['transaction_id'] !== $notice['transaction_id'])) {
            throw new MidtransUnavailable('Transaction details from Midtrans do not match the local payment.');
        }
    }

    private function matches(array $data, Order $order, Payment $payment): bool
    {
        $amount = $data['gross_amount'] ?? null;
        $reference = $data['transaction_id'] ?? null;

        return ($data['order_id'] ?? null) === $order->number && ($data['currency'] ?? null) === 'IDR'
            && ($data['payment_type'] ?? null) === 'qris'
            && is_string($reference) && Str::isUuid($reference)
            && (!$payment->provider_reference || $payment->provider_reference === $reference)
            && is_string($amount) && preg_match('/\A[0-9]{1,14}(?:\.[0-9]{1,2})?\z/', $amount)
            && Money::cents($amount) === (int) $payment->amount_cents
            && (int) $payment->amount_cents === (int) $order->total_cents;
    }

    private function recordStatus(Order $order, Payment $payment, array $data): void
    {
        $status = strtolower($data['transaction_status']);
        $outcome = match ($status) {
            'pending' => null,
            'settlement' => $this->settlementOutcome($data),
            'deny', 'failure', 'cancel', 'expire' => 'cancelled',
            default => throw new MidtransUnavailable('Midtrans returned an unsupported transaction status.'),
        };
        $expires = $this->gatewayTime($data['expiry_time'] ?? null) ?? $payment->expires_at
            ?? $this->gatewayTime($data['transaction_time'] ?? null)?->addMinutes(Midtrans::EXPIRY_MINUTES);
        $details = [
            'provider_reference' => $data['transaction_id'],
            'provider_status' => $status,
            'qr_url' => $status === 'pending' ? ($payment->qr_url ?? $this->midtrans->qrUrl($data)) : null,
            'expires_at' => $expires,
        ];
        if ($outcome === 'paid') {
            $details['paid_at'] = $this->gatewayTime($data['settlement_time'] ?? null) ?? now();
        }
        $this->record($order, $details, $outcome);
    }

    private function settlementOutcome(array $data): string
    {
        $fraud = $data['fraud_status'] ?? 'accept';
        if ((string) $data['status_code'] !== '200' || !is_string($fraud) || strcasecmp($fraud, 'accept') !== 0) {
            throw new MidtransUnavailable('Midtrans has not confirmed settlement.');
        }

        return 'paid';
    }

    private function gatewayTime(mixed $time): ?CarbonImmutable
    {
        if (!is_string($time) || !preg_match('/\A\d{4}-\d\d-\d\d \d\d:\d\d:\d\d\z/', $time)) {
            return null;
        }

        return CarbonImmutable::createFromFormat('Y-m-d H:i:s', $time, 'Asia/Jakarta')->setTimezone(config('app.timezone'));
    }

    private function record(Order $order, array $details, ?string $outcome): void
    {
        $this->ordering->applyPayment($order, Payment::MIDTRANS, $details, $outcome);
    }
}
