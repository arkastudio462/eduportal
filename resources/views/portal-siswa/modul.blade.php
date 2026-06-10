<x-layouts.portal-siswa title="Modul Bahan Ajar">
<x-slot:styles>
<style>[x-cloak] { display: none !important; }</style>
</x-slot:styles>
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-primary">Modul Bahan Ajar</h2>
        <p class="text-on-surface-variant font-body-md">Akses modul pembelajaran dari guru</p>
    </div>
</div>

@if($moduls->isEmpty())
<div class="bg-white rounded-xl border border-outline-variant card-shadow p-8 text-center">
    <span class="material-symbols-outlined text-4xl text-outline mb-2">folder_off</span>
    <p class="font-body-md text-outline">Belum ada modul yang tersedia</p>
</div>
@else
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    @foreach($moduls as $modul)
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-5 hover:translate-y-[-4px] transition-transform duration-200">
        <div class="flex items-start gap-3 mb-3">
            <div class="w-10 h-10 rounded-lg bg-secondary-container text-on-secondary-container flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-xl">description</span>
            </div>
            <div class="min-w-0 flex-1">
                <h3 class="font-label-md text-label-md text-primary truncate">{{ $modul->judul }}</h3>
                <p class="text-xs text-on-surface-variant">{{ $modul->guru?->user?->name ?? '-' }} • {{ $modul->mapel?->nama ?? '-' }}</p>
            </div>
        </div>
        @if($modul->deskripsi)
        <p class="text-xs text-on-surface-variant mb-3 line-clamp-2">{{ $modul->deskripsi }}</p>
        @endif
        <div class="flex items-center justify-between pt-3 border-t border-outline-variant">
            <span class="text-xs text-on-surface-variant">{{ strtoupper($modul->ekstensi) }} • {{ round($modul->ukuran / 1024) }}KB</span>
            <a href="{{ route('portal-siswa.modul.download', $modul) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-primary text-on-primary rounded-lg text-xs hover:bg-primary-container transition-colors">
                <span class="material-symbols-outlined text-[16px]">download</span>
                Download
            </a>
        </div>
    </div>
    @endforeach
</div>
@endif
</x-layouts.portal-siswa>
