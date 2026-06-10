<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\SppInvoiceMail;
use App\Models\PembayaranSpp;
use App\Notifications\SppPaidNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MidtransWebhookController extends Controller
{
    public function handle(Request $request)
    {
        try {
            $payload = $request->all();
            Log::info('Midtrans webhook received', $payload);

            $orderId = $payload['order_id'] ?? '';
            $transactionStatus = $payload['transaction_status'] ?? '';
            $fraudStatus = $payload['fraud_status'] ?? '';
            $transactionId = $payload['transaction_id'] ?? '';

            if (! preg_match('/^SPP-(\d+)-\d+$/', $orderId, $matches)) {
                return response()->json(['status' => 'invalid order_id']);
            }

            $pembayaran = PembayaranSpp::find($matches[1]);

            if (! $pembayaran) {
                return response()->json(['status' => 'payment not found'], 404);
            }

            $pembayaran->midtrans_transaction_id = $transactionId;
            $pembayaran->midtrans_status = $transactionStatus;

            if ($transactionStatus === 'capture' || $transactionStatus === 'settlement') {
                if ($fraudStatus === 'accept' || $fraudStatus === '') {
                    $pembayaran->status = 'lunas';
                    $pembayaran->tanggal_bayar = now();
                }
            } elseif ($transactionStatus === 'cancel' || $transactionStatus === 'deny' || $transactionStatus === 'expire') {
                $pembayaran->status = 'belum_lunas';
                $pembayaran->tanggal_bayar = null;
            }

            $pembayaran->save();

            if ($pembayaran->status === 'lunas') {
                $siswa = $pembayaran->siswa->load('user');

                try {
                    $siswa->user->notify(new SppPaidNotification($pembayaran));
                } catch (\Exception $e) {
                    Log::error('Failed to send SPP paid notification: '.$e->getMessage());
                }

                try {
                    if ($siswa->user->email) {
                        Mail::to($siswa->user->email)->send(new SppInvoiceMail($pembayaran));
                    }
                } catch (\Exception $e) {
                    Log::error('Failed to send SPP invoice email: '.$e->getMessage());
                }
            }

            return response()->json(['status' => 'ok']);
        } catch (\Exception $e) {
            Log::error('Midtrans webhook error: '.$e->getMessage());

            return response()->json(['status' => 'error'], 500);
        }
    }
}
