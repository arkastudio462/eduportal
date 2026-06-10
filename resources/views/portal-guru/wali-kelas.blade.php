<x-layouts.portal-guru title="Wali Kelas - Portal Guru">
<x-slot:styles>
<style>[x-cloak] { display: none !important; }</style>
</x-slot:styles>
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-primary">Wali Kelas</h2>
        <p class="text-on-surface-variant font-body-md">
            @if($kelasWali)
            Kelas {{ $kelasWali->nama }} — {{ $kelasWali->tingkat }}
            @else
            Anda belum menjadi wali kelas
            @endif
        </p>
    </div>
</div>

@if(!$kelasWali)
<div class="bg-white rounded-xl border border-outline-variant card-shadow p-8 text-center">
    <span class="material-symbols-outlined text-4xl text-outline mb-2">school</span>
    <p class="font-body-md text-outline">Anda belum ditugaskan sebagai wali kelas</p>
</div>
@else
<div class="mb-6 flex flex-wrap gap-3">
    <button x-data @click="$dispatch('open-modal', 'kirim-pesan')" class="inline-flex items-center gap-2 bg-secondary-container text-on-secondary-container px-4 py-2.5 rounded-xl font-label-md hover:brightness-110 transition-all">
        <span class="material-symbols-outlined text-[18px]">campaign</span>
        Kirim Pesan ke Semua Siswa
    </button>
</div>

{{-- Modal Kirim Pesan --}}
<div x-cloak x-data="{ open: false }" x-show="open" x-on:open-modal.window="if ($event.detail === 'kirim-pesan') open = true" class="fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-black/50" @click="open = false"></div>
    <div class="relative bg-white rounded-xl shadow-xl border border-outline-variant w-full max-w-lg p-6 z-10" @click.outside="open = false">
        <h3 class="font-headline-sm text-headline-sm text-primary mb-4">Kirim Pesan ke Siswa</h3>
        <form method="POST" action="{{ route('portal-guru.wali-kelas.kirim-pesan') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="font-label-sm text-label-sm text-outline mb-1 block">Judul Pesan</label>
                    <input type="text" name="judul" required maxlength="255" class="w-full px-3 py-2 border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none" placeholder="Contoh: Pengumuman dari Wali Kelas">
                </div>
                <div>
                    <label class="font-label-sm text-label-sm text-outline mb-1 block">Isi Pesan</label>
                    <textarea name="pesan" required rows="5" class="w-full px-3 py-2 border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none" placeholder="Tulis pesan untuk siswa..."></textarea>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" @click="open = false" class="px-4 py-2 bg-surface-container-high text-on-surface rounded-lg font-label-md hover:bg-surface-container-higher transition-colors">Batal</button>
                <button type="submit" class="px-4 py-2 bg-secondary-container text-on-secondary-container rounded-lg font-label-md hover:brightness-110 transition-colors">
                    <span class="material-symbols-outlined text-[16px]">send</span>
                    Kirim
                </button>
            </div>
        </form>
    </div>
</div>
<div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-4 text-center">
        <p class="text-2xl font-bold text-primary">{{ $statistik['total'] }}</p>
        <p class="text-xs text-on-surface-variant mt-1">Total Siswa</p>
    </div>
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-4 text-center">
        <p class="text-2xl font-bold text-green-600">{{ $statistik['spp_lunas'] }}</p>
        <p class="text-xs text-on-surface-variant mt-1">SPP Lunas</p>
    </div>
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-4 text-center">
        <p class="text-2xl font-bold text-red-600">{{ $statistik['spp_belum'] }}</p>
        <p class="text-xs text-on-surface-variant mt-1">SPP Belum</p>
    </div>
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-4 text-center">
        <p class="text-2xl font-bold text-blue-600">{{ $statistik['hadir'] }}</p>
        <p class="text-xs text-on-surface-variant mt-1">Hadir Hari Ini</p>
    </div>
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-4 text-center">
        <p class="text-2xl font-bold text-orange-600">{{ $statistik['alpha'] }}</p>
        <p class="text-xs text-on-surface-variant mt-1">Alpha Hari Ini</p>
    </div>
</div>

<div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
    <div class="p-4 border-b border-outline-variant bg-surface-bright">
        <h3 class="font-label-lg text-label-lg text-primary">Daftar Siswa</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-primary text-on-primary">
                    <th class="text-left py-3 px-4 font-label-md">Nama</th>
                    <th class="text-left py-3 px-4 font-label-md">NISN</th>
                    <th class="text-left py-3 px-4 font-label-md">Status SPP</th>
                    <th class="text-center py-3 px-4 font-label-md">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($siswaList as $siswa)
                <tr class="border-t border-outline-variant hover:bg-surface-bright transition-colors">
                    <td class="py-3 px-4 font-body-md">{{ $siswa->user->name }}</td>
                    <td class="py-3 px-4 text-on-surface-variant">{{ $siswa->nisn }}</td>
                    <td class="py-3 px-4">
                        @php
                            $spp = $siswa->pembayaranSpp()
                                ->where('bulan', now()->format('m'))
                                ->where('tahun', now()->format('Y'))
                                ->first();
                        @endphp
                        @if($spp && $spp->status === 'lunas')
                        <span class="px-2 py-0.5 bg-green-100 text-green-700 rounded-full text-xs font-medium">Lunas</span>
                        @else
                        <span class="px-2 py-0.5 bg-red-100 text-red-700 rounded-full text-xs font-medium">Belum</span>
                        @endif
                    </td>
                    <td class="py-3 px-4 text-center">
                        <a href="{{ route('portal-siswa.spp') }}" class="text-primary text-xs hover:underline">
                            <span class="material-symbols-outlined text-lg">visibility</span>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
</x-layouts.portal-guru>
