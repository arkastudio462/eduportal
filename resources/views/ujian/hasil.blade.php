<x-layouts.exam-result title="Hasil Ujian | EduPortal">

<x-slot:styles>
<style>
    .ring-progress { transform: rotate(-90deg); }
    .ring-progress circle { transition: stroke-dashoffset 1.5s ease-in-out; }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .animate-in { animation: fadeInUp 0.6s ease-out forwards; opacity: 0; }
    .delay-1 { animation-delay: 0.1s; }
    .delay-2 { animation-delay: 0.2s; }
    .delay-3 { animation-delay: 0.3s; }
    .delay-4 { animation-delay: 0.4s; }
</style>
</x-slot:styles>

@if($nilai && $ujian)
@php
    $detail = $nilai->jawaban_detail ?? [];
    $totalSoal = count($detail);
    $lulus = ($nilai->skor ?? 0) >= 75;
    $circumference = 2 * 3.14159 * 52;
    $offset = $circumference - (($nilai->skor ?? 0) / 100 * $circumference);
@endphp

<div class="max-w-5xl mx-auto space-y-8">
    <!-- Header Card -->
    <div class="bg-white rounded-2xl border border-outline-variant overflow-hidden animate-in delay-1">
        <div class="bg-gradient-to-r from-primary to-[#1a1f4e] px-6 md:px-10 py-8 text-on-primary">
            <div class="flex flex-col md:flex-row items-center gap-6">
                <div class="relative">
                    <svg class="w-28 h-28 ring-progress" viewBox="0 0 120 120">
                        <circle cx="60" cy="60" r="52" fill="none" stroke="rgba(255,255,255,0.15)" stroke-width="12"/>
                        <circle cx="60" cy="60" r="52" fill="none" stroke="white" stroke-width="12" stroke-linecap="round"
                            stroke-dasharray="{{ $circumference }}" stroke-dashoffset="{{ $offset }}" class="drop-shadow-lg"/>
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="text-center">
                            <p class="text-4xl font-bold">{{ $nilai->skor }}</p>
                            <p class="text-xs text-on-primary/80">dari 100</p>
                        </div>
                    </div>
                </div>
                <div class="text-center md:text-left">
                    <h2 class="font-headline-md text-headline-md">{{ $ujian->nama }}</h2>
                    <p class="text-on-primary/80 mt-2">{{ $ujian->mapel?->nama ?? '-' }}</p>
                    <div class="flex items-center gap-4 mt-4 justify-center md:justify-start">
                        <span class="px-4 py-1.5 bg-white/20 backdrop-blur rounded-full text-sm font-bold {{ $lulus ? '' : 'bg-error/20' }}">
                            {{ $lulus ? 'LULUS' : 'TIDAK LULUS' }}
                        </span>
                        <span class="text-sm text-on-primary/80">Nilai Minimal: 75</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Score Breakdown -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-gutter">
        @php $breakdown = [['Benar', $nilai->benar ?? 0, 'bg-secondary-container text-on-secondary-container', 'trending_up'],['Salah', $nilai->salah ?? 0, 'bg-error-container text-on-error-container', 'close'],['Tidak Dijawab', $nilai->tidak_dijawab ?? 0, 'bg-surface-container text-outline', 'help_outline']]; @endphp
        @foreach($breakdown as $stat)
        <div class="bg-white p-6 rounded-xl border border-outline-variant animate-in delay-{{ $loop->index + 2 }}">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-on-surface-variant text-sm font-label-md">{{ $stat[0] }}</p>
                    <h3 class="font-headline-md text-headline-md text-primary mt-1">{{ $stat[1] }}</h3>
                </div>
                <div class="w-12 h-12 rounded-xl {{ $stat[2] }} flex items-center justify-center">
                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">{{ $stat[3] }}</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <!-- Detail Jawaban -->
    <div class="bg-white rounded-xl border border-outline-variant overflow-hidden animate-in delay-4">
        <div class="p-6 border-b border-outline-variant flex items-center justify-between">
            <h3 class="font-headline-sm text-headline-sm">Detail Jawaban</h3>
            <span class="text-sm text-on-surface-variant">{{ $totalSoal }} soal</span>
        </div>
        <div class="divide-y divide-outline-variant">
            @forelse($detail as $idx => $d)
            <div class="p-5 hover:bg-surface-container/30 transition-colors">
                <div class="flex items-start gap-4">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm shrink-0 mt-0.5
                        {{ $d['status'] == 'benar' ? 'bg-secondary-container text-on-secondary-container' : '' }}
                        {{ $d['status'] == 'salah' ? 'bg-error-container text-on-error-container' : '' }}
                        {{ $d['status'] == 'tidak_dijawab' ? 'bg-surface-container text-outline' : '' }}">
                        {{ $idx + 1 }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-body-md font-semibold mb-2">{{ $d['soal'] }}</p>
                        @if(!empty($d['gambar']))
                        <div class="mb-3">
                            <img src="{{ $d['gambar'] }}" alt="Gambar soal" class="max-w-full max-h-40 rounded-lg border border-outline-variant">
                        </div>
                        @endif
                        <div class="space-y-1 text-sm">
                            @if($d['jawaban_user'])
                            <div class="flex items-center gap-2">
                                <span class="text-on-surface-variant">Jawaban Anda:</span>
                                <span class="font-semibold {{ $d['status'] == 'benar' ? 'text-green-700' : 'text-error' }}">{{ $d['jawaban_user'] }}</span>
                                @if($d['status'] == 'benar')
                                <span class="material-symbols-outlined text-green-600 text-[18px]">check_circle</span>
                                @else
                                <span class="material-symbols-outlined text-error text-[18px]">cancel</span>
                                @endif
                            </div>
                            @else
                            <div class="flex items-center gap-2">
                                <span class="text-on-surface-variant">Tidak dijawab</span>
                                <span class="material-symbols-outlined text-outline text-[18px]">radio_button_unchecked</span>
                            </div>
                            @endif
                            @if($d['status'] != 'benar')
                            <div class="flex items-center gap-2">
                                <span class="text-on-surface-variant">Jawaban Benar:</span>
                                <span class="font-bold text-green-700">{{ $d['jawaban_benar'] }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-8 text-center text-on-surface-variant">Detail jawaban tidak tersedia.</div>
            @endforelse
        </div>
    </div>
    <!-- Rekomendasi -->
    <div class="bg-white rounded-xl border border-outline-variant p-6 animate-in delay-4">
        <h3 class="font-headline-sm text-headline-sm mb-4">Hasil Ujian</h3>
        <p class="text-on-surface-variant mb-4">
            @if($lulus)
                Selamat! Anda lulus ujian <strong class="text-primary">{{ $ujian->nama }}</strong> dengan nilai <strong class="text-primary">{{ $nilai->skor }}</strong>.
            @else
                Nilai Anda <strong class="text-error">{{ $nilai->skor }}</strong> masih di bawah KKM (75). Silakan pelajari kembali materi dan coba lagi.
            @endif
        </p>
    </div>
    <!-- Tombol Kembali -->
    <div class="text-center">
        <a href="{{ route('portal-siswa.ujian-online') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-on-primary rounded-lg font-bold hover:bg-primary/90 transition-all active:scale-95">
            <span class="material-symbols-outlined">arrow_back</span>
            Kembali ke Ujian
        </a>
    </div>
</div>
@else
<div class="flex items-center justify-center min-h-[60vh]">
    <div class="bg-white rounded-xl border border-outline-variant p-12 text-center max-w-md">
        <span class="material-symbols-outlined text-6xl text-outline mb-4">search_off</span>
        <h3 class="font-headline-md text-headline-md text-primary mb-2">Hasil Tidak Ditemukan</h3>
        <p class="text-on-surface-variant mb-6">Data hasil ujian tidak tersedia.</p>
        <a href="{{ route('portal-siswa.ujian-online') }}" class="inline-flex px-6 py-2.5 bg-primary text-on-primary rounded-lg font-bold text-sm hover:bg-primary/90 transition-all">Kembali ke Ujian</a>
    </div>
</div>
@endif
</x-layouts.exam-result>
