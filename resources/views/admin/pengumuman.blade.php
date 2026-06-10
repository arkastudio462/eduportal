<x-layouts.admin title="Pengumuman | EduPortal">
    <x-slot:styles>
    <style>
        [x-cloak] { display: none !important; }
    </style>
    </x-slot:styles>
<div x-data="{ openModal: false, editMode: false, deleteModal: false, deleteUrl: '', form: { judul: '', konten: '', tipe: 'umum' }, editId: null }">
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-primary">Pengumuman</h2>
        <p class="text-on-surface-variant font-body-md">Kelola pengumuman dan informasi sekolah</p>
    </div>
    <button @click="openModal = true; editMode = false; form = { judul: '', konten: '', tipe: 'umum' }; editId = null" class="px-4 py-2.5 bg-primary text-on-primary rounded-lg flex items-center gap-2 font-label-md hover:bg-primary-container transition-all active:scale-95">
        <span class="material-symbols-outlined">add</span>
        Buat Pengumuman
    </button>
</div>

<div class="space-y-gutter">
    @forelse($semuaPengumuman as $pengumuman)
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-6">
        <div class="flex items-start justify-between gap-4">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-2">
                    <span class="px-3 py-0.5 rounded-full text-xs font-bold bg-secondary-fixed text-secondary uppercase">{{ $pengumuman->tipe }}</span>
                    <span class="text-xs text-outline">{{ $pengumuman->tanggal->isoFormat('dddd, D MMMM YYYY HH:mm') }}</span>
                </div>
                <h3 class="font-headline-sm text-headline-sm mb-2">{{ $pengumuman->judul }}</h3>
                <p class="text-on-surface-variant">{{ $pengumuman->konten }}</p>
            </div>
            <div class="flex items-center gap-1 shrink-0">
                <button @click="openModal = true; editMode = true; form = { judul: '{{ $pengumuman->judul }}', konten: '{{ $pengumuman->konten }}', tipe: '{{ $pengumuman->tipe }}' }; editId = {{ $pengumuman->id }}" class="p-2 hover:bg-surface-container-low rounded-lg">
                    <span class="material-symbols-outlined text-outline">edit</span>
                </button>
                <button @click="deleteUrl = '{{ route('admin.pengumuman.destroy', $pengumuman) }}'; deleteModal = true" class="p-2 hover:bg-error/10 rounded-lg">
                    <span class="material-symbols-outlined text-error">delete</span>
                </button>
            </div>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-8 text-center">
        <span class="material-symbols-outlined text-5xl text-outline mb-4">campaign</span>
        <p class="text-on-surface-variant">Belum ada pengumuman.</p>
    </div>
    @endforelse
</div>

<!-- Modal -->
<div x-show="openModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.away="openModal = false">
    <div class="fixed inset-0 bg-black/40" @click="openModal = false"></div>
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg p-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-headline-sm text-headline-sm" x-text="editMode ? 'Edit Pengumuman' : 'Buat Pengumuman'"></h3>
            <button @click="openModal = false" class="p-2 hover:bg-surface-container rounded-lg"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form :action="editMode ? '{{ route('admin.pengumuman.update', '__ID__') }}'.replace('__ID__', editId) : '{{ route('admin.pengumuman.store') }}'" method="POST" class="space-y-5">
            @csrf
            <template x-if="editMode"><input type="hidden" name="_method" value="PUT"></template>
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Judul</label>
                <input x-model="form.judul" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" placeholder="Judul pengumuman" type="text" name="judul" required>
            </div>
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Konten</label>
                <textarea x-model="form.konten" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" placeholder="Isi pengumuman..." rows="5" name="konten" required></textarea>
            </div>
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Tipe</label>
                <select x-model="form.tipe" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" name="tipe" required>
                    <option value="umum">Umum</option>
                    <option value="akademik">Akademik</option>
                    <option value="kegiatan">Kegiatan</option>
                </select>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" @click="openModal = false" class="flex-1 py-3 border border-outline-variant rounded-xl font-label-md hover:bg-surface-container">Batal</button>
                <button type="submit" class="flex-1 py-3 bg-primary text-on-primary rounded-xl font-label-md hover:bg-primary-container" x-text="editMode ? 'Simpan' : 'Buat'"></button>
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
                <p class="text-sm text-gray-500 mt-1">Apakah Anda yakin ingin menghapus pengumuman ini? Tindakan ini tidak dapat dibatalkan.</p>
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
</x-layouts.admin>
