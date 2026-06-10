<x-layouts.exam title="Ujian Sedang Berlangsung">

<x-slot:styles>
<style>
    .option-card:hover { border-color: #FEAF2C; background-color: rgba(254, 174, 44, 0.05); }
    .option-card.selected { border-color: #FEAF2C; background-color: #FEF3E0; }
    .nav-dot { width: 32px; height: 32px; border-radius: 50%; font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.2s; }
    [x-cloak] { display: none !important; }
</style>
</x-slot:styles>

@if($totalSoal > 0)
<div x-data="examApp({{ $totalSoal }}, {{ $ujian->durasi ?? 90 }}, '{{ $ujian->id }}')" class="flex flex-1 overflow-hidden">
    <!-- Sidebar Soal -->
    <aside id="soal-sidebar" class="hidden lg:flex flex-col w-72 bg-white border-r border-outline-variant shrink-0 fixed lg:static inset-y-0 left-0 z-40 pt-14 shadow-xl lg:shadow-none">
        <div class="p-4 border-b border-outline-variant flex items-center justify-between">
            <h3 class="font-headline-sm text-headline-sm text-primary">Navigasi Soal</h3>
            <button class="lg:hidden p-1 hover:bg-surface-container rounded transition-colors" onclick="document.getElementById('soal-sidebar')?.classList.add('hidden')">
                <span class="material-symbols-outlined text-outline">close</span>
            </button>
        </div>
        <div class="flex-1 overflow-y-auto p-4">
            <div class="grid grid-cols-5 gap-2" id="soal-nav">
                <template x-for="(s, idx) in soalList" :key="idx">
                    <button @click="currentSoal = idx"
                        class="nav-dot flex items-center justify-center transition-all"
                        :class="{
                            'bg-secondary-container text-on-secondary-container border-secondary-container': jawaban[s.id],
                            'border-2 border-primary ring-2 ring-primary': currentSoal === idx,
                            'border border-outline-variant text-on-surface-variant hover:border-primary hover:text-primary': !jawaban[s.id] && currentSoal !== idx
                        }"
                        x-text="idx + 1">
                    </button>
                </template>
            </div>
            <div class="mt-6">
                <p class="text-xs font-semibold text-outline mb-3 uppercase">Keterangan</p>
                <div class="space-y-2">
                    <div class="flex items-center gap-2"><div class="w-4 h-4 rounded bg-secondary-container border border-secondary-container"></div><span class="text-xs">Terjawab</span></div>
                    <div class="flex items-center gap-2"><div class="w-4 h-4 rounded border border-outline-variant bg-white"></div><span class="text-xs">Belum Dijawab</span></div>
                    <div class="flex items-center gap-2"><div class="w-4 h-4 rounded border-2 border-primary bg-white"></div><span class="text-xs">Sedang Aktif</span></div>
                </div>
            </div>
        </div>
        <div class="p-4 border-t border-outline-variant">
            <form method="POST" action="{{ route('ujian.submit', $ujian->slug) }}" id="form-submit" @submit.prevent="Swal.fire({title:'Konfirmasi',text:'Yakin ingin mengumpulkan jawaban?',icon:'warning',showCancelButton:true,confirmButtonText:'Ya, kumpulkan!',cancelButtonText:'Batal'}).then(r=>r.isConfirmed&&$el.submit())">
                @csrf
                <template x-for="(jawab, soalId) in jawaban" :key="soalId">
                    <input type="hidden" :name="'jawaban[' + soalId + ']'" :value="jawab">
                </template>
                <button type="submit" class="w-full px-4 py-2.5 bg-primary text-on-primary rounded-lg font-bold text-sm hover:bg-primary/90 transition-all active:scale-95">
                    Kumpulkan Jawaban
                </button>
            </form>
            <p class="text-xs text-on-surface-variant text-center mt-2" x-text="totalSoal + ' soal'"></p>
        </div>
    </aside>
    <!-- Area Soal -->
    <main class="flex-1 overflow-y-auto bg-surface-container-low">
        <div class="p-4 md:p-8">
            <!-- Header Timer -->
            <div x-cloak class="bg-white rounded-xl border border-outline-variant p-4 mb-6 flex items-center justify-between">
                <span class="px-3 py-1 bg-primary-fixed text-primary rounded-full text-xs font-bold">{{ $ujian->nama ?? 'Ujian' }} • <span x-text="totalSoal"></span> soal</span>
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined" :class="sisaWaktu < 300 ? 'text-error animate-pulse' : 'text-amber-500'">timer</span>
                    <span class="font-bold text-sm" :class="sisaWaktu < 300 ? 'text-error' : 'text-on-surface'" x-text="formatWaktu(sisaWaktu)"></span>
                </div>
            </div>

            <!-- Soal -->
            <template x-for="(s, idx) in soalList" :key="s.id">
                <div x-show="currentSoal === idx" x-cloak class="bg-white rounded-xl border border-outline-variant p-6 md:p-8 mb-6">
                    <div class="flex items-center justify-between mb-6">
                        <span class="px-3 py-1 bg-primary-fixed text-primary rounded-full text-xs font-bold">Soal <span x-text="idx + 1"></span></span>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-amber-500" style="font-variation-settings: 'FILL' 1;">flag</span>
                            <span class="text-sm text-on-surface-variant">Soal <span x-text="idx + 1"></span> dari <span x-text="totalSoal"></span></span>
                        </div>
                    </div>
                    <div class="mb-8">
                        <h3 class="font-headline-sm text-headline-sm mb-4 leading-relaxed" x-text="s.konten"></h3>
                        <template x-if="s.gambar">
                            <div class="mb-4">
                                <img :src="s.gambar" alt="Gambar soal" class="max-w-full max-h-80 rounded-lg border border-outline-variant">
                            </div>
                        </template>
                    </div>
                    <template x-if="s.tipe === 'Essay'">
                        <div>
                            <textarea class="w-full px-4 py-3 border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none resize-none"
                                rows="4" placeholder="Tulis jawaban Anda..."
                                @input="jawaban[s.id] = $event.target.value"
                                x-text="jawaban[s.id] || ''"></textarea>
                        </div>
                    </template>
                    <template x-if="s.tipe !== 'Essay'">
                        <div class="space-y-4">
                            <template x-for="(opt, idx) in (s.opsi_array || [])" :key="idx">
                                <label class="option-card flex items-center gap-4 p-4 border border-outline-variant rounded-xl cursor-pointer transition-all"
                                    :class="{ 'selected': jawaban[s.id] === opt.value }">
                                    <input type="radio" class="w-4 h-4 accent-secondary-container"
                                        :name="'jawaban_' + s.id"
                                        :value="opt.value"
                                        :checked="jawaban[s.id] === opt.value"
                                        @change="jawaban[s.id] = opt.value; jawabCount = Object.keys(jawaban).length;">
                                    <span class="font-body-md" x-text="(opt.label || String.fromCharCode(65 + idx)) + '. ' + opt.value"></span>
                                </label>
                            </template>
                        </div>
                    </template>
                </div>
            </template>

            <!-- Navigation Buttons -->
            <div class="flex items-center justify-between">
                <button @click="prevSoal" x-show="currentSoal > 0" class="px-6 py-3 border border-outline-variant rounded-xl text-outline font-bold hover:bg-surface-container transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined">chevron_left</span>
                    Sebelumnya
                </button>
                <div x-show="currentSoal === 0"></div>
                <button @click="nextSoal" x-show="currentSoal < totalSoal - 1" class="px-6 py-3 bg-primary text-on-primary rounded-xl font-bold hover:bg-primary/90 transition-all flex items-center gap-2 active:scale-95">
                    Selanjutnya
                    <span class="material-symbols-outlined">chevron_right</span>
                </button>
                <button x-show="currentSoal === totalSoal - 1" @click="document.getElementById('form-submit').dispatchEvent(new Event('submit'))" class="px-6 py-3 bg-secondary-container text-on-secondary-container rounded-xl font-bold hover:opacity-90 transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined">check</span>
                    Kumpulkan
                </button>
            </div>
        </div>
    </main>
</div>
@else
<div class="flex-1 flex items-center justify-center p-8">
    <div class="bg-white rounded-xl border border-outline-variant p-12 text-center max-w-md">
        <span class="material-symbols-outlined text-6xl text-outline mb-4">quiz</span>
        <h3 class="font-headline-md text-headline-md text-primary mb-2">Tidak Ada Soal</h3>
        <p class="text-on-surface-variant mb-6">Ujian ini belum memiliki soal. Silakan hubungi guru Anda.</p>
        <a href="{{ url()->previous() }}" class="inline-flex px-6 py-2.5 bg-primary text-on-primary rounded-lg font-bold text-sm hover:bg-primary/90 transition-all">Kembali</a>
    </div>
</div>
@endif

<x-slot:scripts>
<script>
function examApp(totalSoal, durasiMenit, ujianId) {
    return {
        currentSoal: 0,
        totalSoal: totalSoal,
        jawaban: {},
        jawabCount: 0,
        sisaWaktu: durasiMenit * 60,
        timerInterval: null,
        soalList: @json($soal->values()),

        init() {
            this.timerInterval = setInterval(() => {
                if (this.sisaWaktu > 0) {
                    this.sisaWaktu--;
                } else {
                    clearInterval(this.timerInterval);
                    document.getElementById('form-submit').submit();
                }
            }, 1000);
        },

        formatWaktu(detik) {
            const jam = Math.floor(detik / 3600);
            const menit = Math.floor((detik % 3600) / 60);
            const d = detik % 60;
            return (jam > 0 ? String(jam).padStart(2, '0') + ':' : '') +
                String(menit).padStart(2, '0') + ':' +
                String(d).padStart(2, '0');
        },

        nextSoal() {
            if (this.currentSoal < this.totalSoal - 1) this.currentSoal++;
        },

        prevSoal() {
            if (this.currentSoal > 0) this.currentSoal--;
        },
    };
}
</script>
</x-slot:scripts>
</x-layouts.exam>
