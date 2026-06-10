<x-layouts.guest title="Alumni | SMA Nusantara">
<div class="max-w-max-width mx-auto px-margin-mobile md:px-margin-desktop py-12 md:py-20">
    <div class="text-center mb-12">
        <h1 class="font-headline-lg text-headline-lg text-primary mb-4">Alumni</h1>
        <p class="text-on-surface-variant max-w-2xl mx-auto">Jejak langkah para lulusan SMA Nusantara. Teruslah berkarya dan menginspirasi.</p>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-2xl border border-outline-variant card-shadow p-8 text-center">
            <div class="text-5xl font-bold text-primary mb-2">{{ $totalAlumni }}+</div>
            <p class="text-on-surface-variant">Total Alumni Terdata</p>
        </div>
        <div class="bg-white rounded-2xl border border-outline-variant card-shadow p-8 text-center">
            <div class="text-5xl font-bold text-primary mb-2">{{ $totalTracer }}+</div>
            <p class="text-on-surface-variant">Tracer Study Terisi</p>
        </div>
        <div class="bg-white rounded-2xl border border-outline-variant card-shadow p-8 text-center">
            <div class="text-5xl font-bold text-primary mb-2">{{ $totalAlumni > 0 ? round($totalTracer / max($totalAlumni, 1) * 100) : 0 }}%</div>
            <p class="text-on-surface-variant">Partisipasi Tracer Study</p>
        </div>
    </div>

    {{-- Tracer Study Terbaru --}}
    @if($tracerStudies->isNotEmpty())
    <div class="mb-8">
        <h2 class="font-headline-md text-headline-md text-primary mb-6">Cerita Alumni</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($tracerStudies as $ts)
            <div class="bg-white rounded-xl border border-outline-variant card-shadow p-6">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 rounded-full bg-primary-fixed flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary">person</span>
                    </div>
                    <div>
                        <p class="font-label-md text-label-md text-on-surface">{{ $ts->nama }}</p>
                        <p class="text-body-xs text-on-surface-variant">Lulusan {{ $ts->tahun_lulus }}</p>
                    </div>
                </div>
                @if($ts->pekerjaan)
                <div class="flex items-center gap-2 text-body-sm mb-1">
                    <span class="material-symbols-outlined text-[18px] text-outline">work</span>
                    <span>{{ $ts->pekerjaan }}</span>
                </div>
                @endif
                @if($ts->universitas)
                <div class="flex items-center gap-2 text-body-sm">
                    <span class="material-symbols-outlined text-[18px] text-outline">school</span>
                    <span>{{ $ts->universitas }}</span>
                </div>
                @endif
                @if($ts->pesan)
                <p class="text-body-sm text-on-surface-variant mt-3 italic">"{{ $ts->pesan }}"</p>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Tracer Study Form --}}
    <div x-data="{ openForm: false }" class="bg-white rounded-2xl border border-outline-variant card-shadow p-8 md:p-12">
        <div class="text-center mb-8">
            <h2 class="font-headline-md text-headline-md text-primary mb-2">Tracer Study Alumni</h2>
            <p class="text-on-surface-variant max-w-2xl mx-auto">Bagi para alumni SMA Nusantara, silakan mengisi formulir tracer study untuk membantu kami meningkatkan kualitas pendidikan.</p>
        </div>

        <button @click="openForm = !openForm" class="mx-auto flex items-center gap-2 px-8 py-3 bg-primary text-on-primary rounded-xl font-label-md hover:bg-primary/90 transition-all active:scale-95">
            <span class="material-symbols-outlined" x-text="openForm ? 'expand_less' : 'edit_note'"></span>
            <span x-text="openForm ? 'Tutup Formulir' : 'Isi Tracer Study'"></span>
        </button>

        <div x-show="openForm" x-collapse class="mt-8">
            <form method="POST" action="{{ route('alumni.tracer') }}" class="grid grid-cols-1 md:grid-cols-2 gap-5 max-w-2xl mx-auto">
                @csrf
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Nama Lengkap</label>
                    <input type="text" name="nama" required class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" placeholder="Nama lengkap Anda">
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Tahun Lulus</label>
                    <input type="number" name="tahun_lulus" min="2000" max="{{ date('Y') }}" required class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" placeholder="Contoh: 2024">
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Pekerjaan</label>
                    <input type="text" name="pekerjaan" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" placeholder="Pekerjaan saat ini (opsional)">
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Universitas / Institusi</label>
                    <input type="text" name="universitas" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" placeholder="Universitas atau institusi (opsional)">
                </div>
                <div class="space-y-2 md:col-span-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Kontak (Email/HP)</label>
                    <input type="text" name="kontak" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" placeholder="Email atau nomor HP (opsional)">
                </div>
                <div class="space-y-2 md:col-span-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Pesan & Kesan</label>
                    <textarea name="pesan" rows="4" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" placeholder="Pesan dan kesan selama di SMA Nusantara (opsional)"></textarea>
                </div>
                <div class="md:col-span-2 pt-2">
                    <button type="submit" class="w-full py-3 bg-primary text-on-primary rounded-xl font-label-md hover:bg-primary/90 transition-all text-lg">
                        Kirim Tracer Study
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</x-layouts.guest>
