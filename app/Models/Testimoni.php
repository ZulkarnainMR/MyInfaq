<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimoni extends Model
{
    protected $table = 'tbl_testimoni';
    protected $primaryKey = 'id_testimoni';

    protected $fillable = [
        'nama',
        'peranan',
        'bintang',
        'ulasan',
        'status',
    ];

    protected $casts = [
        'bintang' => 'integer',
    ];
}
