<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class Laporan extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.laporan';

    protected static ?string $navigationGroup = 'Laporan & Statistik';

    protected static ?string $title = 'Laporan Kutipan';

    protected static ?int $navigationSort = 100;

    public $bulan = '';
    public $tahun = '';
    public $status = '';
    public $id_kempen = '';

    public function getViewData(): array
    {
        $user = auth()->user();

        // 1. Asas query menggunakan Join table 
        $query = DB::table('tbl_derma')
            ->join('tbl_kempen', 'tbl_derma.id_kempen', '=', 'tbl_kempen.id_kempen')
            ->join('tbl_organisasi', 'tbl_kempen.id_organisasi', '=', 'tbl_organisasi.id_organisasi')
            ->leftJoin('tbl_penderma', 'tbl_derma.id_penderma', '=', 'tbl_penderma.id_penderma')
            ->select(
                'tbl_derma.*',
                'tbl_kempen.tajuk_kempen',
                'tbl_organisasi.nama_organisasi',
                DB::raw('COALESCE(tbl_penderma.nama_penderma, "Hamba Allah") as nama_penderma')
            );

        // Jika pengguna adalah Organisasi, hanya tunjukkan laporan kempen mereka sahaja
        if ($user && $user->isOrganisasi()) {
            $query->where('tbl_organisasi.id_user', $user->id_user);
        }

        // 2. Aplikasi penapis 
        if (!empty($this->bulan)) {
            $query->whereMonth('tbl_derma.tarikh_derma', $this->bulan);
        }

        if (!empty($this->tahun)) {
            $query->whereYear('tbl_derma.tarikh_derma', $this->tahun);
        }

        if (!empty($this->status)) {
            $query->where('tbl_derma.status_bayaran', $this->status);
        }

        if (!empty($this->id_kempen)) {
            $query->where('tbl_derma.id_kempen', $this->id_kempen);
        }

        $laporan = $query->orderBy('tbl_derma.tarikh_derma', 'desc')->get();

        $summary = [
            'jumlah_kutipan' => $laporan->where('status_bayaran', 'Berjaya')->sum('amaun_derma'),
            'jumlah_transaksi' => $laporan->count(),
            'transaksi_berjaya' => $laporan->where('status_bayaran', 'Berjaya')->count(),
        ];

        $kempenQuery = DB::table('tbl_kempen');
        if ($user && $user->isOrganisasi()) {
            $kempenQuery->join('tbl_organisasi', 'tbl_kempen.id_organisasi', '=', 'tbl_organisasi.id_organisasi')
                        ->where('tbl_organisasi.id_user', $user->id_user);
        }
        $senaraiKempen = $kempenQuery->select('tbl_kempen.id_kempen', 'tbl_kempen.tajuk_kempen')->get();

        return [
            'laporan' => $laporan,
            'summary' => $summary,
            'senaraiKempen' => $senaraiKempen,
        ];
    }
}
