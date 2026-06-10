<x-layouts.admin title="Bimbingan Konseling | EduPortal">
    <x-slot:styles>
    <style>
        [x-cloak] { display: none !important; }
    </style>
    </x-slot:styles>
<div x-data="{
    openModal: false,
    editMode: false,
    editId: null,
    deleteModal: false,
    deleteUrl: '',
    statusFilter: '{{ request('status') }}',
    guruFilter: '{{ request('guru_id') }}',
    siswaList: [],
    form: {
        kelas_id: '',
        siswa_id: '',
        guru_id: '',
        tanggal: '',
        jam: '',
        topik: '',
        catatan: '',
        tindak_lanjut: '',
        status: 'dijadwalkan'
    },
    resetForm() {
        this.form = { kelas_id: '', siswa_id: '', guru_id: '', tanggal: '', jam: '', topik: '', catatan: '', tindak_lanjut: '', status: 'dijadwalkan' };
        this.siswaList = [];
        this.editMode = false;
        this.editId = null;
    },
    openCreateModal() {
        this.resetForm();
        this.openModal = true;
    },
    openEditModal(id, siswaId, guruId, tanggal, jam, topik, catatan, tindakLanjut, status) {
        this.resetForm();
        this.editMode = true;
        this.editId = id;
        this.form.siswa_id = siswaId;
        this.form.guru_id = guruId;
        this.form.tanggal = tanggal;
        this.form.jam = jam;
        this.form.topik = topik;
        this.form.catatan = catatan;
        this.form.tindak_lanjut = tindakLanjut;
        this.form.status = status;
        this.openModal = true;
    },
    fetchSiswa() {
        if (!this.form.kelas_id) { this.siswaList = []; return; }
        fetch('{{ route('admin.konseling.get-siswa') }}?kelas_id=' + this.form.kelas_id)
            .then(r => r.json())
            .then(data => { this.siswaList = data; })
            .catch(() => { this.siswaList = []; });
    },
    applyFilter() {
        let url = '{{ route('admin.konseling') }}';
        let params = [];
        if (this.statusFilter) params.push('status=' + this.statusFilter);
        if (this.guruFilter) params.push('guru_id=' + this.guruFilter);
        if (params.length) url += '?' + params.join('&');
        window.location = url;
    },
    confirmDelete(url) {
        this.deleteUrl = url;
        this.deleteModal = true;
    }
}">
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-primary">Bimbingan Konseling</h2>
        <p class="text-on-surface-variant font-body-md">Jadwal konseling & catatan BK</p>
    </div>
    <button @click="openCreateModal()" class="px-4 py-2.5 bg-primary text-on-primary rounded-lg flex items-center gap-2 font-label-md hover:bg-primary-container transition-all active:scale-95">
        <span class="material-symbols-outlined">add</span>
        Tambah Konseling
    </button>
</div>

{{-- Stats --}}
@php
$total = $semuaKonseling->total();
$dijadwalkan = $semuaKonseling->filter(fn($k) => $k->status === 'dijadwalkan')->count();
$selesai = $semuaKonseling->filter(fn($k) => $k->status === 'selesai')->count();
$dibatalkan = $semuaKonseling->filter(fn($k) => $k->status === 'dibatalkan')->count();
@endphp
<div class="grid grid-cols-2 lg:grid-cols-4 gap-gutter mb-6">
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-primary-fixed text-primary flex items-center justify-center"><span class="material-symbols-outlined" style="font-variation-settings:'FILL'1">forum</span></div>
        <div><p class="text-xs text-on-surface-variant">Total Konseling</p><h3 class="font-headline-md text-headline-md text-primary">{{ $total }}</h3></div>
    </div>
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center"><span class="material-symbols-outlined" style="font-variation-settings:'FILL'1">schedule</span></div>
        <div><p class="text-xs text-on-surface-variant">Dijadwalkan</p><h3 class="font-headline-md text-headline-md text-blue-700">{{ $dijadwalkan }}</h3></div>
    </div>
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-green-100 text-green-700 flex items-center justify-center"><span class="material-symbols-outlined" style="font-variation-settings:'FILL'1">check_circle</span></div>
        <div><p class="text-xs text-on-surface-variant">Selesai</p><h3 class="font-headline-md text-headline-md text-green-700">{{ $selesai }}</h3></div>
    </div>
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-red-100 text-red-700 flex items-center justify-center"><span class="material-symbols-outlined" style="font-variation-settings:'FILL'1">cancel</span></div>
        <div><p class="text-xs text-on-surface-variant">Dibatalkan</p><h3 class="font-headline-md text-headline-md text-red-700">{{ $dibatalkan }}</h3></div>
    </div>
</div>

{{-- Filter --}}
<div class="bg-white rounded-xl border border-outline-variant card-shadow p-4 mb-6 flex flex-wrap items-end gap-4">
    <div class="space-y-1">
        <label class="font-label-md text-label-md text-on-surface-variant text-xs">Status</label>
        <select x-model="statusFilter" class="bg-surface-container-low border border-outline-variant rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-primary">
            <option value="">Semua Status</option>
            <option value="dijadwalkan">Dijadwalkan</option>
            <option value="selesai">Selesai</option>
            <option value="dibatalkan">Dibatalkan</option>
        </select>
    </div>
    <div class="space-y-1">
        <label class="font-label-md text-label-md text-on-surface-variant text-xs">Guru BK</label>
        <select x-model="guruFilter" class="bg-surface-container-low border border-outline-variant rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-primary">
            <option value="">Semua Guru</option>
            @foreach($semuaGuru as $g)
            <option value="{{ $g->id }}">{{ $g->user->name }}</option>
            @endforeach
        </select>
    </div>
    <button @click="applyFilter()" class="px-4 py-2 bg-primary text-on-primary rounded-lg font-label-md text-sm hover:bg-primary-container transition-all">Terapkan Filter</button>
</div>

{{-- Table --}}
<div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-surface-container-low">
                <tr>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant w-12">No</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Nama Siswa</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Kelas</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Guru BK</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Tanggal</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Jam</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Topik</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Status</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant">
                @forelse($semuaKonseling as $k)
                <tr class="hover:bg-surface-container transition-colors">
                    <td class="px-6 py-4 text-on-surface-variant">{{ $loop->iteration + ($semuaKonseling->currentPage() - 1) * $semuaKonseling->perPage() }}</td>
                    <td class="px-6 py-4 font-body-md font-semibold">{{ $k->siswa->user->name ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $k->siswa->kelas->nama ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $k->guru->user->name ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $k->tanggal?->isoFormat('D MMMM YYYY') ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $k->jam ? \Carbon\Carbon::parse($k->jam)->format('H:i') : '-' }}</td>
                    <td class="px-6 py-4 max-w-xs truncate">{{ $k->topik }}</td>
                    <td class="px-6 py-4">
                        @if($k->status === 'dijadwalkan')
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700">Dijadwalkan</span>
                        @elseif($k->status === 'selesai')
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">Selesai</span>
                        @else
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">Dibatalkan</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <button @click="openEditModal('{{ $k->id }}', '{{ $k->siswa_id }}', '{{ $k->guru_id }}', '{{ $k->tanggal?->format('Y-m-d') ?? '' }}', '{{ $k->jam ? \Carbon\Carbon::parse($k->jam)->format('H:i') : '' }}', '{{ $k->topik }}', '{{ $k->catatan ?? '' }}', '{{ $k->tindak_lanjut ?? '' }}', '{{ $k->status }}')" class="p-2 hover:bg-surface-container-low rounded-lg">
                                <span class="material-symbols-outlined text-outline">edit</span>
                            </button>
                            <button @click="confirmDelete('{{ route('admin.konseling.destroy', $k) }}')" class="p-2 hover:bg-error/10 rounded-lg">
                                <span class="material-symbols-outlined text-error">delete</span>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td class="px-6 py-8 text-center text-on-surface-variant" colspan="9">Belum ada data konseling.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($semuaKonseling->hasPages())
    <div class="px-6 py-4 border-t border-outline-variant">
        {{ $semuaKonseling->links() }}
    </div>
    @endif
</div>

{{-- Create/Edit Modal --}}
<div x-show="openModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.away="openModal = false">
    <div class="fixed inset-0 bg-black/40" @click="openModal = false"></div>
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg p-8 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-headline-sm text-headline-sm" x-text="editMode ? 'Edit Konseling' : 'Tambah Konseling'"></h3>
            <button @click="openModal = false" class="p-2 hover:bg-surface-container rounded-lg"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form :action="editMode ? '{{ route('admin.konseling.update', '__ID__') }}'.replace('__ID__', editId) : '{{ route('admin.konseling.store') }}'" method="POST" class="space-y-4">
            @csrf
            <template x-if="editMode"><input type="hidden" name="_method" value="PUT"></template>

            <template x-if="!editMode">
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Kelas</label>
                    <select x-model="form.kelas_id" @change="fetchSiswa()" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelasList as $kelas)
                        <option value="{{ $kelas->id }}">{{ $kelas->tingkat }} {{ $kelas->nama }} {{ $kelas->jurusan?->nama ?? '' }}</option>
                        @endforeach
                    </select>
                </div>
            </template>

            <template x-if="!editMode">
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Siswa</label>
                    <select x-model="form.siswa_id" name="siswa_id" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" required>
                        <option value="">-- Pilih Siswa --</option>
                        <template x-for="siswa in siswaList" :key="siswa.id">
                            <option x-bind:value="siswa.id" x-text="siswa.nama"></option>
                        </template>
                    </select>
                </div>
            </template>

            <template x-if="editMode">
                <input type="hidden" name="siswa_id" x-model="form.siswa_id">
            </template>

            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Guru BK</label>
                <select x-model="form.guru_id" name="guru_id" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" required>
                    <option value="">-- Pilih Guru BK --</option>
                    @foreach($semuaGuru as $g)
                    <option value="{{ $g->id }}">{{ $g->user->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Tanggal</label>
                    <input x-model="form.tanggal" type="date" name="tanggal" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" required>
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Jam</label>
                    <input x-model="form.jam" type="time" name="jam" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2">
                </div>
            </div>

            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Topik</label>
                <input x-model="form.topik" type="text" name="topik" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" placeholder="Topik konseling" required>
            </div>

            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Catatan</label>
                <textarea x-model="form.catatan" name="catatan" rows="3" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" placeholder="Catatan konseling (opsional)"></textarea>
            </div>

            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Tindak Lanjut</label>
                <textarea x-model="form.tindak_lanjut" name="tindak_lanjut" rows="3" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" placeholder="Tindak lanjut (opsional)"></textarea>
            </div>

            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Status</label>
                <select x-model="form.status" name="status" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" required>
                    <option value="dijadwalkan">Dijadwalkan</option>
                    <option value="selesai">Selesai</option>
                    <option value="dibatalkan">Dibatalkan</option>
                </select>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="button" @click="openModal = false" class="flex-1 py-3 border border-outline-variant rounded-xl font-label-md hover:bg-surface-container">Batal</button>
                <button type="submit" class="flex-1 py-3 bg-primary text-on-primary rounded-xl font-label-md hover:bg-primary-container" x-text="editMode ? 'Simpan' : 'Tambah'"></button>
            </div>
        </form>
    </div>
</div>

{{-- Delete Modal --}}
<div x-show="deleteModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.away="deleteModal = false">
    <div class="fixed inset-0 bg-black/40" @click="deleteModal = false"></div>
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
        <div class="flex items-center gap-4 mb-4">
            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                <span class="material-symbols-outlined text-red-600 text-2xl">delete_forever</span>
            </div>
            <div>
                <h3 class="font-headline-sm text-headline-sm">Konfirmasi Hapus</h3>
                <p class="text-sm text-on-surface-variant mt-1">Apakah Anda yakin ingin menghapus data konseling ini? Tindakan ini tidak dapat dibatalkan.</p>
            </div>
        </div>
        <div class="flex justify-end gap-3 mt-6">
            <button @click="deleteModal = false" type="button" class="px-4 py-2 border border-outline-variant rounded-lg font-label-md hover:bg-surface-container transition-all">Batal</button>
            <form method="POST" :action="deleteUrl" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg font-label-md hover:bg-red-700 transition-all">Hapus</button>
            </form>
        </div>
    </div>
</div>
</x-layouts.admin>
