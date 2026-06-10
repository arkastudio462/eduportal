<x-layouts.admin title="Ruang | EduPortal">
    <x-slot:styles>
    <style>
        [x-cloak] { display: none !important; }
    </style>
    </x-slot:styles>
<div x-data="{ openModal: false, editMode: false, form: { kode: '', nama: '', lantai: '', gedung: '', kapasitas: '', jenis: 'kelas', keterangan: '', status: 'tersedia' }, editId: null }">
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-primary">Ruang</h2>
        <p class="text-on-surface-variant font-body-md">Kelola data ruang dan fasilitas sekolah</p>
    </div>
    <button @click="openModal = true; editMode = false; form = { kode: '', nama: '', lantai: '', gedung: '', kapasitas: '', jenis: 'kelas', keterangan: '', status: 'tersedia' }; editId = null" class="bg-primary text-on-primary px-6 py-2.5 rounded-lg flex items-center gap-2 font-label-md hover:bg-primary-container transition-all active:scale-95">
        <span class="material-symbols-outlined">add</span>
        Tambah Ruang
    </button>
</div>

<div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-surface-container-low">
                <tr>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Kode</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Nama Ruang</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Lantai/Gedung</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Jenis</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Kapasitas</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Status</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant">
                @forelse($semuaRuang as $ruang)
                <tr class="hover:bg-surface-container transition-colors">
                    <td class="px-6 py-4 font-mono text-sm font-semibold">{{ $ruang->kode }}</td>
                    <td class="px-6 py-4 font-body-md font-semibold">{{ $ruang->nama }}</td>
                    <td class="px-6 py-4">{{ $ruang->lantai ? 'Lt. '.$ruang->lantai : '-' }}{{ $ruang->gedung ? ' / '.$ruang->gedung : '' }}</td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-xs font-label-md bg-surface-container-low">
                            {{ ucwords(str_replace('_', ' ', $ruang->jenis)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">{{ $ruang->kapasitas ? $ruang->kapasitas.' orang' : '-' }}</td>
                    <td class="px-6 py-4">
                        @if($ruang->status === 'tersedia')
                        <span class="px-3 py-1 rounded-full text-xs font-label-md bg-success/10 text-success">Tersedia</span>
                        @else
                        <span class="px-3 py-1 rounded-full text-xs font-label-md bg-error/10 text-error">Tidak Tersedia</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <button @click="openModal = true; editMode = true; form = { kode: '{{ $ruang->kode }}', nama: '{{ $ruang->nama }}', lantai: '{{ $ruang->lantai ?? '' }}', gedung: '{{ $ruang->gedung ?? '' }}', kapasitas: '{{ $ruang->kapasitas ?? '' }}', jenis: '{{ $ruang->jenis }}', keterangan: '{{ addslashes($ruang->keterangan ?? '') }}', status: '{{ $ruang->status }}' }; editId = {{ $ruang->id }}" class="p-2 hover:bg-surface-container-low rounded-lg transition-colors">
                                <span class="material-symbols-outlined text-outline">edit</span>
                            </button>
                            <form method="POST" action="{{ route('admin.ruang.destroy', $ruang) }}" @submit.prevent="Swal.fire({title:'Konfirmasi',text:'Yakin ingin menghapus {{ $ruang->nama }}?',icon:'warning',showCancelButton:true,confirmButtonText:'Ya, hapus!',cancelButtonText:'Batal'}).then(r=>r.isConfirmed&&$el.submit())">
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
                <tr><td class="px-6 py-8 text-center text-on-surface-variant" colspan="7">Belum ada data ruang.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($semuaRuang->hasPages())
    <div class="px-6 py-4 border-t border-outline-variant">
        {{ $semuaRuang->links() }}
    </div>
    @endif
</div>

<div x-show="openModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.away="openModal = false">
    <div class="fixed inset-0 bg-black/40" @click="openModal = false"></div>
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg p-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-headline-sm text-headline-sm" x-text="editMode ? 'Edit Ruang' : 'Tambah Ruang'"></h3>
            <button @click="openModal = false" class="p-2 hover:bg-surface-container rounded-lg">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form :action="editMode ? '{{ route('admin.ruang.update', '__ID__') }}'.replace('__ID__', editId) : '{{ route('admin.ruang.store') }}'" method="POST" class="space-y-4">
            @csrf
            <template x-if="editMode">
                <input type="hidden" name="_method" value="PUT">
            </template>
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Kode Ruang</label>
                    <input x-model="form.kode" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" placeholder="Contoh: R-001" type="text" name="kode" required>
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Nama Ruang</label>
                    <input x-model="form.nama" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" placeholder="Contoh: Laboratorium Fisika" type="text" name="nama" required>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Lantai</label>
                    <input x-model="form.lantai" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" placeholder="Contoh: 2" type="text" name="lantai">
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Gedung</label>
                    <input x-model="form.gedung" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" placeholder="Contoh: A" type="text" name="gedung">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Jenis</label>
                    <select x-model="form.jenis" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" name="jenis" required>
                        <option value="kelas">Kelas</option>
                        <option value="lab">Laboratorium</option>
                        <option value="aula">Aula</option>
                        <option value="ruang_guru">Ruang Guru</option>
                        <option value="ruang_admin">Ruang Admin</option>
                        <option value="perpustakaan">Perpustakaan</option>
                        <option value="lapangan">Lapangan</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Kapasitas</label>
                    <input x-model="form.kapasitas" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" placeholder="Jumlah orang" type="number" name="kapasitas" min="1">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Status</label>
                    <select x-model="form.status" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" name="status" required>
                        <option value="tersedia">Tersedia</option>
                        <option value="tidak_tersedia">Tidak Tersedia</option>
                    </select>
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
