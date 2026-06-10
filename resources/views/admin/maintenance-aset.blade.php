@php
$statusBadge = fn($s) => match($s) {
    'direncanakan' => 'bg-info/10 text-info',
    'sedang_dikerjakan' => 'bg-warning/10 text-warning',
    'selesai' => 'bg-success/10 text-success',
    default => 'bg-surface-container-low text-on-surface',
};
$statusLabel = fn($s) => match($s) {
    'direncanakan' => 'Direncanakan',
    'sedang_dikerjakan' => 'Sedang Dikerjakan',
    'selesai' => 'Selesai',
    default => $s,
};
@endphp
<x-layouts.admin title="Maintenance Aset | EduPortal">
    <x-slot:styles>
    <style>
        [x-cloak] { display: none !important; }
    </style>
    </x-slot:styles>
<div x-data="{ openModal: false, editMode: false, form: { barang_id: '', ruang_id: '', jenis: 'perbaikan', deskripsi: '', tanggal_mulai: '', tanggal_selesai: '', biaya: '', status: 'direncanakan', pelaksana: '', keterangan: '' }, editId: null }">
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-primary">Maintenance & Perbaikan</h2>
        <p class="text-on-surface-variant font-body-md">Kelola perawatan dan perbaikan aset sekolah</p>
    </div>
    <button @click="openModal = true; editMode = false; form = { barang_id: '', ruang_id: '', jenis: 'perbaikan', deskripsi: '', tanggal_mulai: '', tanggal_selesai: '', biaya: '', status: 'direncanakan', pelaksana: '', keterangan: '' }; editId = null" class="bg-primary text-on-primary px-6 py-2.5 rounded-lg flex items-center gap-2 font-label-md hover:bg-primary-container transition-all active:scale-95">
        <span class="material-symbols-outlined">add</span>
        Tambah Maintenance
    </button>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-outline-variant p-5 card-shadow">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-lg bg-info/10 flex items-center justify-center">
                <span class="material-symbols-outlined text-info">build</span>
            </div>
            <div>
                <p class="text-2xl font-headline-sm font-bold">{{ $countDirencanakan }}</p>
                <p class="text-xs text-on-surface-variant">Direncanakan</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-outline-variant p-5 card-shadow">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-lg bg-warning/10 flex items-center justify-center">
                <span class="material-symbols-outlined text-warning">engineering</span>
            </div>
            <div>
                <p class="text-2xl font-headline-sm font-bold">{{ $countSedangDikerjakan }}</p>
                <p class="text-xs text-on-surface-variant">Sedang Dikerjakan</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-outline-variant p-5 card-shadow">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-lg bg-success/10 flex items-center justify-center">
                <span class="material-symbols-outlined text-success">check_circle</span>
            </div>
            <div>
                <p class="text-2xl font-headline-sm font-bold">{{ $countSelesai }}</p>
                <p class="text-xs text-on-surface-variant">Selesai</p>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-surface-container-low">
                <tr>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Aset</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Jenis</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Deskripsi</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Tanggal</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Pelaksana</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Status</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant">
                @forelse($semuaMaintenance as $maintenance)
                <tr class="hover:bg-surface-container transition-colors">
                    <td class="px-6 py-4">
                        @if($maintenance->barang)
                        <span class="font-body-md font-semibold">{{ $maintenance->barang->nama }}</span>
                        <span class="text-xs text-on-surface-variant block">{{ $maintenance->barang->kode }}</span>
                        @elseif($maintenance->ruang)
                        <span class="font-body-md font-semibold">{{ $maintenance->ruang->nama }}</span>
                        <span class="text-xs text-on-surface-variant block">{{ $maintenance->ruang->kode }}</span>
                        @else
                        -
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-xs font-label-md bg-surface-container-low">
                            {{ ucfirst($maintenance->jenis) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 max-w-[250px] truncate">{{ $maintenance->deskripsi }}</td>
                    <td class="px-6 py-4 text-sm">
                        <div>{{ $maintenance->tanggal_mulai->format('d M Y') }}</div>
                        @if($maintenance->tanggal_selesai)
                        <div class="text-on-surface-variant">- {{ $maintenance->tanggal_selesai->format('d M Y') }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4">{{ $maintenance->pelaksana ?? '-' }}</td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-xs font-label-md {{ $statusBadge($maintenance->status) }}">{{ $statusLabel($maintenance->status) }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <button @click="openModal = true; editMode = true; form = { barang_id: '{{ $maintenance->barang_id ?? '' }}', ruang_id: '{{ $maintenance->ruang_id ?? '' }}', jenis: '{{ $maintenance->jenis }}', deskripsi: '{{ addslashes($maintenance->deskripsi) }}', tanggal_mulai: '{{ $maintenance->tanggal_mulai->format('Y-m-d') }}', tanggal_selesai: '{{ $maintenance->tanggal_selesai?->format('Y-m-d') ?? '' }}', biaya: '{{ $maintenance->biaya ?? '' }}', status: '{{ $maintenance->status }}', pelaksana: '{{ $maintenance->pelaksana ?? '' }}', keterangan: '{{ addslashes($maintenance->keterangan ?? '') }}' }; editId = {{ $maintenance->id }}" class="p-2 hover:bg-surface-container-low rounded-lg transition-colors">
                                <span class="material-symbols-outlined text-outline">edit</span>
                            </button>
                            <form method="POST" action="{{ route('admin.maintenance-aset.destroy', $maintenance) }}" @submit.prevent="Swal.fire({title:'Konfirmasi',text:'Yakin ingin menghapus data maintenance ini?',icon:'warning',showCancelButton:true,confirmButtonText:'Ya, hapus!',cancelButtonText:'Batal'}).then(r=>r.isConfirmed&&$el.submit())">
                                @csrf
                                @method('DELETE')
                                <button class="p-2 hover:bg-error/10 rounded-lg transition-colors">
                                    <span class="material-symbols-outlined text-error">delete</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td class="px-6 py-8 text-center text-on-surface-variant" colspan="7">Belum ada data maintenance.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($semuaMaintenance->hasPages())
    <div class="px-6 py-4 border-t border-outline-variant">
        {{ $semuaMaintenance->links() }}
    </div>
    @endif
</div>

<div x-show="openModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.away="openModal = false">
    <div class="fixed inset-0 bg-black/40" @click="openModal = false"></div>
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg p-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-headline-sm text-headline-sm" x-text="editMode ? 'Edit Maintenance' : 'Tambah Maintenance'"></h3>
            <button @click="openModal = false" class="p-2 hover:bg-surface-container rounded-lg">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form :action="editMode ? '{{ route('admin.maintenance-aset.update', '__ID__') }}'.replace('__ID__', editId) : '{{ route('admin.maintenance-aset.store') }}'" method="POST" class="space-y-4">
            @csrf
            <template x-if="editMode">
                <input type="hidden" name="_method" value="PUT">
            </template>
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Barang</label>
                    <select x-model="form.barang_id" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" name="barang_id">
                        <option value="">Pilih Barang</option>
                        @foreach($daftarBarang as $barang)
                        <option value="{{ $barang->id }}">{{ $barang->kode }} - {{ $barang->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Ruang</label>
                    <select x-model="form.ruang_id" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" name="ruang_id">
                        <option value="">Pilih Ruang</option>
                        @foreach($daftarRuang as $ruang)
                        <option value="{{ $ruang->id }}">{{ $ruang->kode }} - {{ $ruang->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Jenis</label>
                    <select x-model="form.jenis" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" name="jenis" required>
                        <option value="perbaikan">Perbaikan</option>
                        <option value="perawatan">Perawatan</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Status</label>
                    <select x-model="form.status" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" name="status" required>
                        <option value="direncanakan">Direncanakan</option>
                        <option value="sedang_dikerjakan">Sedang Dikerjakan</option>
                        <option value="selesai">Selesai</option>
                    </select>
                </div>
            </div>
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Deskripsi</label>
                <textarea x-model="form.deskripsi" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" rows="3" name="deskripsi" required></textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Tanggal Mulai</label>
                    <input x-model="form.tanggal_mulai" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" type="date" name="tanggal_mulai" required>
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Tanggal Selesai</label>
                    <input x-model="form.tanggal_selesai" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" type="date" name="tanggal_selesai">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Biaya</label>
                    <input x-model="form.biaya" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" type="number" name="biaya" min="0" step="0.01" placeholder="0">
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Pelaksana</label>
                    <input x-model="form.pelaksana" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" placeholder="Nama pelaksana" type="text" name="pelaksana">
                </div>
            </div>
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Keterangan</label>
                <textarea x-model="form.keterangan" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" rows="2" name="keterangan"></textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" @click="openModal = false" class="flex-1 py-3 border border-outline-variant rounded-xl font-label-md hover:bg-surface-container transition-all">Batal</button>
                <button type="submit" class="flex-1 py-3 bg-primary text-on-primary rounded-xl font-label-md hover:bg-primary-container transition-all" x-text="editMode ? 'Simpan' : 'Tambah'"></button>
            </div>
        </form>
    </div>
</div>
</div>
</x-layouts.admin>
