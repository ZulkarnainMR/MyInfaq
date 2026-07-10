<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Paparkan halaman laporan dengan keupayaan filter.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // 1. Asas query menggunakan Join (Memenuhi rubrik)
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

        // 2. Aplikasi penapis (Filters)
        if ($request->filled('bulan')) {
            $query->whereMonth('tbl_derma.tarikh_derma', $request->bulan);
        }

        if ($request->filled('tahun')) {
            $query->whereYear('tbl_derma.tarikh_derma', $request->tahun);
        }

        if ($request->filled('status')) {
            $query->where('tbl_derma.status_bayaran', $request->status);
        }

        if ($request->filled('id_kempen')) {
            $query->where('tbl_derma.id_kempen', $request->id_kempen);
        }

        // Laksanakan query
        $laporan = $query->orderBy('tbl_derma.tarikh_derma', 'desc')->get();

        // 3. Pengiraan Ringkasan Laporan (Summary Report)
        $summary = [
            'jumlah_kutipan' => $laporan->where('status_bayaran', 'Berjaya')->sum('amaun_derma'),
            'jumlah_transaksi' => $laporan->count(),
            'transaksi_berjaya' => $laporan->where('status_bayaran', 'Berjaya')->count(),
        ];

        // Dapatkan senarai kempen untuk dropdown filter
        $kempenQuery = DB::table('tbl_kempen');
        if ($user && $user->isOrganisasi()) {
            $kempenQuery->join('tbl_organisasi', 'tbl_kempen.id_organisasi', '=', 'tbl_organisasi.id_organisasi')
                        ->where('tbl_organisasi.id_user', $user->id_user);
        }
        $senaraiKempen = $kempenQuery->select('tbl_kempen.id_kempen', 'tbl_kempen.tajuk_kempen')->get();

        return view('reports.index', compact('laporan', 'summary', 'senaraiKempen'));
    }

    /**
     * Eksport laporan untuk dicetak
     */
    public function cetak(Request $request)
    {
        $user = auth()->user();

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

        if ($user && $user->isOrganisasi()) {
            $query->where('tbl_organisasi.id_user', $user->id_user);
        }

        if ($request->filled('bulan')) {
            $query->whereMonth('tbl_derma.tarikh_derma', $request->bulan);
        }
        if ($request->filled('tahun')) {
            $query->whereYear('tbl_derma.tarikh_derma', $request->tahun);
        }
        if ($request->filled('status')) {
            $query->where('tbl_derma.status_bayaran', $request->status);
        }
        if ($request->filled('id_kempen')) {
            $query->where('tbl_derma.id_kempen', $request->id_kempen);
        }

        $laporan = $query->orderBy('tbl_derma.tarikh_derma', 'desc')->get();

        $summary = [
            'jumlah_kutipan' => $laporan->where('status_bayaran', 'Berjaya')->sum('amaun_derma'),
            'jumlah_transaksi' => $laporan->count(),
            'transaksi_berjaya' => $laporan->where('status_bayaran', 'Berjaya')->count(),
        ];

        return view('reports.print', compact('laporan', 'summary'));
    }
}
