<x-layouts.admin title="Data Siswa | EduPortal">
    <x-slot:styles>
    <style>
        [x-cloak] { display: none !important; }
    </style>
    </x-slot:styles>
<div x-data="{ selectedIds: [], selectAll: false, toggleSelect(id) { if (this.selectedIds.includes(id)) { this.selectedIds = this.selectedIds.filter(i => i !== id) } else { this.selectedIds.push(id) } }, toggleSelectAll() { this.selectAll = !this.selectAll; if (this.selectAll) { const ids = []; document.querySelectorAll('.siswa-checkbox').forEach(cb => ids.push(parseInt(cb.value))); this.selectedIds = ids; } else { this.selectedIds = []; } } }">
<div x-data="{ openModal: false, editMode: false, openDetail: false, deleteModal: false, importModal: false, deleteUrl: '', detailData: null, form: { name: '', email: '', password: '', nisn: '', nis: '', kelas_id: '', status: 'aktif' }, editId: null, showDetail(id) { fetch('{{ route('admin.siswa.show', '__ID__') }}'.replace('__ID__', id)).then(r => r.json()).then(d => { this.detailData = d; this.openDetail = true; }); } }">
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-primary">Data Siswa</h2>
        <p class="text-on-surface-variant font-body-md">Kelola data siswa SMA Nusantara</p>
    </div>
    <div class="flex gap-3">
        <button @click="importModal = true" class="px-4 py-2.5 border border-secondary text-secondary rounded-lg flex items-center gap-2 font-label-md hover:bg-secondary/10 transition-all">
            <span class="material-symbols-outlined">upload</span>
            Import
        </button>
        <a href="{{ route('admin.export.siswa') }}" class="px-4 py-2.5 border border-outline-variant rounded-lg flex items-center gap-2 font-label-md text-outline hover:bg-surface-container transition-all">
            <span class="material-symbols-outlined">download</span>
            Export
        </a>
        <button @click="openModal = true; editMode = false; form = { name: '', email: '', password: '', nisn: '', nis: '', kelas_id: '', status: 'aktif' }; editId = null" class="px-4 py-2.5 bg-primary text-on-primary rounded-lg flex items-center gap-2 font-label-md hover:bg-primary-container transition-all active:scale-95">
            <span class="material-symbols-outlined">add</span>
            Tambah Siswa
        </button>
    </div>
</div>

<div class="flex items-center gap-2 mb-4">
    <a href="{{ route('admin.data-siswa') }}" class="px-4 py-1.5 rounded-full text-sm font-label-md border transition-all {{ !request('trashed') ? 'bg-primary text-on-primary border-primary' : 'border-outline-variant hover:bg-surface-container' }}">Aktif</a>
    <a href="{{ route('admin.data-siswa', ['trashed' => 1]) }}" class="px-4 py-1.5 rounded-full text-sm font-label-md border transition-all {{ request('trashed') ? 'bg-primary text-on-primary border-primary' : 'border-outline-variant hover:bg-surface-container' }}">Sampah</a>
</div>

<!-- Filters -->
<form method="GET" action="{{ route('admin.data-siswa') }}" class="bg-white p-4 md:p-6 rounded-xl border border-outline-variant card-shadow mb-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <div>
            <label class="font-label-sm text-label-sm text-outline mb-1 block">Cari Nama/NISN</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-sm">search</span>
                <input name="search" value="{{ request('search') }}" class="w-full pl-10 pr-3 py-2 border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none" placeholder="Ketik nama siswa..." type="text">
            </div>
        </div>
        <div>
            <label class="font-label-sm text-label-sm text-outline mb-1 block">Kelas</label>
            <select name="kelas" class="w-full px-3 py-2 border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none bg-white">
                <option value="">Semua Kelas</option>
                @foreach($daftarKelas as $tingkat)
                <option value="{{ $tingkat }}" {{ request('kelas') == $tingkat ? 'selected' : '' }}>Kelas {{ $tingkat }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="font-label-sm text-label-sm text-outline mb-1 block">Jurusan</label>
            <select name="jurusan" class="w-full px-3 py-2 border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none bg-white">
                <option value="">Semua Jurusan</option>
                @foreach($daftarJurusan as $id => $nama)
                <option value="{{ $id }}" {{ request('jurusan') == $id ? 'selected' : '' }}>{{ $nama }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="font-label-sm text-label-sm text-outline mb-1 block">Status</label>
            <select name="status" class="w-full px-3 py-2 border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none bg-white">
                <option value="">Semua Status</option>
                <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="lulus" {{ request('status') == 'lulus' ? 'selected' : '' }}>Lulus</option>
                <option value="mutasi" {{ request('status') == 'mutasi' ? 'selected' : '' }}>Mutasi</option>
                <option value="alumni" {{ request('status') == 'alumni' ? 'selected' : '' }}>Alumni</option>
            </select>
        </div>
        <div class="flex items-end">
            <button type="submit" class="w-full px-3 py-2 bg-secondary-container text-on-secondary-container rounded-lg font-label-md hover:bg-secondary-container/70 transition-all">
                <span class="flex items-center justify-center gap-1">
                    <span class="material-symbols-outlined text-sm">filter_alt</span>
                    Terapkan Filter
                </span>
            </button>
        </div>
    </div>
</form>
<div x-show="selectedIds.length > 0" x-cloak class="mb-4 flex items-center gap-3 px-4 py-3 bg-surface-container rounded-xl border border-outline-variant">
    <span class="text-sm text-on-surface-variant" x-text="selectedIds.length + ' dipilih'"></span>
    <div class="flex gap-2 ml-auto">
        <form method="POST" :action="'{{ route('admin.siswa.bulk-destroy') }}'" @submit.prevent="Swal.fire({title:'Konfirmasi',text:'Hapus data yang dipilih?',icon:'warning',showCancelButton:true,confirmButtonText:'Ya, hapus!',cancelButtonText:'Batal'}).then(r=>r.isConfirmed&&$el.submit())">
            @csrf
            <input type="hidden" name="ids" :value="JSON.stringify(selectedIds)">
            <button type="submit" class="px-4 py-1.5 bg-error text-white rounded-lg text-sm font-label-md hover:bg-error/80 transition-all">Hapus</button>
        </form>
        @if(request('trashed'))
        <form method="POST" :action="'{{ route('admin.siswa.bulk-restore') }}'">
            @csrf
            <input type="hidden" name="ids" :value="JSON.stringify(selectedIds)">
            <button type="submit" class="px-4 py-1.5 bg-primary text-on-primary rounded-lg text-sm font-label-md hover:bg-primary-container transition-all">Pulihkan</button>
        </form>
        @endif
        <button @click="selectedIds = []; selectAll = false" class="px-4 py-1.5 border border-outline-variant rounded-lg text-sm font-label-md text-outline hover:bg-surface-container transition-all">Batal</button>
    </div>
</div>
<!-- Table -->
<div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-surface-container-low">
                <tr>
                    <th class="px-6 py-4 w-10"><input type="checkbox" @click="toggleSelectAll()" :checked="selectAll" class="rounded border-outline-variant"></th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">NISN</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Nama Siswa</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Kelas</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Jurusan</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Status</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant">
                @forelse($semuaSiswa as $siswa)
                <tr class="hover:bg-surface-container transition-colors">
                    <td class="px-6 py-4 w-10"><input type="checkbox" :value="{{ $siswa->id }}" x-model="selectedIds" class="siswa-checkbox rounded border-outline-variant"></td>
                    <td class="px-6 py-4 font-label-md text-on-surface-variant">{{ $siswa->nisn }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            @if($siswa->user->profile_photo_path)
                            <img src="{{ Storage::url($siswa->user->profile_photo_path) }}" alt="" class="w-8 h-8 rounded-full object-cover">
                            @else
                            <div class="w-8 h-8 bg-primary-fixed text-primary rounded-full flex items-center justify-center font-bold text-xs">{{ substr($siswa->user->name, 0, 1) }}</div>
                            @endif
                            <span class="font-body-md">{{ $siswa->user->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 font-body-md">{{ $siswa->kelas->tingkat ?? '-' }}</td>
                    <td class="px-6 py-4 font-body-md">{{ $siswa->kelas->jurusanRel?->nama ?? '-' }}</td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase {{ $siswa->status == 'aktif' ? 'bg-green-100 text-green-700' : 'bg-surface-container text-outline' }}">{{ $siswa->status }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-1">
                            <button @click="openModal = true; editMode = true; form = { name: '{{ $siswa->user->name }}', email: '{{ $siswa->user->email }}', password: '', nisn: '{{ $siswa->nisn }}', nis: '{{ $siswa->nis ?? '' }}', kelas_id: '{{ $siswa->kelas_id }}', status: '{{ $siswa->status }}' }; editId = {{ $siswa->id }}" class="p-1.5 hover:bg-surface-container rounded transition-colors" title="Edit">
                                <span class="material-symbols-outlined text-outline text-sm">edit</span>
                            </button>
                            <button @click="showDetail({{ $siswa->id }})" class="p-1.5 hover:bg-surface-container rounded transition-colors" title="Detail">
                                <span class="material-symbols-outlined text-outline text-sm">visibility</span>
                            </button>
                            <button @click="deleteUrl = '{{ route('admin.siswa.destroy', $siswa) }}'; deleteModal = true" class="p-1.5 hover:bg-surface-container rounded transition-colors" title="Hapus">
                                <span class="material-symbols-outlined text-outline text-sm">delete</span>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td class="px-6 py-4 text-on-surface-variant" colspan="8">Tidak ada data siswa.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-outline-variant flex flex-col sm:flex-row items-center justify-between gap-4">
        <p class="text-sm text-on-surface-variant">Menampilkan {{ $semuaSiswa->firstItem() ?? 0 }}-{{ $semuaSiswa->lastItem() ?? 0 }} dari {{ $semuaSiswa->total() }} data</p>
        {{ $semuaSiswa->links() }}
    </div>
</div>

<!-- Modal -->
<div x-show="openModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.away="openModal = false">
    <div class="fixed inset-0 bg-black/40" @click="openModal = false"></div>
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg p-8 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-headline-sm text-headline-sm" x-text="editMode ? 'Edit Siswa' : 'Tambah Siswa'"></h3>
            <button @click="openModal = false" class="p-2 hover:bg-surface-container rounded-lg">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form :action="editMode ? '{{ route('admin.siswa.update', '__ID__') }}'.replace('__ID__', editId) : '{{ route('admin.siswa.store') }}'" method="POST" class="space-y-5">
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
                    <input x-model="form.email" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" placeholder="email@siswa.sch.id" type="email" name="email" required>
                </div>
            </div>
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant" x-text="editMode ? 'Password (kosongkan jika tidak diubah)' : 'Password'"></label>
                <input x-model="form.password" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" placeholder="Minimal 8 karakter" type="password" name="password" :required="!editMode">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">NISN</label>
                    <input x-model="form.nisn" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" placeholder="NISN" type="text" name="nisn" required>
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">NIS</label>
                    <input x-model="form.nis" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" placeholder="NIS (opsional)" type="text" name="nis">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Kelas</label>
                    <select x-model="form.kelas_id" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" name="kelas_id">
                        <option value="">Pilih Kelas</option>
                        @foreach($semuaKelas as $kelas)
                        <option value="{{ $kelas->id }}">{{ $kelas->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Status</label>
                    <select x-model="form.status" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" name="status" required>
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                        <option value="lulus">Lulus</option>
                        <option value="mutasi">Mutasi</option>
                        <option value="alumni">Alumni</option>
                    </select>
                </div>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" @click="openModal = false" class="flex-1 py-3 border border-outline-variant rounded-xl font-label-md hover:bg-surface-container transition-all">Batal</button>
                <button type="submit" class="flex-1 py-3 bg-primary text-on-primary rounded-xl font-label-md hover:bg-primary-container transition-all" x-text="editMode ? 'Simpan' : 'Tambah'"></button>
            </div>
        </form>
    </div>
    </div>

<!-- Detail Modal -->
<div x-show="openDetail" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-black/40" @click="openDetail = false"></div>
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg p-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-headline-sm text-headline-sm">Detail Siswa</h3>
            <button @click="openDetail = false" class="p-2 hover:bg-surface-container rounded-lg">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <template x-if="detailData">
            <div class="space-y-4">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-16 h-16 bg-primary-fixed text-primary rounded-full flex items-center justify-center font-bold text-xl" x-text="(detailData.user?.name || '?').charAt(0)"></div>
                    <div>
                        <h4 class="font-headline-sm text-headline-sm" x-text="detailData.user?.name"></h4>
                        <p class="text-on-surface-variant" x-text="detailData.user?.email"></p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="text-xs text-on-surface-variant">NISN</span>
                        <p class="font-medium" x-text="detailData.nisn"></p>
                    </div>
                    <div>
                        <span class="text-xs text-on-surface-variant">NIS</span>
                        <p class="font-medium" x-text="detailData.nis || '-'"></p>
                    </div>
                    <div>
                        <span class="text-xs text-on-surface-variant">Kelas</span>
                        <p class="font-medium" x-text="detailData.kelas?.nama || '-'"></p>
                    </div>
                    <div>
                        <span class="text-xs text-on-surface-variant">Jurusan</span>
                        <p class="font-medium" x-text="detailData.kelas?.jurusan_rel?.nama || '-'"></p>
                    </div>
                    <div>
                        <span class="text-xs text-on-surface-variant">Status</span>
                        <p class="font-medium" x-text="detailData.status?.charAt(0).toUpperCase() + detailData.status?.slice(1)"></p>
                    </div>
                    <div>
                        <span class="text-xs text-on-surface-variant">Bergabung</span>
                        <p class="font-medium" x-text="detailData.created_at ? new Date(detailData.created_at).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' }) : '-'"></p>
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>
{{-- Import Modal --}}
<div x-show="importModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.away="importModal = false">
    <div class="fixed inset-0 bg-black/40" @click="importModal = false"></div>
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg p-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-headline-sm text-headline-sm">Import Data Siswa</h3>
            <button @click="importModal = false" class="p-2 hover:bg-surface-container rounded-lg">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="mb-6 p-4 bg-surface-container-low rounded-xl">
            <p class="font-label-md text-label-md text-primary mb-2">Format CSV</p>
            <p class="text-sm text-on-surface-variant mb-2">Baris pertama harus berisi header:</p>
            <code class="block bg-white p-2 rounded text-xs font-mono mb-2">nama,nisn,nis,email,kelas,status</code>
            <p class="text-sm text-on-surface-variant mb-1">Contoh:</p>
            <code class="block bg-white p-2 rounded text-xs font-mono">Budi Santoso,1234567890,2024001,budi@example.com,X IPA 1,aktif</code>
        </div>
        <form method="POST" action="{{ route('admin.import.siswa') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <div>
                <label class="font-label-md text-label-md text-on-surface-variant mb-2 block">File CSV</label>
                <input type="file" name="file" required accept=".csv,.txt"
                    class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2 text-body-md">
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" @click="importModal = false" class="flex-1 py-3 border border-outline-variant rounded-xl font-label-md hover:bg-surface-container transition-all">Batal</button>
                <button type="submit" class="flex-1 py-3 bg-primary text-on-primary rounded-xl font-label-md hover:bg-primary-container transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined">upload</span>
                    Import Data
                </button>
            </div>
        </form>
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
                <p class="text-sm text-gray-500 mt-1">Apakah Anda yakin ingin menghapus siswa ini? Tindakan ini tidak dapat dibatalkan.</p>
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
</div>
</div>
</x-layouts.admin>
