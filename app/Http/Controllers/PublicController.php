<?php

namespace App\Http\Controllers;

use App\Models\Kempen;
use App\Models\Ketelusan;
use App\Models\Derma;
use App\Models\Penderma;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    /** Homepage with active campaign listing & search */
    public function index(Request $request)
    {
        $query = Kempen::with('organisasi')
            ->where('status_kempen', 'Aktif');

        if ($search = $request->get('cari')) {
            $query->where(function ($q) use ($search) {
                $q->where('tajuk_kempen', 'like', "%{$search}%")
                  ->orWhere('keterangan_kempen', 'like', "%{$search}%")
                  ->orWhereHas('organisasi', fn ($q2) => $q2->where('nama_organisasi', 'like', "%{$search}%"));
            });
        }

        $kempen = $query->withCount('derma')
            ->orderByDesc('jumlah_kutipan_semasa')
            ->paginate(9)
            ->withQueryString();

        $totalKutipan    = Derma::where('status_bayaran', 'Berjaya')->sum('amaun_derma');
        $totalKempenAktif = Kempen::where('status_kempen', 'Aktif')->count();
        $totalPenderma   = Penderma::count();

        $testimonis = \App\Models\Testimoni::where('status', 'Approved')
            ->where('bintang', '>=', 4)
            ->inRandomOrder()
            ->limit(6)
            ->get();

        $pendermaTerbaru = Penderma::latest()->limit(4)->get();
        $dermaTerbaru = Derma::where('status_bayaran', 'Berjaya')->latest()->first(['amaun_derma as jumlah']);

        return view('public.home', compact('kempen', 'totalKutipan', 'totalKempenAktif', 'totalPenderma', 'testimonis', 'pendermaTerbaru', 'dermaTerbaru'));
    }

    /**
     * Dedicated page for listing all campaigns with search, category & sort filters.
     *
     * Query Parameters:
     *   cari      - Carian teks bebas (tajuk, keterangan, nama organisasi)
     *   kategori  - Filter kategori (Pendidikan, Kesihatan, dll.)
     *   susun     - Pengisihan: terbaru|popular|hampir_penuh|tarikh_tamat
     *   tamat     - Filter kempen yang hampir tamat (dalam N hari)
     */
    public function senaraiKempen(Request $request)
    {
        $query = Kempen::with('organisasi')->where('status_kempen', 'Aktif');

        // ── Filter 1: Carian teks ─────────────────────────────────────────────
        if ($search = $request->get('cari')) {
            $query->where(function ($q) use ($search) {
                $q->where('tajuk_kempen', 'like', "%{$search}%")
                  ->orWhere('keterangan_kempen', 'like', "%{$search}%")
                  ->orWhereHas('organisasi', fn ($q2) => $q2->where('nama_organisasi', 'like', "%{$search}%"));
            });
        }

        // ── Filter 2: Kategori ───────────────────────────────────────────────
        if ($kategori = $request->get('kategori')) {
            if ($kategori !== 'Semua') {
                $query->where('kategori', $kategori);
            }
        }

        // ── Filter 3: Kempen hampir tamat (dalam N hari) ──────────────────────────
        if ($hariTamat = $request->get('tamat_dalam')) {
            $query->whereNotNull('tarikh_tamat')
                  ->whereDate('tarikh_tamat', '>=', now())
                  ->whereDate('tarikh_tamat', '<=', now()->addDays((int) $hariTamat));
        }

        // ── Pengisihan (Sort) ────────────────────────────────────────────────
        $susun = $request->get('susun', 'terbaru');
        $query = match ($susun) {
            'popular'     => $query->orderByDesc('jumlah_kutipan_semasa'),
            'hampir_penuh' => $query->orderByRaw(
                '(jumlah_kutipan_semasa / NULLIF(sasaran_dana, 0)) DESC'
            ),
            'tarikh_tamat' => $query->orderBy('tarikh_tamat'),
            default        => $query->orderByDesc('created_at'),  // terbaru
        };

        $kempen = $query->withCount('derma')
            ->paginate(12)
            ->withQueryString();

        return view('public.senarai-kempen', compact('kempen', 'susun'));
    }

    /** Single campaign detail page */
    public function kempen(Kempen $kempen)
    {
        abort_unless($kempen->status_kempen === 'Aktif', 404);
        $kempen->load(['organisasi', 'derma' => fn ($q) => $q->where('status_bayaran', 'Berjaya')->latest()->limit(10)]);
        return view('public.kempen', compact('kempen'));
    }

    public function hantarTestimoni(Request $request)
    {
        $request->validate([
            'nama'    => 'required|string|max:255',
            'bintang' => 'required|integer|min:1|max:5',
            'ulasan'  => 'required|string|max:1000',
        ]);

        \App\Models\Testimoni::create([
            'nama'    => $request->nama,
            'peranan' => 'Penderma',
            'bintang' => $request->bintang,
            'ulasan'  => $request->ulasan,
            'status'  => 'Pending',
        ]);

        return response()->json(['success' => true]);
    }

    /** Public transparency / history page */
    public function ketelusan(Request $request)
    {
        $kempenSelesai = Kempen::with(['organisasi', 'ketelusan' => fn ($q) => $q->where('status_audit', 'Diluluskan')])
            ->whereIn('status_kempen', ['Selesai', 'Dibayar'])
            ->latest()
            ->paginate(8);

        return view('public.ketelusan', compact('kempenSelesai'));
    }

    /** Donor personal history dashboard */
    public function riwayatDerma()
    {
        $penderma = auth()->user()->penderma;
        abort_unless($penderma, 403);

        $derma = $penderma->derma()
            ->with('kempen')
            ->where('status_bayaran', 'Berjaya')
            ->latest()
            ->paginate(10);

        $totalDerma   = $penderma->derma()->where('status_bayaran', 'Berjaya')->sum('amaun_derma');
        $jumlahKempen = $penderma->derma()->where('status_bayaran', 'Berjaya')->distinct('id_kempen')->count('id_kempen');

        return view('public.riwayat', compact('derma', 'totalDerma', 'jumlahKempen'));
    }
}
