<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    public const AWAITING = 'awaiting_payment';
    public const CONFIRMED = 'confirmed';
    public const CANCELLED = 'cancelled';

    protected $fillable = [
        'number', 'checkout_key', 'access_hash', 'meja_id', 'table_code',
        'customer_name', 'customer_phone', 'customer_email', 'type', 'note', 'status',
        'subtotal_cents', 'discount_cents', 'tax_bps', 'tax_cents', 'total_cents',
    ];

    protected $hidden = ['access_hash', 'checkout_key', 'customer_name', 'customer_phone', 'customer_email'];

    public function meja(): BelongsTo
    {
        return $this->belongsTo(Meja::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }
}
