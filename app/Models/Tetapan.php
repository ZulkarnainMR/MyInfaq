<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Tetapan extends Model
{
    protected $table = 'tbl_tetapan';
    protected $primaryKey = 'id_tetapan';

    protected $fillable = [
        'kunci',
        'nilai',
    ];

    /**
     * Helper untuk mendapatkan nilai tetapan dengan pantas.
     * Menggunakan cache supaya tidak membebankan database jika dipanggil berkali-kali.
     */
    public static function dapatkan(string $kunci, $default = null)
    {
        return Cache::rememberForever("tetapan_{$kunci}", function () use ($kunci, $default) {
            $tetapan = self::where('kunci', $kunci)->first();
            return $tetapan ? $tetapan->nilai : $default;
        });
    }

    /**
     * Apabila model dikemaskini, kita perlu buang cache yang lama.
     */
    protected static function booted()
    {
        static::saved(function ($tetapan) {
            Cache::forget("tetapan_{$tetapan->kunci}");
        });

        static::deleted(function ($tetapan) {
            Cache::forget("tetapan_{$tetapan->kunci}");
        });
    }
}
