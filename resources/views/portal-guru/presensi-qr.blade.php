<x-layouts.portal-guru title="QR Code Presensi | EduPortal">
<div class="max-w-md mx-auto text-center">
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-8">
        <h2 class="font-headline-sm text-headline-sm text-primary mb-2">Token Presensi</h2>
        <p class="text-on-surface-variant mb-6">{{ $presensiGuru->tanggal->isoFormat('dddd, D MMMM YYYY') }}</p>

        <div class="bg-surface-container-low rounded-xl p-8 mb-6">
            <div class="w-48 h-48 mx-auto flex items-center justify-center">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={{ urlencode(route('portal-guru.presensi-guru.scan-token', $qrToken)) }}" alt="QR Code Presensi" class="w-full h-full rounded-lg">
            </div>
        </div>

        <div class="space-y-2 mb-6">
            <p class="text-sm text-on-surface-variant">Token:</p>
            <p class="text-2xl font-bold text-primary tracking-widest font-mono">{{ $qrToken }}</p>
            <p class="text-xs text-on-surface-variant">Scan QR Code menggunakan kamera ponsel atau masukkan token di halaman Presensi</p>
        </div>

        <div class="flex flex-col gap-2">
            @if(!$presensiGuru->check_in)
            <span class="px-4 py-2 bg-amber-100 text-amber-700 rounded-lg text-sm font-bold">Belum Check-in</span>
            @elseif($presensiGuru->check_in && !$presensiGuru->check_out)
            <span class="px-4 py-2 bg-green-100 text-green-700 rounded-lg text-sm font-bold">
                Check-in: {{ $presensiGuru->check_in }}
            </span>
            @else
            <span class="px-4 py-2 bg-blue-100 text-blue-700 rounded-lg text-sm font-bold">
                Check-in: {{ $presensiGuru->check_in }} | Check-out: {{ $presensiGuru->check_out }}
            </span>
            @endif
        </div>

        <a href="{{ route('portal-guru.presensi-guru') }}" class="inline-flex items-center gap-2 mt-6 px-6 py-3 border border-outline-variant rounded-xl font-label-md text-outline hover:bg-surface-container transition-all">
            <span class="material-symbols-outlined">arrow_back</span>
            Kembali
        </a>
    </div>
</div>
</x-layouts.portal-guru>
