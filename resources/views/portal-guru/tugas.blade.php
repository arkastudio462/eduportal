<x-layouts.portal-guru title="Tugas - Portal Guru">
<x-slot:styles>
<style>[x-cloak] { display: none !important; }</style>
</x-slot:styles>
<div x-data="{ openModal: false, deleteModal: false, deleteUrl: '', form: { judul: '', deskripsi: '', mapel_id: '', kelas_id: '', deadline: '' } }">
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-primary">Tugas</h2>
        <p class="text-on-surface-variant font-body-md">Buat dan kelola tugas siswa</p>
    </div>
    <button @click="openModal = true; form = { judul: '', deskripsi: '', mapel_id: '{{ $mapelList->first()->id ?? '' }}', kelas_id: '{{ $kelasList->first()->id ?? '' }}', deadline: '' }" class="px-4 py-2.5 bg-primary text-on-primary rounded-lg flex items-center gap-2 font-label-md hover:bg-primary-container transition-all active:scale-95">
        <span class="material-symbols-outlined">add</span>
        Buat Tugas
    </button>
</div>

<div class="space-y-gutter">
    @forelse($semuaTugas as $tugas)
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-6">
        <div class="flex items-start justify-between gap-4">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-2">
                    <span class="px-3 py-0.5 rounded-full text-xs font-bold bg-secondary-fixed text-secondary">{{ $tugas->mapel->nama ?? '-' }}</span>
                    <span class="px-3 py-0.5 rounded-full text-xs font-bold bg-primary-fixed text-primary">{{ $tugas->kelas->nama ?? '-' }}</span>
                    @if($tugas->deadline)
                    <span class="text-xs text-outline">
                        Deadline: {{ $tugas->deadline->isoFormat('D MMMM YYYY HH:mm') }}
                    </span>
                    @endif
                </div>
                <h3 class="font-headline-sm text-headline-sm">{{ $tugas->judul }}</h3>
                @if($tugas->deskripsi)
                <p class="text-on-surface-variant mt-1">{{ Str::limit($tugas->deskripsi, 150) }}</p>
                @endif
                <div class="flex items-center gap-4 mt-3">
                    <span class="text-sm text-outline">
                        <span class="font-bold text-on-surface">{{ $tugas->submissions->count() }}</span> pengumpulan
                    </span>
                    <span class="text-sm text-outline">Dibuat {{ $tugas->created_at->diffForHumans() }}</span>
                </div>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('portal-guru.tugas.submissions', $tugas) }}" class="px-4 py-2 bg-secondary-container text-on-secondary-container rounded-lg text-sm font-bold hover:opacity-90 transition-all">
                    Lihat Pengumpulan
                </a>
                <button @click="deleteUrl = '{{ route('portal-guru.tugas.destroy', $tugas) }}'; deleteModal = true" class="p-2 hover:bg-error/10 rounded-lg">
                    <span class="material-symbols-outlined text-error">delete</span>
                </button>
            </div>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-8 text-center">
        <span class="material-symbols-outlined text-5xl text-outline mb-4">assignment</span>
        <h3 class="font-headline-sm text-headline-sm text-on-surface mb-2">Belum Ada Tugas</h3>
        <p class="text-on-surface-variant">Buat tugas pertama Anda untuk siswa.</p>
    </div>
    @endforelse
</div>

<!-- Modal -->
<div x-show="openModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.away="openModal = false">
    <div class="fixed inset-0 bg-black/40" @click="openModal = false"></div>
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg p-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-headline-sm text-headline-sm">Buat Tugas Baru</h3>
            <button @click="openModal = false" class="p-2 hover:bg-surface-container rounded-lg"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form action="{{ route('portal-guru.tugas.store') }}" method="POST" class="space-y-5">
            @csrf
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Judul Tugas</label>
                <input x-model="form.judul" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" placeholder="Judul tugas" type="text" name="judul" required>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Mata Pelajaran</label>
                    <select x-model="form.mapel_id" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" name="mapel_id" required>
                        @foreach($mapelList as $mapel)
                        <option value="{{ $mapel->id }}">{{ $mapel->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Kelas</label>
                    <select x-model="form.kelas_id" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" name="kelas_id" required>
                        @foreach($kelasList as $kelas)
                        <option value="{{ $kelas->id }}">{{ $kelas->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Deskripsi</label>
                <textarea x-model="form.deskripsi" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" placeholder="Deskripsi tugas (opsional)" rows="4" name="deskripsi"></textarea>
            </div>
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Deadline</label>
                <input x-model="form.deadline" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" type="datetime-local" name="deadline">
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" @click="openModal = false" class="flex-1 py-3 border border-outline-variant rounded-xl font-label-md hover:bg-surface-container">Batal</button>
                <button type="submit" class="flex-1 py-3 bg-primary text-on-primary rounded-xl font-label-md hover:bg-primary-container">Buat</button>
            </div>
        </form>
    </div>
    </div>
</div>

<!-- Delete Modal -->
<div x-show="deleteModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.away="deleteModal = false">
    <div class="fixed inset-0 bg-black/40" @click="deleteModal = false"></div>
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
        <div class="flex items-center gap-4 mb-4">
            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                <span class="material-symbols-outlined text-red-600 text-2xl">delete_forever</span>
            </div>
            <div>
                <h3 class="font-headline-sm text-headline-sm">Konfirmasi Hapus</h3>
                <p class="text-sm text-gray-500 mt-1">Apakah Anda yakin ingin menghapus tugas ini? Tindakan ini tidak dapat dibatalkan.</p>
            </div>
        </div>
        <div class="flex justify-end gap-3 mt-6">
            <button @click="deleteModal = false" type="button" class="px-4 py-2 border border-gray-300 rounded-lg font-label-md hover:bg-gray-50 transition-all">Batal</button>
            <form method="POST" :action="deleteUrl" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg font-label-md hover:bg-red-700 transition-all">Hapus</button>
            </form>
        </div>
    </div>
</div>
</x-layouts.portal-guru>
