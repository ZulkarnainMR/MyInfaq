<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Derma extends Model
{
    // SoftDeletes: Rekod derma adalah rekod kewangan.
    // TIDAK BOLEH dipadam secara kekal. Gunakan soft delete untuk audit trail.
    use SoftDeletes;

    protected $table = 'tbl_derma';
    protected $primaryKey = 'id_resit';

    protected $fillable = [
        'id_kempen',
        'id_penderma',
        'amaun_derma',
        'platform_tip',
        'tarikh_derma',
        'status_bayaran',
        'no_resit',
        'kaedah_bayaran',
        'nota',
        'bill_code',
    ];

    protected $casts = [
        'amaun_derma'  => 'decimal:2',
        'platform_tip' => 'decimal:2',
        'tarikh_derma' => 'datetime',
    ];

    // ─── Boot: Generate receipt number automatically ───────────────────────────
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($derma) {
            if (empty($derma->no_resit)) {
                $derma->no_resit = 'INFAQ-' . strtoupper(Str::random(8)) . '-' . date('Ymd');
            }
        });

        // Update campaign total only when status is updated to Berjaya (via ToyyibPay callback)
        static::updated(function ($derma) {
            if ($derma->wasChanged('status_bayaran') && $derma->status_bayaran === 'Berjaya') {
                $derma->kempen->increment('jumlah_kutipan_semasa', $derma->amaun_derma);

                // Mark campaign as Selesai if target reached
                $derma->kempen->refresh();
                if ($derma->kempen->isFundingComplete() && $derma->kempen->status_kempen === 'Aktif') {
                    $derma->kempen->update(['status_kempen' => 'Selesai']);
                }
            }
        });
    }

    // ─── Relationships ────────────────────────────────────────────────────────
    public function kempen()
    {
        return $this->belongsTo(Kempen::class, 'id_kempen', 'id_kempen');
    }

    public function penderma()
    {
        return $this->belongsTo(Penderma::class, 'id_penderma', 'id_penderma');
    }
}
