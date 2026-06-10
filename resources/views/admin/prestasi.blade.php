<x-layouts.admin title="Prestasi | EduPortal">
    <x-slot:styles>
    <style>
        [x-cloak] { display: none !important; }
    </style>
    </x-slot:styles>
<div x-data="{ openModal: false, editMode: false, form: { judul: '', deskripsi: '', tingkat: '', peringkat: 'Juara 1', tanggal: '', tipe: 'akademik' }, editId: null }">
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-primary">Prestasi</h2>
        <p class="text-on-surface-variant font-body-md">Kelola prestasi akademik dan non-akademik</p>
    </div>
    <button @click="openModal = true; editMode = false; form = { judul: '', deskripsi: '', tingkat: '', peringkat: 'Juara 1', tanggal: '', tipe: 'akademik' }; editId = null" class="bg-primary text-on-primary px-6 py-2.5 rounded-lg flex items-center gap-2 font-label-md hover:bg-primary-container transition-all active:scale-95">
        <span class="material-symbols-outlined">add</span>
        Tambah Prestasi
    </button>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-gutter">
    @forelse($semuaPrestasi as $prestasi)
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-6 hover:translate-y-[-4px] transition-all duration-300">
        <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 rounded-xl {{ $prestasi->tipe == 'akademik' ? 'bg-primary-fixed text-primary' : 'bg-tertiary-fixed text-on-tertiary-container' }} flex items-center justify-center">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">{{ $prestasi->tipe == 'akademik' ? 'menu_book' : 'sports_score' }}</span>
            </div>
            <div class="flex items-center gap-1">
                <button @click="openModal = true; editMode = true; form = { judul: '{{ $prestasi->judul }}', deskripsi: '{{ $prestasi->deskripsi ?? '' }}', tingkat: '{{ $prestasi->tingkat ?? '' }}', peringkat: '{{ $prestasi->peringkat ?? 'Juara 1' }}', tanggal: '{{ $prestasi->tanggal?->format('Y-m-d') ?? '' }}', tipe: '{{ $prestasi->tipe }}' }; editId = {{ $prestasi->id }}" class="p-1.5 hover:bg-surface-container-low rounded-lg">
                    <span class="material-symbols-outlined text-outline text-sm">edit</span>
                </button>
                <form method="POST" action="{{ route('admin.prestasi.destroy', $prestasi) }}" @submit.prevent="Swal.fire({title:'Konfirmasi',text:'Yakin ingin menghapus prestasi ini?',icon:'warning',showCancelButton:true,confirmButtonText:'Ya, hapus!',cancelButtonText:'Batal'}).then(r=>r.isConfirmed&&$el.submit())">
                    @csrf
                    @method('DELETE')
                    <button class="p-1.5 hover:bg-error/10 rounded-lg">
                        <span class="material-symbols-outlined text-error text-sm">delete</span>
                    </button>
                </form>
            </div>
        </div>
        <div class="mb-2">
            <span class="px-2 py-0.5 rounded text-xs font-bold bg-secondary-fixed text-secondary uppercase">{{ $prestasi->tingkat ?? '-' }}</span>
            <span class="px-2 py-0.5 rounded text-xs font-bold bg-yellow-100 text-yellow-700 ml-1">{{ $prestasi->peringkat ?? '-' }}</span>
        </div>
        <h3 class="font-headline-sm text-headline-sm mb-2">{{ $prestasi->judul }}</h3>
        @if($prestasi->deskripsi)
        <p class="text-sm text-on-surface-variant">{{ $prestasi->deskripsi }}</p>
        @endif
        @if($prestasi->tanggal)
        <p class="text-xs text-outline mt-3">{{ $prestasi->tanggal->isoFormat('D MMMM YYYY') }}</p>
        @endif
    </div>
    @empty
    <div class="lg:col-span-3 bg-white rounded-xl border border-outline-variant card-shadow p-8 text-center">
        <span class="material-symbols-outlined text-5xl text-outline mb-4">military_tech</span>
        <p class="text-on-surface-variant">Belum ada data prestasi.</p>
    </div>
    @endforelse
</div>

<!-- Modal -->
<div x-show="openModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.away="openModal = false">
    <div class="fixed inset-0 bg-black/40" @click="openModal = false"></div>
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg p-8 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-headline-sm text-headline-sm" x-text="editMode ? 'Edit Prestasi' : 'Tambah Prestasi'"></h3>
            <button @click="openModal = false" class="p-2 hover:bg-surface-container rounded-lg"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form :action="editMode ? '{{ route('admin.prestasi.update', '__ID__') }}'.replace('__ID__', editId) : '{{ route('admin.prestasi.store') }}'" method="POST" class="space-y-4">
            @csrf
            <template x-if="editMode"><input type="hidden" name="_method" value="PUT"></template>
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Judul Prestasi</label>
                <input x-model="form.judul" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" placeholder="Contoh: Olimpiade Matematika Nasional" type="text" name="judul" required>
            </div>
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Deskripsi</label>
                <textarea x-model="form.deskripsi" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" placeholder="Deskripsi prestasi (opsional)" rows="3" name="deskripsi"></textarea>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Tingkat</label>
                    <select x-model="form.tingkat" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" name="tingkat">
                        <option value="">Pilih</option>
                        <option value="Sekolah">Sekolah</option>
                        <option value="Kecamatan">Kecamatan</option>
                        <option value="Kota">Kota</option>
                        <option value="Provinsi">Provinsi</option>
                        <option value="Nasional">Nasional</option>
                        <option value="Internasional">Internasional</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Peringkat</label>
                    <select x-model="form.peringkat" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" name="peringkat">
                        <option value="Juara 1">Juara 1</option>
                        <option value="Juara 2">Juara 2</option>
                        <option value="Juara 3">Juara 3</option>
                        <option value="Harapan">Harapan</option>
                        <option value="Finalis">Finalis</option>
                        <option value="Peserta">Peserta</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Tanggal</label>
                    <input x-model="form.tanggal" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" type="date" name="tanggal">
                </div>
            </div>
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Tipe</label>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="tipe" value="akademik" x-model="form.tipe" class="text-primary focus:ring-primary">
                        <span class="font-body-md">Akademik</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="tipe" value="non-akademik" x-model="form.tipe" class="text-primary focus:ring-primary">
                        <span class="font-body-md">Non-Akademik</span>
                    </label>
                </div>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" @click="openModal = false" class="flex-1 py-3 border border-outline-variant rounded-xl font-label-md hover:bg-surface-container">Batal</button>
                <button type="submit" class="flex-1 py-3 bg-primary text-on-primary rounded-xl font-label-md hover:bg-primary-container" x-text="editMode ? 'Simpan' : 'Tambah'"></button>
            </div>
        </form>
    </div>
    </div>
</div>
</x-layouts.admin>
