<x-layouts.admin title="Pelanggaran Siswa | EduPortal">
<x-slot:styles>
<style>[x-cloak] { display: none !important; }</style>
</x-slot:styles>
<div x-data="{
    tab: 'catatan',
    openCreate: false,
    openEdit: false,
    openKategoriModal: false,
    kategoriEditMode: false,
    editId: null,
    kategoriEditId: null,
    siswaList: [],
    form: {
        kelas_id: '',
        siswa_id: '',
        kategori_id: '',
        tanggal: '{{ date('Y-m-d') }}',
        pelanggaran: '',
        poin: '',
        sanksi: '',
        keterangan: ''
    },
    kategoriForm: { nama: '', poin: '', sanksi: '' },
    kategoriData: @json($kategoriList->keyBy('id')->map(fn($k) => ['nama' => $k->nama, 'poin' => $k->poin, 'sanksi' => $k->sanksi])),
    fetchSiswa() {
        if (!this.form.kelas_id) { this.siswaList = []; return; }
        fetch('{{ route('admin.pelanggaran-siswa.get-siswa') }}?kelas_id=' + this.form.kelas_id)
            .then(r => r.json()).then(d => { this.siswaList = d; this.form.siswa_id = ''; });
    },
    fillFromKategori() {
        const k = this.kategoriData[this.form.kategori_id];
        if (k) {
            this.form.pelanggaran = k.nama;
            this.form.poin = k.poin;
            this.form.sanksi = k.sanksi;
        } else {
            this.form.pelanggaran = '';
            this.form.poin = '';
            this.form.sanksi = '';
        }
    },
    resetForm() {
        this.form = { kelas_id: '', siswa_id: '', kategori_id: '', tanggal: '{{ date('Y-m-d') }}', pelanggaran: '', poin: '', sanksi: '', keterangan: '' };
        this.siswaList = [];
    }
}">
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-primary">Pelanggaran Siswa</h2>
        <p class="text-on-surface-variant font-body-md">Kelola catatan pelanggaran dan kategori pelanggaran siswa</p>
    </div>
    <div class="flex gap-3">
        <template x-if="tab === 'catatan'">
            <button @click="openCreate = true; resetForm()" class="bg-primary text-on-primary px-6 py-2.5 rounded-lg flex items-center gap-2 font-label-md hover:bg-primary-container transition-all active:scale-95">
                <span class="material-symbols-outlined">add</span>
                Tambah Catatan
            </button>
        </template>
        <template x-if="tab === 'kategori'">
            <button @click="openKategoriModal = true; kategoriEditMode = false; kategoriForm = { nama: '', poin: '', sanksi: '' }; kategoriEditId = null" class="bg-secondary text-on-secondary px-6 py-2.5 rounded-lg flex items-center gap-2 font-label-md hover:bg-secondary/90 transition-all active:scale-95">
                <span class="material-symbols-outlined">add</span>
                Tambah Kategori
            </button>
        </template>
    </div>
</div>

<!-- Tabs -->
<div class="flex gap-1 mb-6 bg-surface-container-low rounded-xl p-1 w-fit">
    <button @click="tab = 'catatan'" :class="tab === 'catatan' ? 'bg-white shadow-sm text-primary' : 'text-on-surface-variant hover:text-on-surface'" class="px-5 py-2 rounded-lg font-label-md transition-all">Catatan Pelanggaran</button>
    <button @click="tab = 'kategori'" :class="tab === 'kategori' ? 'bg-white shadow-sm text-primary' : 'text-on-surface-variant hover:text-on-surface'" class="px-5 py-2 rounded-lg font-label-md transition-all">Kategori Pelanggaran</button>
</div>

<!-- Tab 1: Catatan Pelanggaran -->
<div x-show="tab === 'catatan'">
    <!-- Stats -->
    @php
        $totalPoin = $semuaPelanggaran->sum('poin');
        $siswaTerlibat = $semuaPelanggaran->pluck('siswa_id')->unique()->count();
    @endphp
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-outline-variant card-shadow p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-red-100 text-red-600 flex items-center justify-center">
                    <span class="material-symbols-outlined">gavel</span>
                </div>
                <div>
                    <div class="text-xs text-on-surface-variant">Total Pelanggaran</div>
                    <div class="text-2xl font-bold mt-1">{{ $semuaPelanggaran->total() }}</div>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-outline-variant card-shadow p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-orange-100 text-orange-700 flex items-center justify-center">
                    <span class="material-symbols-outlined">exclamation</span>
                </div>
                <div>
                    <div class="text-xs text-on-surface-variant">Total Poin</div>
                    <div class="text-2xl font-bold mt-1">{{ number_format($totalPoin, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-outline-variant card-shadow p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center">
                    <span class="material-symbols-outlined">people</span>
                </div>
                <div>
                    <div class="text-xs text-on-surface-variant">Siswa Terlibat</div>
                    <div class="text-2xl font-bold mt-1">{{ $siswaTerlibat }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <form method="GET" action="{{ route('admin.pelanggaran-siswa') }}" class="bg-white p-5 rounded-xl border border-outline-variant card-shadow mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            <div>
                <label class="font-label-sm text-label-sm text-outline block mb-1">Kelas</label>
                <select name="kelas_id" class="w-full px-3 py-2 border border-outline-variant rounded-lg text-sm bg-white">
                    <option value="">Semua Kelas</option>
                    @foreach($kelasList as $k)
                    <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full px-3 py-2 bg-secondary-container text-on-secondary-container rounded-lg font-label-md hover:bg-secondary-container/70 transition-all">Filter</button>
            </div>
        </div>
    </form>

    <!-- Table -->
    <div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-surface-container-low">
                    <tr>
                        <th class="px-4 py-4 font-label-md text-on-surface-variant w-12">No</th>
                        <th class="px-4 py-4 font-label-md text-on-surface-variant">Nama Siswa</th>
                        <th class="px-4 py-4 font-label-md text-on-surface-variant">Kelas</th>
                        <th class="px-4 py-4 font-label-md text-on-surface-variant">Tanggal</th>
                        <th class="px-4 py-4 font-label-md text-on-surface-variant">Pelanggaran</th>
                        <th class="px-4 py-4 font-label-md text-on-surface-variant">Kategori</th>
                        <th class="px-4 py-4 font-label-md text-on-surface-variant">Poin</th>
                        <th class="px-4 py-4 font-label-md text-on-surface-variant">Sanksi</th>
                        <th class="px-4 py-4 font-label-md text-on-surface-variant">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse($semuaPelanggaran as $p)
                    <tr class="hover:bg-surface-container transition-colors">
                        <td class="px-4 py-4 text-sm text-on-surface-variant">{{ $loop->iteration }}</td>
                        <td class="px-4 py-4 font-semibold">{{ $p->siswa?->user?->name ?? '-' }}</td>
                        <td class="px-4 py-4 text-sm">{{ $p->siswa?->kelas?->nama ?? '-' }}</td>
                        <td class="px-4 py-4 text-sm">{{ $p->tanggal?->isoFormat('D MMM YYYY') }}</td>
                        <td class="px-4 py-4">{{ $p->pelanggaran }}</td>
                        <td class="px-4 py-4 text-sm">{{ $p->kategori?->nama ?? '-' }}</td>
                        <td class="px-4 py-4">
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-600">{{ $p->poin }}</span>
                        </td>
                        <td class="px-4 py-4 text-sm">{{ $p->sanksi ?? '-' }}</td>
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-1">
                                <button @click="openEdit = true; editId = {{ $p->id }}; form = { tanggal: '{{ $p->tanggal?->format('Y-m-d') }}', pelanggaran: '{{ $p->pelanggaran }}', poin: '{{ $p->poin }}', sanksi: '{{ $p->sanksi ?? '' }}', keterangan: '{{ $p->keterangan ?? '' }}' }" class="p-1.5 hover:bg-surface-container-low rounded-lg">
                                    <span class="material-symbols-outlined text-outline text-[18px]">edit</span>
                                </button>
                                <form method="POST" action="{{ route('admin.pelanggaran-siswa.destroy', $p->id) }}" @submit.prevent="Swal.fire({title:'Konfirmasi',text:'Yakin ingin menghapus pelanggaran ini?',icon:'warning',showCancelButton:true,confirmButtonText:'Ya, hapus!',cancelButtonText:'Batal'}).then(r=>r.isConfirmed&&$el.submit())">
                                    @csrf
                                    @method('DELETE')
                                    <button class="p-1.5 hover:bg-error/10 rounded-lg">
                                        <span class="material-symbols-outlined text-error text-[18px]">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="px-6 py-8 text-center text-on-surface-variant">Belum ada data pelanggaran.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($semuaPelanggaran->hasPages())
        <div class="px-6 py-4 border-t border-outline-variant">
            {{ $semuaPelanggaran->links('vendor.pagination.custom') }}
        </div>
        @endif
    </div>
</div>

<!-- Tab 2: Kategori Pelanggaran -->
<div x-show="tab === 'kategori'">
    <div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-surface-container-low">
                    <tr>
                        <th class="px-6 py-4 font-label-md text-on-surface-variant">Nama</th>
                        <th class="px-6 py-4 font-label-md text-on-surface-variant">Poin</th>
                        <th class="px-6 py-4 font-label-md text-on-surface-variant">Sanksi</th>
                        <th class="px-6 py-4 font-label-md text-on-surface-variant">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse($kategoriList as $k)
                    <tr class="hover:bg-surface-container transition-colors">
                        <td class="px-6 py-4 font-semibold">{{ $k->nama }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-600">{{ $k->poin }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm">{{ $k->sanksi ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <button @click="openKategoriModal = true; kategoriEditMode = true; kategoriForm = { nama: '{{ $k->nama }}', poin: '{{ $k->poin }}', sanksi: '{{ $k->sanksi ?? '' }}' }; kategoriEditId = {{ $k->id }}" class="p-1.5 hover:bg-surface-container-low rounded-lg">
                                    <span class="material-symbols-outlined text-outline text-[18px]">edit</span>
                                </button>
                                <form method="POST" action="{{ route('admin.pelanggaran-siswa.destroy-kategori', $k->id) }}" @submit.prevent="Swal.fire({title:'Konfirmasi',text:'Yakin ingin menghapus kategori {{ $k->nama }}?',icon:'warning',showCancelButton:true,confirmButtonText:'Ya, hapus!',cancelButtonText:'Batal'}).then(r=>r.isConfirmed&&$el.submit())">
                                    @csrf
                                    @method('DELETE')
                                    <button class="p-1.5 hover:bg-error/10 rounded-lg">
                                        <span class="material-symbols-outlined text-error text-[18px]">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-6 py-8 text-center text-on-surface-variant">Belum ada kategori pelanggaran.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Create Pelanggaran -->
<div x-show="openCreate" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.away="openCreate = false">
    <div class="fixed inset-0 bg-black/40" @click="openCreate = false"></div>
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg p-8 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-headline-sm text-headline-sm">Tambah Catatan Pelanggaran</h3>
            <button @click="openCreate = false" class="p-2 hover:bg-surface-container rounded-lg"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form action="{{ route('admin.pelanggaran-siswa.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Kelas</label>
                <select x-model="form.kelas_id" @change="fetchSiswa()" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" name="kelas_id" required>
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($kelasList as $k)
                    <option value="{{ $k->id }}">{{ $k->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Siswa</label>
                <select x-model="form.siswa_id" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" name="siswa_id" required>
                    <option value="">-- Pilih Siswa --</option>
                    <template x-for="s in siswaList" :key="s.id">
                        <option x-bind:value="s.id" x-text="s.nama"></option>
                    </template>
                </select>
            </div>
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Kategori</label>
                <select x-model="form.kategori_id" @change="fillFromKategori()" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" name="kategori_id">
                    <option value="">-- Pilih Kategori (opsional) --</option>
                    @foreach($kategoriList as $kat)
                    <option value="{{ $kat->id }}">{{ $kat->nama }} ({{ $kat->poin }} poin)</option>
                    @endforeach
                </select>
            </div>
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Tanggal</label>
                <input x-model="form.tanggal" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" type="date" name="tanggal" required>
            </div>
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Pelanggaran</label>
                <input x-model="form.pelanggaran" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" type="text" name="pelanggaran" required>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Poin</label>
                    <input x-model="form.poin" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" type="number" name="poin" required>
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Sanksi</label>
                    <input x-model="form.sanksi" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" type="text" name="sanksi">
                </div>
            </div>
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Keterangan</label>
                <textarea x-model="form.keterangan" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" rows="3" name="keterangan"></textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" @click="openCreate = false" class="flex-1 py-3 border border-outline-variant rounded-xl font-label-md hover:bg-surface-container">Batal</button>
                <button type="submit" class="flex-1 py-3 bg-primary text-on-primary rounded-xl font-label-md hover:bg-primary-container">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Pelanggaran -->
<div x-show="openEdit" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.away="openEdit = false">
    <div class="fixed inset-0 bg-black/40" @click="openEdit = false"></div>
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg p-8 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-headline-sm text-headline-sm">Edit Catatan Pelanggaran</h3>
            <button @click="openEdit = false" class="p-2 hover:bg-surface-container rounded-lg"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form method="POST" action="{{ route('admin.pelanggaran-siswa.update', '__ID__') }}" x-bind:action="'{{ route('admin.pelanggaran-siswa.update', '__ID__') }}'.replace('__ID__', editId)" class="space-y-4">
            @csrf
            @method('PUT')
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Tanggal</label>
                <input x-model="form.tanggal" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" type="date" name="tanggal" required>
            </div>
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Pelanggaran</label>
                <input x-model="form.pelanggaran" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" type="text" name="pelanggaran" required>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Poin</label>
                    <input x-model="form.poin" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" type="number" name="poin" required>
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Sanksi</label>
                    <input x-model="form.sanksi" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" type="text" name="sanksi">
                </div>
            </div>
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Keterangan</label>
                <textarea x-model="form.keterangan" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" rows="3" name="keterangan"></textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" @click="openEdit = false" class="flex-1 py-3 border border-outline-variant rounded-xl font-label-md hover:bg-surface-container">Batal</button>
                <button type="submit" class="flex-1 py-3 bg-primary text-on-primary rounded-xl font-label-md hover:bg-primary-container">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Kategori (Create/Edit) -->
<div x-show="openKategoriModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.away="openKategoriModal = false">
    <div class="fixed inset-0 bg-black/40" @click="openKategoriModal = false"></div>
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg p-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-headline-sm text-headline-sm" x-text="kategoriEditMode ? 'Edit Kategori' : 'Tambah Kategori'"></h3>
            <button @click="openKategoriModal = false" class="p-2 hover:bg-surface-container rounded-lg"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form :action="kategoriEditMode ? '{{ route('admin.pelanggaran-siswa.update-kategori', '__ID__') }}'.replace('__ID__', kategoriEditId) : '{{ route('admin.pelanggaran-siswa.store-kategori') }}'" method="POST" class="space-y-4">
            @csrf
            <template x-if="kategoriEditMode">
                <input type="hidden" name="_method" value="POST">
            </template>
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Nama Kategori</label>
                <input x-model="kategoriForm.nama" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" type="text" name="nama" required>
            </div>
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Poin</label>
                <input x-model="kategoriForm.poin" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" type="number" name="poin" required>
            </div>
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Sanksi</label>
                <input x-model="kategoriForm.sanksi" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" type="text" name="sanksi">
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" @click="openKategoriModal = false" class="flex-1 py-3 border border-outline-variant rounded-xl font-label-md hover:bg-surface-container">Batal</button>
                <button type="submit" class="flex-1 py-3 bg-primary text-on-primary rounded-xl font-label-md hover:bg-primary-container" x-text="kategoriEditMode ? 'Simpan' : 'Tambah'"></button>
            </div>
        </form>
    </div>
</div>
</x-layouts.admin>
