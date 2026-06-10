<x-layouts.portal-siswa title="Tugas - Portal Siswa">
<x-slot:styles>
<style>[x-cloak] { display: none !important; }</style>
</x-slot:styles>
<div x-data="{ openModal: false, tugasId: null, tugasJudul: '', catatan: '' }">
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-primary">Tugas</h2>
        <p class="text-on-surface-variant font-body-md">Kelola dan kumpulkan tugas Anda</p>
    </div>
</div>

<div class="space-y-gutter">
    @forelse($semuaTugas as $tugas)
    @php $sub = $submissions->get($tugas->id); @endphp
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-6">
        <div class="flex items-start justify-between gap-4">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-2">
                    <span class="px-3 py-0.5 rounded-full text-xs font-bold bg-secondary-fixed text-secondary">{{ $tugas->mapel->nama ?? '-' }}</span>
                    @if($tugas->deadline)
                    <span class="text-xs text-outline">
                        Deadline: {{ $tugas->deadline->isoFormat('D MMMM YYYY HH:mm') }}
                        @if($tugas->deadline->isPast() && !$sub)
                        <span class="text-error font-bold ml-1">(Terlewat)</span>
                        @endif
                    </span>
                    @endif
                </div>
                <h3 class="font-headline-sm text-headline-sm">{{ $tugas->judul }}</h3>
                @if($tugas->deskripsi)
                <p class="text-on-surface-variant mt-1">{{ $tugas->deskripsi }}</p>
                @endif
                <div class="flex items-center gap-4 mt-3">
                    <span class="text-xs text-outline">Guru: {{ $tugas->guru->user->name ?? '-' }}</span>
                    @if($sub)
                    <span class="text-xs text-green-600 font-semibold">&#10003; Sudah dikumpulkan</span>
                    @if($sub->file)
                        <a href="{{ Storage::url($sub->file) }}" target="_blank" class="text-xs text-primary underline">Lihat File</a>
                    @endif
                    @if($sub->nilai !== null)
                    <span class="text-xs font-bold {{ $sub->nilai >= 75 ? 'text-green-600' : 'text-error' }}">Nilai: {{ $sub->nilai }}</span>
                    @endif
                    @else
                    <span class="text-xs text-orange-600 font-semibold">Belum dikumpulkan</span>
                    @endif
                </div>
            </div>
            <div class="shrink-0">
                @if(!$sub || !$tugas->deadline || !$tugas->deadline->isPast())
                <button @click="openModal = true; tugasId = {{ $tugas->id }}; tugasJudul = '{{ $tugas->judul }}'; catatan = '{{ $sub->catatan ?? '' }}'"
                    class="px-4 py-2 {{ $sub ? 'bg-secondary-container text-on-secondary-container' : 'bg-primary text-on-primary' }} rounded-lg text-sm font-bold hover:opacity-90 transition-all">
                    {{ $sub ? 'Edit Kumpulan' : 'Kumpulkan' }}
                </button>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-8 text-center">
        <span class="material-symbols-outlined text-5xl text-outline mb-4">assignment</span>
        <h3 class="font-headline-sm text-headline-sm text-on-surface mb-2">Tidak Ada Tugas</h3>
        <p class="text-on-surface-variant">Belum ada tugas untuk kelas Anda.</p>
    </div>
    @endforelse
</div>

<!-- Modal -->
<div x-show="openModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.away="openModal = false">
    <div class="fixed inset-0 bg-black/40" @click="openModal = false"></div>
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg p-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-headline-sm text-headline-sm" x-text="'Kumpulkan Tugas: ' + tugasJudul"></h3>
            <button @click="openModal = false" class="p-2 hover:bg-surface-container rounded-lg"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form action="{{ route('portal-siswa.tugas.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <input type="hidden" name="tugas_id" x-model="tugasId">
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Catatan</label>
                <textarea x-model="catatan" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" placeholder="Tambahkan catatan (opsional)" rows="4" name="catatan"></textarea>
            </div>
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Upload File (Maks 10MB)</label>
                <input type="file" name="file" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2 text-sm">
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" @click="openModal = false" class="flex-1 py-3 border border-outline-variant rounded-xl font-label-md hover:bg-surface-container">Batal</button>
                <button type="submit" class="flex-1 py-3 bg-primary text-on-primary rounded-xl font-label-md hover:bg-primary-container">Kumpulkan</button>
            </div>
        </form>
    </div>
    </div>
</div>
</x-layouts.portal-siswa>
