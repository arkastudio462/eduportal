<x-layouts.portal-guru title="Jadwal Pelajaran - Portal Guru">
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-primary">Jadwal Pelajaran</h2>
        <p class="text-on-surface-variant font-body-md">Lihat jadwal mengajar Anda</p>
    </div>
    <a href="{{ route('portal-guru.jadwal.print') }}" target="_blank" class="px-4 py-2.5 border border-outline-variant rounded-lg flex items-center gap-2 font-label-md text-outline hover:bg-surface-container transition-all">
        <span class="material-symbols-outlined">print</span>
        Cetak PDF
    </a>
</div>

@if($semuaJadwal->isEmpty())
<div class="bg-white rounded-xl border border-outline-variant card-shadow p-8 text-center">
    <span class="material-symbols-outlined text-5xl text-outline mb-4">calendar_month</span>
    <h3 class="font-headline-sm text-headline-sm text-on-surface mb-2">Belum Ada Jadwal</h3>
    <p class="text-on-surface-variant">Jadwal mengajar belum tersedia.</p>
</div>
@else
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-gutter">
    @foreach($hariMap as $hari)
    @php $jadwalHari = $semuaJadwal->get($hari, collect()); @endphp
    <div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
        <div class="px-5 py-4 bg-primary-fixed border-b border-outline-variant">
            <h3 class="font-headline-sm text-headline-sm text-primary">{{ $hari }}</h3>
            <p class="text-xs text-on-surface-variant">{{ $jadwalHari->count() }} jadwal</p>
        </div>
        <div class="p-4 space-y-3">
            @forelse($jadwalHari as $j)
            <div class="p-4 bg-surface-container-low rounded-xl border border-outline-variant">
                <div class="flex items-start justify-between gap-2 mb-2">
                    <span class="font-bold text-sm bg-secondary-fixed text-secondary px-2 py-0.5 rounded">{{ $j->mapel->nama ?? '-' }}</span>
                    <span class="text-xs text-outline font-mono">{{ $j->jam_mulai }} - {{ $j->jam_selesai }}</span>
                </div>
                <div class="text-sm">
                    <span class="text-on-surface-variant">Kelas:</span> <span class="font-semibold">{{ $j->kelas->nama ?? '-' }}</span>
                </div>
                @if($j->ruang)
                <div class="text-sm">
                    <span class="text-on-surface-variant">Ruang:</span> <span class="font-semibold">{{ $j->ruang }}</span>
                </div>
                @endif
            </div>
            @empty
            <div class="py-6 text-center text-on-surface-variant text-sm">Tidak ada jadwal</div>
            @endforelse
        </div>
    </div>
    @endforeach
</div>
@endif
</x-layouts.portal-guru>
