<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meja extends Model
{
    protected $table = 'mejas';

    protected $fillable = [
        'kode_meja',
        'area',
        'lantai',
        'jenis_area',
        'kapasitas',
        'status',
    ];
}
