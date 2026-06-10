<x-layouts.admin title="Mutasi Siswa | EduPortal">
    <x-slot:styles>
    <style>
        [x-cloak] { display: none !important; }
    </style>
    </x-slot:styles>
<div x-data="{ openModal: false, form: { kelas_id: '', siswa_id: '', jenis: 'masuk', tanggal: '{{ date('Y-m-d') }}', sekolah_asal: '', sekolah_tujuan: '', alasan: '', keterangan: '' }, daftarSiswa: [], loadSiswa() { if (!this.form.kelas_id) { this.daftarSiswa = []; return; } fetch('{{ route('admin.mutasi-siswa.get-siswa') }}?kelas_id=' + this.form.kelas_id).then(r => r.json()).then(d => { this.daftarSiswa = d; this.form.siswa_id = ''; }); } }">
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-primary">Mutasi Siswa</h2>
        <p class="text-on-surface-variant font-body-md">Catatan mutasi masuk, keluar, dan pindah</p>
    </div>
    <button @click="openModal = true; form = { kelas_id: '', siswa_id: '', jenis: 'masuk', tanggal: '{{ date('Y-m-d') }}', sekolah_asal: '', sekolah_tujuan: '', alasan: '', keterangan: '' }; daftarSiswa = []" class="px-4 py-2.5 bg-primary text-on-primary rounded-lg flex items-center gap-2 font-label-md hover:bg-primary-container transition-all active:scale-95">
        <span class="material-symbols-outlined">add</span>
        Tambah Mutasi
    </button>
</div>

@php
$total = $semuaMutasi->total();
$masuk = $semuaMutasi->where('jenis', 'masuk')->count();
$keluar = $semuaMutasi->where('jenis', 'keluar')->count();
$pindah = $semuaMutasi->where('jenis', 'pindah')->count();
@endphp

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-outline-variant p-5 card-shadow">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-primary-fixed text-primary rounded-lg flex items-center justify-center">
                <span class="material-symbols-outlined">swap_horiz</span>
            </div>
            <div>
                <p class="text-2xl font-bold font-headline-sm text-primary">{{ $total }}</p>
                <p class="text-sm text-on-surface-variant">Total Mutasi</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-outline-variant p-5 card-shadow">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-green-100 text-green-700 rounded-lg flex items-center justify-center">
                <span class="material-symbols-outlined">login</span>
            </div>
            <div>
                <p class="text-2xl font-bold font-headline-sm text-green-700">{{ $semuaMutasi->where('jenis', 'masuk')->count() }}</p>
                <p class="text-sm text-on-surface-variant">Masuk</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-outline-variant p-5 card-shadow">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-red-100 text-red-700 rounded-lg flex items-center justify-center">
                <span class="material-symbols-outlined">logout</span>
            </div>
            <div>
                <p class="text-2xl font-bold font-headline-sm text-red-700">{{ $semuaMutasi->where('jenis', 'keluar')->count() }}</p>
                <p class="text-sm text-on-surface-variant">Keluar</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-outline-variant p-5 card-shadow">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-amber-100 text-amber-700 rounded-lg flex items-center justify-center">
                <span class="material-symbols-outlined">transfer_within_a_station</span>
            </div>
            <div>
                <p class="text-2xl font-bold font-headline-sm text-amber-700">{{ $semuaMutasi->where('jenis', 'pindah')->count() }}</p>
                <p class="text-sm text-on-surface-variant">Pindah</p>
            </div>
        </div>
    </div>
</div>

<!-- Filter -->
<form method="GET" action="{{ route('admin.mutasi-siswa') }}" class="mb-6">
    <div class="flex items-center gap-4">
        <div class="w-48">
            <select name="jenis" class="w-full px-3 py-2 border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none bg-white" onchange="this.form.submit()">
                <option value="">Semua Jenis</option>
                <option value="masuk" {{ request('jenis') == 'masuk' ? 'selected' : '' }}>Masuk</option>
                <option value="keluar" {{ request('jenis') == 'keluar' ? 'selected' : '' }}>Keluar</option>
                <option value="pindah" {{ request('jenis') == 'pindah' ? 'selected' : '' }}>Pindah</option>
            </select>
        </div>
        @if(request('jenis'))
        <a href="{{ route('admin.mutasi-siswa') }}" class="text-sm text-secondary hover:underline">Reset filter</a>
        @endif
    </div>
</form>

<!-- Table -->
<div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-surface-container-low">
                <tr>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant w-12">No</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Nama Siswa</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">NISN</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Jenis</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Tanggal</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Sekolah Asal/Tujuan</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Alasan</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant">
                @forelse($semuaMutasi as $mutasi)
                <tr class="hover:bg-surface-container transition-colors">
                    <td class="px-6 py-4 font-body-md text-on-surface-variant">{{ $loop->iteration + ($semuaMutasi->currentPage() - 1) * $semuaMutasi->perPage() }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-primary-fixed text-primary rounded-full flex items-center justify-center font-bold text-xs">{{ substr($mutasi->siswa->user->name, 0, 1) }}</div>
                            <span class="font-body-md font-semibold">{{ $mutasi->siswa->user->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 font-body-md">{{ $mutasi->siswa->nisn }}</td>
                    <td class="px-6 py-4">
                        @php
                        $badge = match($mutasi->jenis) {
                            'masuk' => 'bg-green-100 text-green-700',
                            'keluar' => 'bg-red-100 text-red-700',
                            'pindah' => 'bg-amber-100 text-amber-700',
                            default => 'bg-surface-container text-outline'
                        };
                        @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase {{ $badge }}">{{ $mutasi->jenis }}</span>
                    </td>
                    <td class="px-6 py-4 font-body-md">{{ $mutasi->tanggal->format('d/m/Y') }}</td>
                    <td class="px-6 py-4 font-body-md text-on-surface-variant">
                        @if($mutasi->sekolah_asal)
                            <span class="text-xs text-outline">Asal:</span> {{ $mutasi->sekolah_asal }}
                        @elseif($mutasi->sekolah_tujuan)
                            <span class="text-xs text-outline">Tujuan:</span> {{ $mutasi->sekolah_tujuan }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-6 py-4 font-body-md text-on-surface-variant max-w-[200px] truncate">{{ $mutasi->alasan ?? '-' }}</td>
                    <td class="px-6 py-4">
                        <form method="POST" action="{{ route('admin.mutasi-siswa.destroy', $mutasi->id) }}" @submit.prevent="Swal.fire({title:'Konfirmasi',text:'Yakin ingin menghapus data mutasi ini?',icon:'warning',showCancelButton:true,confirmButtonText:'Ya, hapus!',cancelButtonText:'Batal'}).then(r=>r.isConfirmed&&$el.submit())">
                            @csrf
                            @method('DELETE')
                            <button class="p-2 hover:bg-error/10 rounded-lg transition-colors" title="Hapus">
                                <span class="material-symbols-outlined text-error">delete</span>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td class="px-6 py-8 text-center text-on-surface-variant" colspan="8">Belum ada data mutasi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($semuaMutasi->hasPages())
    <div class="px-6 py-4 border-t border-outline-variant">
        {{ $semuaMutasi->links() }}
    </div>
    @endif
</div>

<!-- Modal Tambah Mutasi -->
<div x-show="openModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.away="openModal = false">
    <div class="fixed inset-0 bg-black/40" @click="openModal = false"></div>
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg p-8 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-headline-sm text-headline-sm">Tambah Mutasi</h3>
            <button @click="openModal = false" class="p-2 hover:bg-surface-container rounded-lg">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.mutasi-siswa.store') }}" class="space-y-5">
            @csrf
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Kelas</label>
                <select x-model="form.kelas_id" @change="loadSiswa()" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" name="kelas_id" required>
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($kelasList as $kelas)
                    <option value="{{ $kelas->id }}">{{ $kelas->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Siswa</label>
                <select x-model="form.siswa_id" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" name="siswa_id" required>
                    <option value="">-- Pilih Siswa --</option>
                    <template x-for="siswa in daftarSiswa" :key="siswa.id">
                        <option :value="siswa.id" x-text="siswa.nama + ' (' + siswa.nisn + ')'"></option>
                    </template>
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Jenis Mutasi</label>
                    <select x-model="form.jenis" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" name="jenis" required>
                        <option value="masuk">Masuk</option>
                        <option value="keluar">Keluar</option>
                        <option value="pindah">Pindah</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Tanggal</label>
                    <input x-model="form.tanggal" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" type="date" name="tanggal" required>
                </div>
            </div>
            <div class="space-y-2" x-show="form.jenis === 'masuk'">
                <label class="font-label-md text-label-md text-on-surface-variant">Sekolah Asal</label>
                <input x-model="form.sekolah_asal" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" placeholder="Nama sekolah asal" type="text" name="sekolah_asal">
            </div>
            <div class="space-y-2" x-show="form.jenis === 'keluar' || form.jenis === 'pindah'">
                <label class="font-label-md text-label-md text-on-surface-variant">Sekolah Tujuan</label>
                <input x-model="form.sekolah_tujuan" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" placeholder="Nama sekolah tujuan" type="text" name="sekolah_tujuan">
            </div>
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Alasan</label>
                <textarea x-model="form.alasan" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" placeholder="Alasan mutasi" name="alasan" rows="2"></textarea>
            </div>
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Keterangan</label>
                <textarea x-model="form.keterangan" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" placeholder="Keterangan tambahan" name="keterangan" rows="2"></textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" @click="openModal = false" class="flex-1 py-3 border border-outline-variant rounded-xl font-label-md hover:bg-surface-container transition-all">Batal</button>
                <button type="submit" class="flex-1 py-3 bg-primary text-on-primary rounded-xl font-label-md hover:bg-primary-container transition-all">Simpan</button>
            </div>
        </form>
    </div>
</div>
</div>
</x-layouts.admin>
