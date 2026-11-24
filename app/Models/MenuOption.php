<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuOption extends Model
{
    protected $fillable = ['name', 'price', 'max_quantity', 'active'];

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }
}
