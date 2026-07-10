<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penderma extends Model
{
    protected $table = 'tbl_penderma';
    protected $primaryKey = 'id_penderma';

    protected $fillable = [
        'id_user',
        'nama_penderma',
        'no_telefon',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function derma()
    {
        return $this->hasMany(Derma::class, 'id_penderma', 'id_penderma');
    }

    // ─── Accessors ───────────────────────────────────────────────────────────
    public function getTotalDermaAttribute(): float
    {
        return $this->derma()->where('status_bayaran', 'Berjaya')->sum('amaun_derma');
    }
}
