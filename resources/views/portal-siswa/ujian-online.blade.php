<x-layouts.portal-siswa title="Ujian Online - Portal Siswa">
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-primary">Ujian Online</h2>
        <p class="text-on-surface-variant font-body-md">Ikuti ujian online yang tersedia</p>
    </div>
</div>

@if($ujianList->isNotEmpty())
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-gutter">
    @foreach($ujianList as $ujian)
    <div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden flex flex-col hover:translate-y-[-2px] transition-all duration-300">
        <div class="p-5 flex-1">
            <div class="flex items-start justify-between mb-3">
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold
                    {{ $ujian->status == 'selesai' ? 'bg-surface-container text-outline' : '' }}
                    {{ $ujian->status == 'sedang_berlangsung' ? 'bg-secondary-container text-secondary' : '' }}
                    {{ $ujian->status == 'akan_datang' ? 'bg-primary-fixed text-primary' : '' }}
                    {{ $ujian->status == 'draft' ? 'bg-error-container text-error' : '' }}">
                    {{ str_replace('_', ' ', ucfirst($ujian->status)) }}
                </span>
                @if($ujian->sudah_dikerjakan && $ujian->nilai)
                <span class="text-sm font-bold {{ $ujian->nilai->skor >= 75 ? 'text-green-600' : 'text-error' }}">
                    {{ $ujian->nilai->skor }}
                </span>
                @endif
            </div>
            <h3 class="font-headline-sm text-headline-sm text-primary mb-3">{{ $ujian->nama }}</h3>
            <div class="space-y-2 text-sm text-on-surface-variant">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">book</span>
                    {{ $ujian->mapel->nama ?? '-' }}
                </div>
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">timer</span>
                    {{ $ujian->durasi }} menit
                </div>
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">event</span>
                    {{ $ujian->tanggal_mulai->isoFormat('DD MMM YYYY') }}
                    @if($ujian->tanggal_selesai && $ujian->tanggal_selesai != $ujian->tanggal_mulai)
                    - {{ $ujian->tanggal_selesai->isoFormat('DD MMM YYYY') }}
                    @endif
                </div>
            </div>
        </div>
        <div class="px-5 py-3 border-t border-outline-variant bg-surface-container-low">
            @if($ujian->sudah_dikerjakan)
            <a href="{{ route('ujian.hasil', $ujian->slug) }}" class="w-full flex items-center justify-center gap-2 px-4 py-2 border border-outline-variant rounded-lg text-sm font-bold text-on-surface hover:bg-surface-container transition-all active:scale-95">
                <span class="material-symbols-outlined text-[18px]">visibility</span>
                Lihat Hasil
            </a>
            @elseif($ujian->status == 'sedang_berlangsung' || $ujian->status == 'akan_datang')
            <a href="{{ route('ujian.sedang-berlangsung', $ujian->slug) }}" class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-primary text-on-primary rounded-lg text-sm font-bold hover:bg-primary/90 transition-all active:scale-95">
                <span class="material-symbols-outlined text-[18px]">play_arrow</span>
                {{ $ujian->status == 'sedang_berlangsung' ? 'Mulai Ujian' : 'Lihat Detail' }}
            </a>
            @else
            <button disabled class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-surface-container text-outline rounded-lg text-sm font-bold cursor-not-allowed">
                <span class="material-symbols-outlined text-[18px]">lock</span>
                Tidak Tersedia
            </button>
            @endif
        </div>
    </div>
    @endforeach
</div>
@else
<div class="bg-white rounded-xl border border-outline-variant card-shadow p-12 text-center">
    <span class="material-symbols-outlined text-6xl text-outline mb-4">quiz</span>
    <h3 class="font-headline-sm text-headline-sm text-on-surface mb-2">Belum Ada Ujian</h3>
    <p class="text-on-surface-variant">Belum ada ujian yang tersedia untuk kelas Anda.</p>
</div>
@endif
</x-layouts.portal-siswa>
