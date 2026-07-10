<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staf extends Model
{
    protected $table = 'tbl_staf';
    protected $primaryKey = 'id_staf';

    protected $fillable = [
        'id_user',
        'nama_staf',
        'jawatan',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function kempenDisemak()
    {
        return $this->hasMany(Kempen::class, 'id_staf', 'id_staf');
    }

    public function ketelusan()
    {
        return $this->hasMany(Ketelusan::class, 'id_staf', 'id_staf');
    }
}
