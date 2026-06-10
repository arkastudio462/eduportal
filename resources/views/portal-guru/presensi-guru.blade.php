<x-layouts.portal-guru title="Presensi Guru | EduPortal">
    <x-slot:styles>
    <style>[x-cloak] { display: none !important; }</style>
    </x-slot:styles>
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-primary">Presensi Guru</h2>
        <p class="text-on-surface-variant font-body-md">Check-in / Check-out via QR Code</p>
    </div>
</div>

@if($guru)
<div class="grid grid-cols-1 md:grid-cols-3 gap-gutter mb-6">
    <div class="bg-white p-6 rounded-xl border border-outline-variant card-shadow">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-primary-fixed text-primary flex items-center justify-center">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL'1">badge</span>
            </div>
            <div>
                <p class="text-xs text-on-surface-variant">Nama Guru</p>
                <h3 class="font-headline-md text-headline-md text-primary">{{ $guru->user->name }}</h3>
            </div>
        </div>
    </div>
    <div class="bg-white p-6 rounded-xl border border-outline-variant card-shadow">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-secondary-fixed text-secondary flex items-center justify-center">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL'1">today</span>
            </div>
            <div>
                <p class="text-xs text-on-surface-variant">Hari Ini</p>
                <h3 class="font-headline-md text-headline-md text-primary">{{ now()->isoFormat('dddd, D MMMM YYYY') }}</h3>
            </div>
        </div>
    </div>
    <div class="bg-white p-6 rounded-xl border border-outline-variant card-shadow">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-tertiary-fixed text-on-tertiary-container flex items-center justify-center">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL'1">qr_code_scanner</span>
            </div>
            <div>
                <p class="text-xs text-on-surface-variant">Scan QR Code</p>
                <h3 class="font-headline-md text-headline-md text-primary">Gunakan Kamera</h3>
            </div>
        </div>
    </div>
</div>

<div x-data="{ scanning: false, manualInput: false }" class="mb-8">
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-6">
        <div class="flex flex-col items-center text-center">
            <span class="material-symbols-outlined text-5xl text-primary mb-3">qr_code_scanner</span>
            <h3 class="font-headline-sm text-headline-sm mb-2">Scan QR Code Presensi</h3>
            <p class="text-on-surface-variant mb-4 max-w-md">Arahkan kamera ke QR Code yang ditampilkan admin untuk check-in / check-out otomatis.</p>

            <div class="flex gap-3 mb-6">
                <button @click="scanning = true; manualInput = false; setTimeout(() => startScanner(), 100)" x-show="!scanning" class="px-6 py-3 bg-primary text-on-primary rounded-xl font-label-md hover:bg-primary/90 transition-all active:scale-95 flex items-center gap-2">
                    <span class="material-symbols-outlined">photo_camera</span>
                    Buka Kamera
                </button>
                <button @click="manualInput = !manualInput; scanning = false; stopScanner()" class="px-6 py-3 border border-outline-variant rounded-xl font-label-md text-outline hover:bg-surface-container transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined">keyboard</span>
                    Input Manual
                </button>
            </div>

            {{-- Camera Scanner --}}
            <div x-show="scanning" x-cloak class="w-full max-w-sm mx-auto">
                <div id="qr-reader" class="w-full rounded-xl overflow-hidden border border-outline-variant mb-3"></div>
                <p class="text-sm text-on-surface-variant">Tunggu hingga QR Code terdeteksi secara otomatis.</p>
                <button @click="scanning = false; stopScanner()" class="mt-4 px-4 py-2 bg-error text-on-primary rounded-lg font-label-md hover:bg-error/90 transition-all">Tutup Kamera</button>
            </div>

            {{-- Manual Input --}}
            <form x-show="manualInput" x-cloak method="POST" action="{{ route('portal-guru.presensi-guru.scan') }}" class="w-full max-w-md">
                @csrf
                <div class="flex gap-3">
                    <input type="text" name="qr_token" required placeholder="Masukkan token QR Code..." class="flex-1 px-4 py-3 border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none">
                    <button type="submit" class="px-6 py-3 bg-primary text-on-primary rounded-xl font-label-md hover:bg-primary/90 transition-all active:scale-95">
                        <span class="material-symbols-outlined">qr_code_scanner</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
let html5QrCode = null;

function startScanner() {
    if (html5QrCode) { html5QrCode.clear(); }

    html5QrCode = new Html5Qrcode("qr-reader");
    html5QrCode.start(
        { facingMode: "environment" },
        { fps: 10, qrbox: { width: 250, height: 250 } },
        (decodedText) => {
            let token = decodedText.trim();
            // Extract token from URL if QR encodes a full URL
            if (token.includes('/')) {
                const parts = token.split('/');
                token = parts[parts.length - 1].split('?')[0];
            }
            if (token.length > 5) {
                stopScanner();
                // Auto-submit via a hidden form
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route('portal-guru.presensi-guru.scan') }}';
                form.innerHTML = '@csrf<input type="hidden" name="qr_token" value="' + token + '">';
                document.body.appendChild(form);
                form.submit();
            }
        },
        () => {}
    ).catch(() => {
        alert('Kamera tidak tersedia. Silakan gunakan input manual.');
        document.querySelector('[x-data]').__x.$data.scanning = false;
    });
}

function stopScanner() {
    if (html5QrCode) {
        html5QrCode.stop().catch(() => {});
        html5QrCode.clear().catch(() => {});
        html5QrCode = null;
    }
}
</script>

<div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-outline-variant flex items-center justify-between">
        <h3 class="font-headline-sm text-headline-sm">Riwayat Presensi</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-surface-container-low">
                <tr>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Tanggal</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Check In</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Check Out</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Status</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Keterangan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant">
                @forelse($riwayat as $presensi)
                <tr class="hover:bg-surface-container transition-colors">
                    <td class="px-6 py-4 font-body-md font-semibold">{{ $presensi->tanggal->isoFormat('D MMM YYYY') }}</td>
                    <td class="px-6 py-4">{{ $presensi->check_in ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $presensi->check_out ?? '-' }}</td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-xs font-bold
                            {{ $presensi->status == 'hadir' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $presensi->status == 'izin' ? 'bg-amber-100 text-amber-700' : '' }}
                            {{ $presensi->status == 'sakit' ? 'bg-red-100 text-red-700' : '' }}
                            {{ $presensi->status == 'alpha' ? 'bg-gray-100 text-gray-500' : '' }}">
                            {{ ucfirst($presensi->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-on-surface-variant">{{ $presensi->keterangan ?? '-' }}</td>
                </tr>
                @empty
                <tr><td class="px-6 py-8 text-center text-on-surface-variant" colspan="5">Belum ada riwayat presensi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($riwayat instanceof \Illuminate\Contracts\Pagination\Paginator && $riwayat->hasPages())
    <div class="p-4 border-t border-outline-variant">{{ $riwayat->links('vendor.pagination.custom') }}</div>
    @endif
</div>
@else
<div class="bg-white rounded-xl border border-outline-variant card-shadow p-12 text-center">
    <span class="material-symbols-outlined text-6xl text-outline mb-4">person_off</span>
    <h3 class="font-headline-sm text-headline-sm text-on-surface mb-2">Data Guru Tidak Ditemukan</h3>
    <p class="text-on-surface-variant">Hubungi admin untuk informasi lebih lanjut.</p>
</div>
@endif
</x-layouts.portal-guru>
