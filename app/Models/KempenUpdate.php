<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KempenUpdate extends Model
{
    protected $table = 'tbl_kempen_updates';
    protected $primaryKey = 'id_update';

    protected $fillable = [
        'id_kempen',
        'tajuk_update',
        'keterangan_update',
        'gambar_update',
    ];

    public function kempen()
    {
        return $this->belongsTo(Kempen::class, 'id_kempen', 'id_kempen');
    }
}
