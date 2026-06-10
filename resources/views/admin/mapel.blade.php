<x-layouts.admin title="Mata Pelajaran | EduPortal">
    <x-slot:styles>
    <style>
        [x-cloak] { display: none !important; }
    </style>
    </x-slot:styles>
<div x-data="{ openModal: false, editMode: false, deleteModal: false, deleteUrl: '', form: { nama: '', kode: '' }, editId: null }">
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-primary">Mata Pelajaran</h2>
        <p class="text-on-surface-variant font-body-md">Kelola data mata pelajaran</p>
    </div>
    <button @click="openModal = true; editMode = false; form = { nama: '', kode: '' }; editId = null" class="bg-primary text-on-primary px-6 py-2.5 rounded-lg flex items-center gap-2 font-label-md hover:bg-primary-container transition-all active:scale-95">
        <span class="material-symbols-outlined">add</span>
        Tambah Mapel
    </button>
</div>

<div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-surface-container-low">
                <tr>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Nama Mata Pelajaran</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Kode</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant">
                @forelse($semuaMapel as $mapel)
                <tr class="hover:bg-surface-container transition-colors">
                    <td class="px-6 py-4 font-body-md font-semibold">{{ $mapel->nama }}</td>
                    <td class="px-6 py-4">{{ $mapel->kode ?? '-' }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <button @click="openModal = true; editMode = true; form = { nama: '{{ $mapel->nama }}', kode: '{{ $mapel->kode ?? '' }}' }; editId = {{ $mapel->id }}" class="p-2 hover:bg-surface-container-low rounded-lg transition-colors">
                                <span class="material-symbols-outlined text-outline">edit</span>
                            </button>
                            <button @click="deleteUrl = '{{ route('admin.mapel.destroy', $mapel) }}'; deleteModal = true" class="p-2 hover:bg-error/10 rounded-lg transition-colors">
                                <span class="material-symbols-outlined text-error">delete</span>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td class="px-6 py-8 text-center text-on-surface-variant" colspan="3">Belum ada data mata pelajaran.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div x-show="openModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.away="openModal = false">
    <div class="fixed inset-0 bg-black/40" @click="openModal = false"></div>
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md p-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-headline-sm text-headline-sm" x-text="editMode ? 'Edit Mata Pelajaran' : 'Tambah Mata Pelajaran'"></h3>
            <button @click="openModal = false" class="p-2 hover:bg-surface-container rounded-lg">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form :action="editMode ? '{{ route('admin.mapel.update', '__ID__') }}'.replace('__ID__', editId) : '{{ route('admin.mapel.store') }}'" method="POST" class="space-y-5">
            @csrf
            <template x-if="editMode">
                <input type="hidden" name="_method" value="PUT">
            </template>
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Nama Mata Pelajaran</label>
                <input x-model="form.nama" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" placeholder="Contoh: Matematika Wajib" type="text" name="nama" required>
            </div>
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Kode</label>
                <input x-model="form.kode" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" placeholder="Contoh: MTK-W (opsional)" type="text" name="kode">
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" @click="openModal = false" class="flex-1 py-3 border border-outline-variant rounded-xl font-label-md hover:bg-surface-container transition-all">Batal</button>
                <button type="submit" class="flex-1 py-3 bg-primary text-on-primary rounded-xl font-label-md hover:bg-primary-container transition-all" x-text="editMode ? 'Simpan' : 'Tambah'"></button>
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
                <p class="text-sm text-gray-500 mt-1">Apakah Anda yakin ingin menghapus mata pelajaran ini? Tindakan ini tidak dapat dibatalkan.</p>
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
