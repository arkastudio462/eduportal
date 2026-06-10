<x-layouts.admin title="Barang | EduPortal">
    <x-slot:styles>
    <style>
        [x-cloak] { display: none !important; }
    </style>
    </x-slot:styles>
<div x-data="{ openModal: false, editMode: false, form: { kode: '', nama: '', kategori: '', ruang_id: '', jumlah: 1, kondisi: 'baik', merek: '', tahun_peroleh: '', sumber_dana: '', keterangan: '' }, editId: null }">
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-primary">Barang Inventaris</h2>
        <p class="text-on-surface-variant font-body-md">Kelola data barang dan perlengkapan sekolah</p>
    </div>
    <button @click="openModal = true; editMode = false; form = { kode: '', nama: '', kategori: '', ruang_id: '', jumlah: 1, kondisi: 'baik', merek: '', tahun_peroleh: '', sumber_dana: '', keterangan: '' }; editId = null" class="bg-primary text-on-primary px-6 py-2.5 rounded-lg flex items-center gap-2 font-label-md hover:bg-primary-container transition-all active:scale-95">
        <span class="material-symbols-outlined">add</span>
        Tambah Barang
    </button>
</div>

<div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-surface-container-low">
                <tr>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Kode</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Nama Barang</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Kategori</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Lokasi</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Jumlah</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Kondisi</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant">
                @forelse($semuaBarang as $barang)
                <tr class="hover:bg-surface-container transition-colors">
                    <td class="px-6 py-4 font-mono text-sm font-semibold">{{ $barang->kode }}</td>
                    <td class="px-6 py-4 font-body-md font-semibold">{{ $barang->nama }}</td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-xs font-label-md bg-surface-container-low">{{ $barang->kategori }}</span>
                    </td>
                    <td class="px-6 py-4">{{ $barang->ruang?->nama ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $barang->jumlah }}</td>
                    <td class="px-6 py-4">
                        @if($barang->kondisi === 'baik')
                        <span class="px-3 py-1 rounded-full text-xs font-label-md bg-success/10 text-success">Baik</span>
                        @elseif($barang->kondisi === 'rusak_ringan')
                        <span class="px-3 py-1 rounded-full text-xs font-label-md bg-warning/10 text-warning">Rusak Ringan</span>
                        @else
                        <span class="px-3 py-1 rounded-full text-xs font-label-md bg-error/10 text-error">Rusak Berat</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <button @click="openModal = true; editMode = true; form = { kode: '{{ $barang->kode }}', nama: '{{ $barang->nama }}', kategori: '{{ $barang->kategori }}', ruang_id: '{{ $barang->ruang_id ?? '' }}', jumlah: '{{ $barang->jumlah }}', kondisi: '{{ $barang->kondisi }}', merek: '{{ $barang->merek ?? '' }}', tahun_peroleh: '{{ $barang->tahun_peroleh ?? '' }}', sumber_dana: '{{ $barang->sumber_dana ?? '' }}', keterangan: '{{ addslashes($barang->keterangan ?? '') }}' }; editId = {{ $barang->id }}" class="p-2 hover:bg-surface-container-low rounded-lg transition-colors">
                                <span class="material-symbols-outlined text-outline">edit</span>
                            </button>
                            <form method="POST" action="{{ route('admin.barang.destroy', $barang) }}" @submit.prevent="Swal.fire({title:'Konfirmasi',text:'Yakin ingin menghapus {{ $barang->nama }}?',icon:'warning',showCancelButton:true,confirmButtonText:'Ya, hapus!',cancelButtonText:'Batal'}).then(r=>r.isConfirmed&&$el.submit())">
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
                <tr><td class="px-6 py-8 text-center text-on-surface-variant" colspan="7">Belum ada data barang.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($semuaBarang->hasPages())
    <div class="px-6 py-4 border-t border-outline-variant">
        {{ $semuaBarang->links() }}
    </div>
    @endif
</div>

<div x-show="openModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.away="openModal = false">
    <div class="fixed inset-0 bg-black/40" @click="openModal = false"></div>
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg p-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-headline-sm text-headline-sm" x-text="editMode ? 'Edit Barang' : 'Tambah Barang'"></h3>
            <button @click="openModal = false" class="p-2 hover:bg-surface-container rounded-lg">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form :action="editMode ? '{{ route('admin.barang.update', '__ID__') }}'.replace('__ID__', editId) : '{{ route('admin.barang.store') }}'" method="POST" class="space-y-4">
            @csrf
            <template x-if="editMode">
                <input type="hidden" name="_method" value="PUT">
            </template>
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Kode Barang</label>
                    <input x-model="form.kode" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" placeholder="Contoh: BRG-001" type="text" name="kode" required>
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Nama Barang</label>
                    <input x-model="form.nama" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" placeholder="Nama barang" type="text" name="nama" required>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Kategori</label>
                    <input x-model="form.kategori" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" placeholder="Contoh: Elektronik" type="text" name="kategori" list="daftarKategori" required>
                    <datalist id="daftarKategori">
                        @foreach($daftarKategori as $kat)
                        <option value="{{ $kat }}">
                        @endforeach
                    </datalist>
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Lokasi (Ruang)</label>
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
                    <label class="font-label-md text-label-md text-on-surface-variant">Jumlah</label>
                    <input x-model="form.jumlah" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" type="number" name="jumlah" min="1" required>
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Kondisi</label>
                    <select x-model="form.kondisi" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" name="kondisi" required>
                        <option value="baik">Baik</option>
                        <option value="rusak_ringan">Rusak Ringan</option>
                        <option value="rusak_berat">Rusak Berat</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Merek</label>
                    <input x-model="form.merek" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" placeholder="Merek barang" type="text" name="merek">
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Tahun Peroleh</label>
                    <input x-model="form.tahun_peroleh" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" type="number" name="tahun_peroleh" min="1900" max="2099" placeholder="2024">
                </div>
            </div>
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Sumber Dana</label>
                <input x-model="form.sumber_dana" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" placeholder="Contoh: BOS, APBD, APBN" type="text" name="sumber_dana">
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
