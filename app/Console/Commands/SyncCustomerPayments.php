<?php

namespace App\Console\Commands;

use App\Models\Payment;
use App\Services\Customer\Midtrans;
use App\Services\Customer\MidtransPayments;
use App\Services\Customer\MidtransUnavailable;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncCustomerPayments extends Command
{
    protected $signature = 'customer:sync-payments';

    protected $description = 'Reconcile pending Midtrans Sandbox payments';

    public function handle(Midtrans $midtrans, MidtransPayments $payments): int
    {
        if (!$midtrans->ready()) {
            return self::SUCCESS;
        }

        $pending = Payment::query()->with('order')->where('provider', Payment::MIDTRANS)->where('status', 'pending');
        foreach ($pending->lazyById() as $payment) {
            try {
                $payments->sync($payment->order);
            } catch (MidtransUnavailable $failure) {
                Log::warning($failure->getMessage(), ['order' => $payment->order->number]);
            }
        }

        return self::SUCCESS;
    }
}
