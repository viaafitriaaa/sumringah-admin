<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    protected $fillable = [
        'nama_menu',
        'kategori',
        'deskripsi',
        'harga',
        'stok',
        'foto',
        'status',
    ];

    public function options(): HasMany
    {
        return $this->hasMany(MenuOption::class);
    }
}
