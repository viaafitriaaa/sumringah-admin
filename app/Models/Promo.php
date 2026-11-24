<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    public function scopeActiveOn(Builder $query, string $date): Builder
    {
        return $query->where('status', 'aktif')
            ->where('tanggal_mulai', '<=', $date)
            ->where('tanggal_selesai', '>=', $date);
    }
}
