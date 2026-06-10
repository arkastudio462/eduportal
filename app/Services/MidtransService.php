<?php

namespace App\Services;

use App\Models\PembayaranSpp;
use Carbon\Carbon;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$clientKey = config('services.midtrans.client_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = config('services.midtrans.is_sanitized');
        Config::$is3ds = config('services.midtrans.is_3ds');
    }

    public function createSnapTransaction(PembayaranSpp $pembayaran): string
    {
        $siswa = $pembayaran->siswa->load('user', 'kelas');
        $bulan = Carbon::create()->month((int) $pembayaran->bulan)->locale('id')->monthName;

        $params = [
            'transaction_details' => [
                'order_id' => 'SPP-'.$pembayaran->id.'-'.time(),
                'gross_amount' => (int) $pembayaran->jumlah,
            ],
            'customer_details' => [
                'first_name' => $siswa->user->name,
                'email' => $siswa->user->email,
            ],
            'item_details' => [
                [
                    'id' => 'SPP-'.$pembayaran->bulan.'-'.$pembayaran->tahun,
                    'price' => (int) $pembayaran->jumlah,
                    'quantity' => 1,
                    'name' => 'SPP '.$bulan.' '.$pembayaran->tahun,
                ],
            ],
        ];

        try {
            $transaction = Snap::createTransaction($params);

            $pembayaran->update([
                'snap_token' => $transaction->token,
                'payment_link' => $transaction->redirect_url,
            ]);

            return $transaction->token;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public static function getClientKey(): string
    {
        return config('services.midtrans.client_key');
    }
}
