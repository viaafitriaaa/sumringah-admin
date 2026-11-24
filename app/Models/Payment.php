<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    public const SIMULATION = 'local_simulation';

    protected $fillable = ['method', 'provider', 'provider_reference', 'status', 'amount_cents', 'paid_at'];

    protected $casts = ['paid_at' => 'datetime'];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
