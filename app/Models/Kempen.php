<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kempen extends Model
{
    // SoftDeletes: rekod yang dipadam TIDAK hilang selama-lamanya.
    // Lajur `deleted_at` akan ditanda, dan rekod masih boleh dipulihkan (restore).
    // Ini WAJIB untuk sistem kewangan — audit trail mesti lengkap.
    use SoftDeletes;

    // ─── Scopes ───────────────────────────────────────────────────────────────

    /**
     * Scope: Kempen yang selamat untuk dipadam (tiada derma Berjaya).
     * Kempen yang mempunyai derma Berjaya TIDAK boleh dipadam — hanya archive.
     */
    public function scopeBolehDipadam($query)
    {
        return $query->whereDoesntHave('derma', fn ($q) => $q->where('status_bayaran', 'Berjaya'));
    }
    protected $table = 'tbl_kempen';
    protected $primaryKey = 'id_kempen';

    protected $fillable = [
        'id_organisasi',
        'tajuk_kempen',
        'keterangan_kempen',
        'sasaran_dana',
        'jumlah_kutipan_semasa',
        'status_kempen',
        'gambar_kempen',
        'tarikh_tamat',
        'id_staf',
        'tarikh_semakan',
        'sebab_tolak',
        'bayaran_diminta',
        'tarikh_minta_bayaran',
        'bayaran_diluluskan',
        'tarikh_bayaran_diluluskan',
        'kategori',
    ];

    protected $casts = [
        'sasaran_dana'             => 'decimal:2',
        'jumlah_kutipan_semasa'    => 'decimal:2',
        'tarikh_tamat'             => 'date',
        'tarikh_semakan'           => 'datetime',
        'tarikh_minta_bayaran'     => 'datetime',
        'tarikh_bayaran_diluluskan'=> 'datetime',
        'bayaran_diminta'          => 'boolean',
        'bayaran_diluluskan'       => 'boolean',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────
    public function organisasi()
    {
        return $this->belongsTo(Organisasi::class, 'id_organisasi', 'id_organisasi');
    }

    public function staf()
    {
        return $this->belongsTo(Staf::class, 'id_staf', 'id_staf');
    }

    public function derma()
    {
        return $this->hasMany(Derma::class, 'id_kempen', 'id_kempen');
    }

    public function ketelusan()
    {
        return $this->hasMany(Ketelusan::class, 'id_kempen', 'id_kempen');
    }

    public function updates()
    {
        return $this->hasMany(KempenUpdate::class, 'id_kempen', 'id_kempen');
    }

    // ─── Accessors & Helpers ─────────────────────────────────────────────────
    public function getPeratusKutipanAttribute(): float
    {
        if ($this->sasaran_dana <= 0) return 0;
        return min(100, round(($this->jumlah_kutipan_semasa / $this->sasaran_dana) * 100, 1));
    }

    public function isFundingComplete(): bool
    {
        return $this->jumlah_kutipan_semasa >= $this->sasaran_dana;
    }

    public function getCajPlatformAttribute(): float
    {
        // Menggunakan peratusan dari Tetapan (lalai 4%)
        $peratus = \App\Models\Tetapan::dapatkan('peratus_cas_platform', 4);
        return round($this->jumlah_kutipan_semasa * ($peratus / 100), 2);
    }

    public function getAmaunBersihNgoAttribute(): float
    {
        return round($this->jumlah_kutipan_semasa - $this->caj_platform, 2);
    }
}
