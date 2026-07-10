<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ToyyibPayService
{
    protected string $secretKey;
    protected string $categoryCode;
    protected string $baseUrl;
    protected string $createBillEndpoint;

    public function __construct()
    {
        $this->secretKey          = config('toyyibpay.secret_key');
        $this->categoryCode       = config('toyyibpay.category_code');
        $this->baseUrl            = rtrim(config('toyyibpay.url'), '/');
        $this->createBillEndpoint = config('toyyibpay.create_bill_endpoint');
    }

    /**
     * Cipta bil baharu di ToyyibPay.
     *
     * @param  array  $data  [
     *   'name'         => Nama bil,
     *   'description'  => Penerangan bil,
     *   'amount'       => Amaun dalam sen (100 = RM1.00),
     *   'return_url'   => URL selepas bayar,
     *   'callback_url' => URL callback untuk notifikasi server,
     *   'order_id'     => ID unik tempahan,
     *   'payer_name'   => Nama pembayar,
     *   'payer_email'  => E-mel pembayar,
     *   'payer_phone'  => No. telefon pembayar,
     * ]
     * @return string|null  Bill code jika berjaya, null jika gagal
     */
    public function createBill(array $data): ?string
    {
        try {
            $payload = [
                'userSecretKey'          => $this->secretKey,
                'categoryCode'           => $this->categoryCode,
                'billName'               => substr($data['name'], 0, 30),
                'billDescription'        => substr($data['description'], 0, 100),
                'billPriceSetting'       => 1,          // 1 = fixed amount
                'billPayorInfo'          => 1,          // 1 = require payor info
                'billAmount'             => (int) round($data['amount'] * 100), // dalam sen
                'billReturnUrl'          => $data['return_url'],
                'billCallbackUrl'        => $data['callback_url'],
                'billExternalReferenceNo'=> $data['order_id'],
                'billTo'                 => $data['payer_name'],
                'billEmail'              => $data['payer_email'],
                'billPhone'              => $data['payer_phone'] ?? '0123456789',
                'billSplitPayment'       => 0,
                'billSplitPaymentArgs'   => '',
                'billPaymentChannel'     => 0,          // 0 = semua kaedah
                'billContentEmail'       => 'Terima kasih kerana menyumbang melalui MyInfaq.',
                'billChargeToCustomer'   => 1,          // 1 = fi dikenakan kepada pelanggan
                'billDisplayMerchant'    => 1,
            ];

            $response = Http::asForm()
                ->timeout(30)
                ->post($this->baseUrl . '/' . $this->createBillEndpoint, $payload);

            if ($response->successful()) {
                $result = $response->json();

                // ToyyibPay returns array of objects
                if (is_array($result) && isset($result[0]['BillCode'])) {
                    return $result[0]['BillCode'];
                }
            }

            Log::error('ToyyibPay createBill gagal', [
                'status'   => $response->status(),
                'response' => $response->body(),
                'payload'  => array_merge($payload, ['userSecretKey' => '***']),
            ]);

            return null;

        } catch (\Throwable $e) {
            Log::error('ToyyibPay exception', ['message' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Dapatkan URL halaman pembayaran ToyyibPay.
     */
    public function getPaymentUrl(string $billCode): string
    {
        return $this->baseUrl . '/' . $billCode;
    }
}
