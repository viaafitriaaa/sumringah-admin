<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'nama_usaha',
        'alamat',
        'email',
        'instagram',
        'whatsapp',
        'logo',
        'jam_operasional',
    ];

    protected $casts = [
        'jam_operasional' => 'array',
    ];
}
