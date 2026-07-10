<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organisasi extends Model
{
    protected $table = 'tbl_organisasi';
    protected $primaryKey = 'id_organisasi';

    protected $fillable = [
        'id_user',
        'nama_organisasi',
        'logo',
        'no_pendaftaran',
        'no_telefon',
        'alamat',
        'payment_status',
        'activation_bill_code',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function kempen()
    {
        return $this->hasMany(Kempen::class, 'id_organisasi', 'id_organisasi');
    }

    public function kempenAktif()
    {
        return $this->kempen()->where('status_kempen', 'Aktif');
    }
}
