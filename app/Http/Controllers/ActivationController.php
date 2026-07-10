<?php

namespace App\Http\Controllers;

use App\Models\Organisasi;
use App\Services\ToyyibPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ActivationController extends Controller
{
    /** Show the activation page */
    public function index()
    {
        $organisasi = auth()->user()?->organisasi;

        if (!$organisasi) {
            return redirect()->route('public.home');
        }

        if ($organisasi->payment_status === 'Paid') {
            return redirect('/organisasi');
        }

        $amount = \App\Models\Tetapan::dapatkan('bayaran_pendaftaran_organisasi', 50.00);

        return view('organisasi.activation', compact('organisasi', 'amount'));
    }

    /** Proses bayaran pengaktifan ke ToyyibPay */
    public function proses(Request $request)
    {
        $user = auth()->user();
        $organisasi = $user?->organisasi;

        if (!$organisasi || $organisasi->payment_status === 'Paid') {
            return redirect('/organisasi');
        }

        $toyyibpay = new ToyyibPayService();
        $orderId = 'ACT-' . strtoupper(uniqid()) . '-' . date('Ymd');
        
        $amount = \App\Models\Tetapan::dapatkan('bayaran_pendaftaran_organisasi', 50.00);

        $billCode = $toyyibpay->createBill([
            'name'         => 'Yuran Pengaktifan Akaun Organisasi',
            'description'  => 'Yuran sekali seumur hidup untuk pendaftaran dan semakan KYC.',
            'amount'       => $amount,
            'return_url'   => route('organisasi.activation.return'),
            'callback_url' => route('organisasi.activation.callback'),
            'order_id'     => $orderId,
            'payer_name'   => $organisasi->nama_organisasi,
            'payer_email'  => $user->email,
            'payer_phone'  => $organisasi->no_telefon,
        ]);

        if (!$billCode) {
            return back()->with('error', 'Gagal menyambung ke gateway pembayaran. Sila cuba lagi.');
        }

        $organisasi->update(['activation_bill_code' => $billCode]);

        return redirect($toyyibpay->getPaymentUrl($billCode));
    }

    /** Tindak balas ToyyibPay untuk pengaktifan */
    public function tindakBalas(Request $request)
    {
        Log::info('Activation Callback', $request->all());

        $billCode = $request->input('billcode') ?? $request->input('refno');
        $status   = $request->input('status');

        if (!$billCode) return response('INVALID', 400);

        $organisasi = Organisasi::where('activation_bill_code', $billCode)->first();

        if (!$organisasi) return response('NOT_FOUND', 404);

        if ($status == '1') {
            $organisasi->update(['payment_status' => 'Paid']);
        }

        return response('OK', 200);
    }

    /** Laman kembali untuk pengaktifan */
    public function lamanKembali(Request $request)
    {
        $billCode = $request->input('billcode');
        $statusId = $request->input('status_id');

        $organisasi = Organisasi::where('activation_bill_code', $billCode)->first();

        if (!$organisasi) {
            return redirect()->route('organisasi.activation')->with('error', 'Rekod pembayaran tidak ditemui.');
        }

        if ($statusId == '1') {
            $organisasi->update(['payment_status' => 'Paid']);
            return redirect('/organisasi')->with('success', 'Akaun anda berjaya diaktifkan! Anda kini boleh mencipta kempen.');
        }

        return redirect()->route('organisasi.activation')->with('error', 'Pembayaran gagal. Sila cuba lagi.');
    }
}
