<?php

namespace App\Http\Controllers;

use App\Models\Derma;
use App\Models\Kempen;
use App\Services\ToyyibPayService;
use App\Services\DonationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DonationController extends Controller
{
    protected DonationService $donationService;

    public function __construct(DonationService $donationService)
    {
        $this->donationService = $donationService;
    }

    /** Papar borang bayaran derma */
    public function borangBayaran(Kempen $kempen)
    {
        abort_unless($kempen->status_kempen === 'Aktif', 404);
        return view('public.checkout', compact('kempen'));
    }

    /**
     * Create ToyyibPay bill and redirect to payment page.
     *
     * Dibungkus dalam DB::transaction supaya jika pangkalan data gagal
     * sewaktu Derma::create atau update bill_code, rekod tidak tergantung 
     * Try-catch menangkap kegagalan API ToyyibPay atau exception yang tidak dijangka.
     */
    public function proses(Request $request, Kempen $kempen)
    {
        abort_unless($kempen->status_kempen === 'Aktif', 404);

        $request->validate([
            'amaun_derma' => ['required', 'numeric', 'min:1', 'max:999999'],
            'nota'        => ['nullable', 'string', 'max:500'],
        ]);

        try {
            $paymentUrl = $this->donationService->processDonation(
                $kempen,
                [
                    'amaun_derma' => $request->amaun_derma,
                    'nota'        => $request->nota,
                    'has_tip'     => $request->has('platform_tip')
                ],
                auth()->user(),
                auth()->user()?->penderma
            );

            return redirect($paymentUrl);

        } catch (\RuntimeException $e) {
            // Kegagalan API ToyyibPay — transaction sudah di-rollback secara automatik
            Log::warning('ToyyibPay: Gagal mencipta bil', [
                'kempen'  => $kempen->id_kempen,
                'message' => $e->getMessage(),
            ]);
            return back()->withErrors(['payment' => 'Gagal menyambung ke gateway pembayaran. Sila cuba sekali lagi.']);

        } catch (\Throwable $e) {
            // Kegagalan tidak dijangka (contoh: DB down, network error)
            Log::error('DonationController@proses: Exception tidak dijangka', [
                'kempen'  => $kempen->id_kempen,
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);
            return back()->withErrors(['payment' => 'Ralat sistem berlaku. Sila hubungi pentadbir atau cuba sebentar lagi.']);
        }
    }

    /**
     * ToyyibPay Callback (POST) — dipanggil oleh server ToyyibPay selepas pembayaran.
     * Route ini MESTI tanpa auth middleware.
     *
     * Parameter dari ToyyibPay:
     *   refno       = Bill Code
     *   status      = 1 (Berjaya) | 2 (Pending) | 3 (Gagal)
     *   reason      = Sebab (jika gagal)
     *   billcode    = Bill Code
     *   order_id    = No. Resit kita
     *   amount      = Amaun dalam sen
     */
    public function tindakBalas(Request $request)
    {
        Log::info('ToyyibPay Callback diterima', $request->all());

        $billCode = $request->input('billcode') ?? $request->input('refno');
        $status   = $request->input('status');
        $orderId  = $request->input('order_id');

        if (!$billCode && !$orderId) {
            Log::warning('ToyyibPay Callback: tiada billcode atau order_id');
            return response('INVALID', 400);
        }

        try {
            // Cari rekod derma
            $derma = null;
            if ($orderId) {
                $derma = Derma::where('no_resit', $orderId)->first();
            }
            if (!$derma && $billCode) {
                $derma = Derma::where('bill_code', $billCode)->first();
            }

            if (!$derma) {
                Log::warning('ToyyibPay Callback: rekod derma tidak ditemui', compact('billCode', 'orderId'));
                return response('NOT_FOUND', 404);
            }

            // Elak proses semula jika sudah dikemas kini
            if ($derma->status_bayaran === 'Berjaya') {
                return response('OK', 200);
            }

            // Kemas kini status dalam transaction
            // 1 = Berjaya, 2 = Pending, 3 = Gagal
            $statusMap = ['1' => 'Berjaya', '2' => 'Pending', '3' => 'Gagal'];
            $newStatus = $statusMap[$status] ?? 'Gagal';

            DB::transaction(function () use ($derma, $newStatus) {
                $derma->update(['status_bayaran' => $newStatus]);
            });

            Log::info('ToyyibPay Callback: status dikemas kini', [
                'no_resit' => $derma->no_resit,
                'status'   => $newStatus,
            ]);

            return response('OK', 200);

        } catch (\Throwable $e) {
            Log::error('ToyyibPay Callback: Exception berlaku', [
                'billCode' => $billCode,
                'orderId'  => $orderId,
                'message'  => $e->getMessage(),
            ]);
            return response('ERROR', 500);
        }
    }

    /**
     * Return URL (GET) — pengguna diredirect ke sini oleh ToyyibPay selepas bayar.
     *
     * Parameter dari ToyyibPay:
     *   status_id   = 1 (Berjaya) | 2 (Pending) | 3 (Gagal)
     *   billcode    = Bill Code
     *   order_id    = No. Resit kita
     */
    public function lamanKembali(Request $request)
    {
        $billCode = $request->input('billcode');
        $statusId = $request->input('status_id');
        $orderId  = $request->input('order_id');

        try {
            // Cari rekod derma
            $derma = null;
            if ($orderId) {
                $derma = Derma::where('no_resit', $orderId)->first();
            }
            if (!$derma && $billCode) {
                $derma = Derma::where('bill_code', $billCode)->first();
            }

            if (!$derma) {
                return redirect()->route('public.home')
                    ->with('error', 'Rekod pembayaran tidak ditemui.');
            }

            // Jika status belum dikemas kini oleh callback (mungkin lewat), kemas kini di sini
            if ($derma->status_bayaran === 'Pending') {
                $statusMap = ['1' => 'Berjaya', '2' => 'Pending', '3' => 'Gagal'];
                $newStatus = $statusMap[$statusId] ?? 'Gagal';

                if ($newStatus !== 'Pending') {
                    DB::transaction(function () use ($derma, $newStatus) {
                        $derma->update(['status_bayaran' => $newStatus]);
                    });
                }
            }

            $derma->refresh();

            if ($derma->status_bayaran === 'Berjaya') {
                return redirect()->route('public.derma.terima_kasih', $derma->id_resit)
                    ->with('success', 'Derma berjaya! Terima kasih atas sumbangan anda.');
            }

            // Gagal atau Pending
            return redirect()->route('public.derma.gagal', $derma->id_resit);

        } catch (\Throwable $e) {
            Log::error('DonationController@returnUrl: Exception berlaku', [
                'billCode' => $billCode,
                'orderId'  => $orderId,
                'message'  => $e->getMessage(),
            ]);
            return redirect()->route('public.home')
                ->with('error', 'Ralat berlaku semasa memproses pembayaran. Sila semak riwayat derma anda.');
        }
    }

    /** Halaman terima kasih / resit */
    public function terimaKasih(Derma $derma)
    {
        abort_unless($derma->status_bayaran === 'Berjaya', 404);
        $derma->load(['kempen.organisasi', 'penderma']);
        return view('public.terimakasih', compact('derma'));
    }

    /** Failed payment page */
    public function gagal(Derma $derma)
    {
        $derma->load(['kempen.organisasi', 'penderma']);
        return view('public.gagal', compact('derma'));
    }

    /** Download PDF receipt */
    public function muatTurunResit(Derma $derma)
    {
        $user = auth()->user();
        if ($user->role === 'Penderma' && $derma->id_penderma !== $user->penderma?->id_penderma) {
            abort(403, 'Akses tidak dibenarkan.');
        }

        try {
            $derma->load(['kempen.organisasi', 'penderma']);
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.resit', compact('derma'));
            return $pdf->download($derma->no_resit . '.pdf');

        } catch (\Throwable $e) {
            Log::error('DonationController@muatTurunResit: Gagal jana PDF', [
                'no_resit' => $derma->no_resit,
                'message'  => $e->getMessage(),
            ]);
            return back()->with('error', 'Gagal menjana PDF resit. Sila cuba sebentar lagi.');
        }
    }
}
