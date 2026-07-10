<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ketelusan extends Model
{
    protected $table = 'tbl_ketelusan';
    protected $primaryKey = 'id_ketelusan';

    protected $fillable = [
        'id_kempen',
        'tajuk_laporan',
        'keterangan_penerima',
        'gambar_agihan',
        'tarikh_agihan',
        'bilangan_penerima',
        'status_audit',
        'id_staf',
        'tarikh_audit',
        'nota_audit',
    ];

    protected $casts = [
        'gambar_agihan'  => 'array',
        'tarikh_agihan'  => 'date',
        'tarikh_audit'   => 'datetime',
        'bilangan_penerima' => 'integer',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────
    public function kempen()
    {
        return $this->belongsTo(Kempen::class, 'id_kempen', 'id_kempen');
    }

    public function staf()
    {
        return $this->belongsTo(Staf::class, 'id_staf', 'id_staf');
    }
}
