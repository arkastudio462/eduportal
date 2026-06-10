<?php

use App\Mail\SppReminderMail;
use App\Models\PembayaranSpp;
use App\Models\Siswa;
use App\Models\Ujian;
use App\Notifications\SppReminderNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schedule;

Schedule::call(function () {
    Ujian::where('status', 'sedang_berlangsung')
        ->where('tanggal_selesai', '<', now())
        ->update(['status' => 'selesai']);
})->everyMinute()->name('auto-expire-ujian');

Schedule::call(function () {
    $bulan = now()->format('m');
    $tahun = now()->format('Y');
    $defaultJumlah = 750000;

    $siswaAktif = Siswa::aktif()->get();

    foreach ($siswaAktif as $siswa) {
        $exists = PembayaranSpp::where('siswa_id', $siswa->id)
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->exists();

        if (! $exists) {
            PembayaranSpp::create([
                'siswa_id' => $siswa->id,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'jumlah' => $defaultJumlah,
                'status' => 'belum_lunas',
            ]);
        }
    }
})->monthly()->name('generate-spp-bulanan');

Schedule::call(function () {
    $now = now();
    $bulan = $now->format('m');
    $tahun = $now->format('Y');

    $tagihanBelumLunas = PembayaranSpp::with('siswa.user')
        ->where('bulan', $bulan)
        ->where('tahun', $tahun)
        ->where('status', 'belum_lunas')
        ->whereNull('midtrans_transaction_id')
        ->get();

    foreach ($tagihanBelumLunas as $tagihan) {
        $user = $tagihan->siswa?->user;
        if (! $user) {
            continue;
        }

        try {
            $user->notify(new SppReminderNotification($tagihan));
        } catch (Exception $e) {
            report($e);
        }

        try {
            if ($user->email) {
                Mail::to($user->email)->send(new SppReminderMail($tagihan));
            }
        } catch (Exception $e) {
            report($e);
        }
    }
})->weeklyOn(1, '8:00')->name('spp-reminder');

Schedule::command('session:gc')->daily()->name('cleanup-sessions');

Schedule::command('db:backup')->dailyAt('02:00')->name('auto-backup-database');
