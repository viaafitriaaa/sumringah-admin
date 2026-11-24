<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    public const SIMULATION = 'local_simulation';
    public const MIDTRANS = 'midtrans_sandbox';

    protected $fillable = [
        'method', 'provider', 'provider_reference', 'status', 'amount_cents', 'paid_at',
        'provider_status', 'qr_url', 'charge_started_at', 'expires_at',
    ];

    protected $casts = [
        'paid_at' => 'immutable_datetime',
        'charge_started_at' => 'immutable_datetime',
        'expires_at' => 'immutable_datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
