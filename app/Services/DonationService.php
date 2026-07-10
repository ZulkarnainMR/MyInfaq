<?php

namespace App\Services;

use App\Models\Derma;
use App\Models\Kempen;
use Illuminate\Support\Facades\DB;

class DonationService
{
    protected ToyyibPayService $toyyibPayService;

    public function __construct(ToyyibPayService $toyyibPayService)
    {
        $this->toyyibPayService = $toyyibPayService;
    }

    /**
     * Proses derma baharu dan cipta bil ToyyibPay.
     *
     * @param Kempen $kempen
     * @param array $data Data request yang divalidasi (amaun_derma, nota, has_tip)
     * @param mixed $user Model User (jika ada)
     * @param mixed $penderma Model Penderma (jika ada)
     * @return string URL pembayaran ToyyibPay
     * @throws \RuntimeException Jika gagal mencipta bil
     */
    public function processDonation(Kempen $kempen, array $data, $user, $penderma): string
    {
        $tip         = !empty($data['has_tip']) ? 2.00 : 0.00;
        $totalAmount = $data['amaun_derma'] + $tip;

        $result = DB::transaction(function () use ($kempen, $data, $penderma, $user, $tip, $totalAmount) {

            // 1. Cipta rekod derma dengan status Pending
            $derma = Derma::create([
                'id_kempen'      => $kempen->id_kempen,
                'id_penderma'    => $penderma?->id_penderma,
                'amaun_derma'    => $data['amaun_derma'],
                'platform_tip'   => $tip,
                'status_bayaran' => 'Pending',
                'kaedah_bayaran' => 'ToyyibPay',
                'nota'           => $data['nota'] ?? null,
            ]);

            // 2. Cipta bil di ToyyibPay
            $billCode = $this->toyyibPayService->createBill([
                'name'         => 'Derma: ' . $kempen->tajuk_kempen . ($tip > 0 ? ' (Termasuk Tip RM2)' : ''),
                'description'  => 'Kempen oleh ' . ($kempen->organisasi?->nama_organisasi ?? 'MyInfaq'),
                'amount'       => $totalAmount,
                'return_url'   => route('public.derma.return'),
                'callback_url' => route('public.derma.callback'),
                'order_id'     => $derma->no_resit,
                'payer_name'   => $penderma?->nama_penderma ?? ($user?->name ?? 'Penderma'),
                'payer_email'  => $user?->email ?? 'noreply@myinfaq.com',
                'payer_phone'  => $penderma?->no_telefon ?? '0123456789',
            ]);

            // 3. Jika gagal cipta bil, lempar exception supaya transaction di-rollback
            if (!$billCode) {
                throw new \RuntimeException('Gagal mendapatkan bill code daripada ToyyibPay.');
            }

            // 4. Simpan bill_code ke rekod derma (dalam transaction yang sama)
            $derma->update(['bill_code' => $billCode]);

            return ['derma' => $derma, 'billCode' => $billCode];
        });

        // 5. Kembalikan URL pembayaran ToyyibPay
        return $this->toyyibPayService->getPaymentUrl($result['billCode']);
    }
}
