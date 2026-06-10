<x-layouts.admin title="Data Guru | EduPortal">
    <x-slot:styles>
    <style>
        [x-cloak] { display: none !important; }
    </style>
    </x-slot:styles>
<div x-data="{ openModal: false, editMode: false, deleteModal: false, deleteUrl: '', form: { name: '', email: '', password: '', nuptk: '', nip: '', mata_pelajaran: '' }, editId: null, selectedIds: [], selectAll: false, toggleSelect(id) { if (this.selectedIds.includes(id)) { this.selectedIds = this.selectedIds.filter(i => i !== id) } else { this.selectedIds.push(id) } }, toggleSelectAll() { this.selectAll = !this.selectAll; if (this.selectAll) { const ids = []; document.querySelectorAll('.guru-checkbox').forEach(cb => ids.push(parseInt(cb.value))); this.selectedIds = ids; } else { this.selectedIds = []; } } }">
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-primary">Data Guru</h2>
        <p class="text-on-surface-variant font-body-md">Kelola data guru dan staff pengajar</p>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('admin.export.siswa') }}" class="px-4 py-2.5 border border-outline-variant rounded-lg flex items-center gap-2 font-label-md text-outline hover:bg-surface-container transition-all">
            <span class="material-symbols-outlined">download</span>
            Export
        </a>
        <button @click="openModal = true; editMode = false; form = { name: '', email: '', password: '', nuptk: '', nip: '', mata_pelajaran: '' }; editId = null" class="px-4 py-2.5 bg-primary text-on-primary rounded-lg flex items-center gap-2 font-label-md hover:bg-primary-container transition-all active:scale-95">
            <span class="material-symbols-outlined">add</span>
            Tambah Guru
        </button>
    </div>
</div>

<div class="flex items-center gap-2 mb-4">
    <a href="{{ route('admin.guru') }}" class="px-4 py-1.5 rounded-full text-sm font-label-md border transition-all {{ !request('trashed') ? 'bg-primary text-on-primary border-primary' : 'border-outline-variant hover:bg-surface-container' }}">Aktif</a>
    <a href="{{ route('admin.guru', ['trashed' => 1]) }}" class="px-4 py-1.5 rounded-full text-sm font-label-md border transition-all {{ request('trashed') ? 'bg-primary text-on-primary border-primary' : 'border-outline-variant hover:bg-surface-container' }}">Sampah</a>
</div>

<!-- Search -->
<form class="mb-6" method="GET">
    <input class="w-full md:w-96 bg-white border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10" placeholder="Cari guru (nama, NUPTK)..." type="text" name="search" value="{{ request('search') }}">
</form>

<div x-show="selectedIds.length > 0" x-cloak class="mb-4 flex items-center gap-3 px-4 py-3 bg-surface-container rounded-xl border border-outline-variant">
    <span class="text-sm text-on-surface-variant" x-text="selectedIds.length + ' dipilih'"></span>
    <div class="flex gap-2 ml-auto">
        <form method="POST" :action="'{{ route('admin.guru.bulk-destroy') }}'" @submit.prevent="Swal.fire({title:'Konfirmasi',text:'Hapus data yang dipilih?',icon:'warning',showCancelButton:true,confirmButtonText:'Ya, hapus!',cancelButtonText:'Batal'}).then(r=>r.isConfirmed&&$el.submit())">
            @csrf
            <input type="hidden" name="ids" :value="JSON.stringify(selectedIds)">
            <button type="submit" class="px-4 py-1.5 bg-error text-white rounded-lg text-sm font-label-md hover:bg-error/80 transition-all">Hapus</button>
        </form>
        @if(request('trashed'))
        <form method="POST" :action="'{{ route('admin.guru.bulk-restore') }}'">
            @csrf
            <input type="hidden" name="ids" :value="JSON.stringify(selectedIds)">
            <button type="submit" class="px-4 py-1.5 bg-primary text-on-primary rounded-lg text-sm font-label-md hover:bg-primary-container transition-all">Pulihkan</button>
        </form>
        @endif
        <button @click="selectedIds = []; selectAll = false" class="px-4 py-1.5 border border-outline-variant rounded-lg text-sm font-label-md text-outline hover:bg-surface-container transition-all">Batal</button>
    </div>
</div>

<div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-surface-container-low">
                <tr>
                    <th class="px-6 py-4 w-10"><input type="checkbox" @click="toggleSelectAll()" :checked="selectAll" class="rounded border-outline-variant"></th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Nama</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">NUPTK</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">NIP</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Mata Pelajaran</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Email</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant">
                @forelse($semuaGuru as $guru)
                <tr class="hover:bg-surface-container transition-colors">
                    <td class="px-6 py-4 w-10"><input type="checkbox" :value="{{ $guru->id }}" x-model="selectedIds" class="guru-checkbox rounded border-outline-variant"></td>
                    <td class="px-6 py-4 flex items-center gap-3">
                        @if($guru->user->profile_photo_path)
                        <img src="{{ Storage::url($guru->user->profile_photo_path) }}" alt="" class="w-8 h-8 rounded-full object-cover">
                        @else
                        <div class="w-8 h-8 bg-primary-fixed text-primary rounded-full flex items-center justify-center font-bold text-xs">{{ substr($guru->user->name, 0, 1) }}</div>
                        @endif
                        <span class="font-body-md font-semibold">{{ $guru->user->name }}</span>
                    </td>
                    <td class="px-6 py-4 font-body-md">{{ $guru->nuptk }}</td>
                    <td class="px-6 py-4 font-body-md">{{ $guru->nip ?? '-' }}</td>
                    <td class="px-6 py-4"><span class="px-3 py-1 bg-secondary-fixed text-secondary rounded-full text-xs font-bold">{{ $guru->mata_pelajaran }}</span></td>
                    <td class="px-6 py-4 text-on-surface-variant">{{ $guru->user->email }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <button @click="openModal = true; editMode = true; form = { name: '{{ $guru->user->name }}', email: '{{ $guru->user->email }}', password: '', nuptk: '{{ $guru->nuptk }}', nip: '{{ $guru->nip ?? '' }}', mata_pelajaran: '{{ $guru->mata_pelajaran }}' }; editId = {{ $guru->id }}" class="p-2 hover:bg-surface-container-low rounded-lg transition-colors">
                                <span class="material-symbols-outlined text-outline">edit</span>
                            </button>
                            <button @click="deleteUrl = '{{ route('admin.guru.destroy', $guru) }}'; deleteModal = true" class="p-2 hover:bg-error/10 rounded-lg transition-colors">
                                <span class="material-symbols-outlined text-error">delete</span>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td class="px-6 py-8 text-center text-on-surface-variant" colspan="7">Belum ada data guru.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($semuaGuru->hasPages())
    <div class="p-6 border-t border-outline-variant">
        {{ $semuaGuru->links() }}
    </div>
    @endif
</div>

<!-- Modal -->
<div x-show="openModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.away="openModal = false">
    <div class="fixed inset-0 bg-black/40" @click="openModal = false"></div>
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg p-8 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-headline-sm text-headline-sm" x-text="editMode ? 'Edit Guru' : 'Tambah Guru'"></h3>
            <button @click="openModal = false" class="p-2 hover:bg-surface-container rounded-lg">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form :action="editMode ? '{{ route('admin.guru.update', '__ID__') }}'.replace('__ID__', editId) : '{{ route('admin.guru.store') }}'" method="POST" class="space-y-5">
            @csrf
            <template x-if="editMode">
                <input type="hidden" name="_method" value="PUT">
            </template>
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Nama Lengkap</label>
                    <input x-model="form.name" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" placeholder="Nama" type="text" name="name" required>
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Email</label>
                    <input x-model="form.email" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" placeholder="email@sekolah.sch.id" type="email" name="email" required>
                </div>
            </div>
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant" x-text="editMode ? 'Password (kosongkan jika tidak diubah)' : 'Password'"></label>
                <input x-model="form.password" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" placeholder="Minimal 8 karakter" type="password" name="password" :required="!editMode">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">NUPTK</label>
                    <input x-model="form.nuptk" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" placeholder="NUPTK" type="text" name="nuptk" required>
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">NIP</label>
                    <input x-model="form.nip" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" placeholder="NIP (opsional)" type="text" name="nip">
                </div>
            </div>
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Mata Pelajaran</label>
                <select x-model="form.mata_pelajaran" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" name="mata_pelajaran" required>
                    <option value="">Pilih Mata Pelajaran</option>
                    @foreach($daftarMapel as $mapel)
                    <option value="{{ $mapel->nama }}">{{ $mapel->nama }}</option>
                    @endforeach
                </select>
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
                <p class="text-sm text-gray-500 mt-1">Apakah Anda yakin ingin menghapus guru ini? Tindakan ini tidak dapat dibatalkan.</p>
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
