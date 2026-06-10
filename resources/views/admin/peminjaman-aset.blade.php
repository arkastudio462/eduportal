@php
$statusBadge = fn($s) => match($s) {
    'diajukan' => 'bg-info/10 text-info',
    'disetujui' => 'bg-success/10 text-success',
    'dipinjam' => 'bg-warning/10 text-warning',
    'dikembalikan' => 'bg-surface-container-low text-on-surface',
    'ditolak' => 'bg-error/10 text-error',
    default => 'bg-surface-container-low text-on-surface',
};
$statusLabel = fn($s) => match($s) {
    'diajukan' => 'Diajukan',
    'disetujui' => 'Disetujui',
    'dipinjam' => 'Dipinjam',
    'dikembalikan' => 'Dikembalikan',
    'ditolak' => 'Ditolak',
    default => $s,
};
@endphp
<x-layouts.admin title="Peminjaman Aset | EduPortal">
    <x-slot:styles>
    <style>
        [x-cloak] { display: none !important; }
    </style>
    </x-slot:styles>
<div x-data="{ openModal: false, editMode: false, form: { peminjam_type: 'App\\Models\\User', peminjam_id: '', ruang_id: '', barang_id: '', tujuan: '', tanggal_mulai: '', tanggal_selesai: '', status: 'diajukan', keterangan: '' }, editId: null }">
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-primary">Peminjaman Aset</h2>
        <p class="text-on-surface-variant font-body-md">Kelola peminjaman ruang dan barang</p>
    </div>
    <button @click="openModal = true; editMode = false; form = { peminjam_type: 'App\\Models\\User', peminjam_id: '{{ auth()->id() }}', ruang_id: '', barang_id: '', tujuan: '', tanggal_mulai: '', tanggal_selesai: '', status: 'diajukan', keterangan: '' }; editId = null" class="bg-primary text-on-primary px-6 py-2.5 rounded-lg flex items-center gap-2 font-label-md hover:bg-primary-container transition-all active:scale-95">
        <span class="material-symbols-outlined">add</span>
        Tambah Peminjaman
    </button>
</div>

<div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-surface-container-low">
                <tr>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Peminjam</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Aset</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Tujuan</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Tanggal</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Status</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant">
                @forelse($semuaPeminjaman as $peminjaman)
                <tr class="hover:bg-surface-container transition-colors">
                    <td class="px-6 py-4">
                        <span class="font-body-md font-semibold">{{ $peminjaman->peminjam?->name ?? $peminjaman->peminjam_type }}</span>
                        <span class="text-xs text-on-surface-variant block">{{ class_basename($peminjaman->peminjam_type) }}</span>
                    </td>
                    <td class="px-6 py-4">
                        @if($peminjaman->ruang)
                        <span class="material-symbols-outlined text-sm align-text-bottom">meeting_room</span> {{ $peminjaman->ruang->nama }}
                        @elseif($peminjaman->barang)
                        <span class="material-symbols-outlined text-sm align-text-bottom">inventory_2</span> {{ $peminjaman->barang->nama }}
                        @else
                        -
                        @endif
                    </td>
                    <td class="px-6 py-4 max-w-[200px] truncate">{{ $peminjaman->tujuan }}</td>
                    <td class="px-6 py-4 text-sm">
                        <div>{{ $peminjaman->tanggal_mulai->format('d M Y') }}</div>
                        <div class="text-on-surface-variant">{{ $peminjaman->tanggal_selesai->format('d M Y') }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-xs font-label-md {{ $statusBadge($peminjaman->status) }}">{{ $statusLabel($peminjaman->status) }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <button @click="openModal = true; editMode = true; form = { peminjam_type: '{{ $peminjaman->peminjam_type }}', peminjam_id: '{{ $peminjaman->peminjam_id }}', ruang_id: '{{ $peminjaman->ruang_id ?? '' }}', barang_id: '{{ $peminjaman->barang_id ?? '' }}', tujuan: '{{ $peminjaman->tujuan }}', tanggal_mulai: '{{ $peminjaman->tanggal_mulai->format('Y-m-d\TH:i') }}', tanggal_selesai: '{{ $peminjaman->tanggal_selesai->format('Y-m-d\TH:i') }}', status: '{{ $peminjaman->status }}', keterangan: '{{ addslashes($peminjaman->keterangan ?? '') }}' }; editId = {{ $peminjaman->id }}" class="p-2 hover:bg-surface-container-low rounded-lg transition-colors">
                                <span class="material-symbols-outlined text-outline">edit</span>
                            </button>
                            <form method="POST" action="{{ route('admin.peminjaman-aset.destroy', $peminjaman) }}" @submit.prevent="Swal.fire({title:'Konfirmasi',text:'Yakin ingin menghapus peminjaman ini?',icon:'warning',showCancelButton:true,confirmButtonText:'Ya, hapus!',cancelButtonText:'Batal'}).then(r=>r.isConfirmed&&$el.submit())">
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
                <tr><td class="px-6 py-8 text-center text-on-surface-variant" colspan="6">Belum ada data peminjaman.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($semuaPeminjaman->hasPages())
    <div class="px-6 py-4 border-t border-outline-variant">
        {{ $semuaPeminjaman->links() }}
    </div>
    @endif
</div>

<div x-show="openModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.away="openModal = false">
    <div class="fixed inset-0 bg-black/40" @click="openModal = false"></div>
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg p-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-headline-sm text-headline-sm" x-text="editMode ? 'Edit Peminjaman' : 'Tambah Peminjaman'"></h3>
            <button @click="openModal = false" class="p-2 hover:bg-surface-container rounded-lg">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form :action="editMode ? '{{ route('admin.peminjaman-aset.update', '__ID__') }}'.replace('__ID__', editId) : '{{ route('admin.peminjaman-aset.store') }}'" method="POST" class="space-y-4">
            @csrf
            <template x-if="editMode">
                <input type="hidden" name="_method" value="PUT">
            </template>
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Tipe Peminjam</label>
                    <select x-model="form.peminjam_type" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" name="peminjam_type" required>
                        <option value="App\Models\User">User</option>
                        <option value="App\Models\Guru">Guru</option>
                        <option value="App\Models\Siswa">Siswa</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">ID Peminjam</label>
                    <input x-model="form.peminjam_id" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" placeholder="ID User" type="number" name="peminjam_id" required>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Ruang</label>
                    <select x-model="form.ruang_id" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" name="ruang_id">
                        <option value="">Pilih Ruang</option>
                        @foreach($daftarRuang as $ruang)
                        <option value="{{ $ruang->id }}">{{ $ruang->kode }} - {{ $ruang->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Barang</label>
                    <select x-model="form.barang_id" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" name="barang_id">
                        <option value="">Pilih Barang</option>
                        @foreach($daftarBarang as $barang)
                        <option value="{{ $barang->id }}">{{ $barang->kode }} - {{ $barang->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Tujuan</label>
                <input x-model="form.tujuan" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" placeholder="Tujuan peminjaman" type="text" name="tujuan" required>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Tanggal Mulai</label>
                    <input x-model="form.tanggal_mulai" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" type="datetime-local" name="tanggal_mulai" required>
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Tanggal Selesai</label>
                    <input x-model="form.tanggal_selesai" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" type="datetime-local" name="tanggal_selesai" required>
                </div>
            </div>
            <template x-if="editMode">
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Status</label>
                <select x-model="form.status" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" name="status" required>
                    <option value="diajukan">Diajukan</option>
                    <option value="disetujui">Disetujui</option>
                    <option value="dipinjam">Dipinjam</option>
                    <option value="dikembalikan">Dikembalikan</option>
                    <option value="ditolak">Ditolak</option>
                </select>
            </div>
            </template>
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
