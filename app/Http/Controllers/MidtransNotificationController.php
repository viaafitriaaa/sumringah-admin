<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Services\Customer\Midtrans;
use App\Services\Customer\MidtransPayments;
use App\Services\Customer\MidtransUnavailable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

final class MidtransNotificationController
{
    public function __invoke(Request $request, Midtrans $midtrans, MidtransPayments $payments): Response
    {
        $validator = Validator::make($request->json()->all(), [
            'order_id' => 'required|string|max:32',
            'transaction_id' => 'required|uuid',
            'status_code' => 'required|string|size:3',
            'gross_amount' => 'required|string|max:17',
            'currency' => 'required|in:IDR',
            'payment_type' => 'required|in:qris',
            'signature_key' => 'required|string|size:128',
        ]);
        abort_if($validator->fails(), 422, 'Format notifikasi tidak valid.');
        $notice = $validator->validated();
        abort_unless($midtrans->signatureMatches($notice), 401);

        $order = Order::query()->where('number', $notice['order_id'])
            ->whereRelation('payment', 'provider', Payment::MIDTRANS)->firstOrFail();
        try {
            $payments->sync($order, notice: $notice);
        } catch (MidtransUnavailable $failure) {
            Log::warning($failure->getMessage(), ['order' => $order->number]);

            return response('Retry', 503);
        }

        return response('OK');
    }
}
