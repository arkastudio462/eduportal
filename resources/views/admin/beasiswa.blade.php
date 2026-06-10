<x-layouts.admin title="Beasiswa | EduPortal">
    <x-slot:styles>
    <style>
        [x-cloak] { display: none !important; }
    </style>
    </x-slot:styles>

@php
$totalBeasiswa = \App\Models\Beasiswa::count();
$totalPenerima = \App\Models\Beasiswa::distinct('siswa_id')->count('siswa_id');
$tahunMin = \App\Models\Beasiswa::min('tahun');
$tahunMax = \App\Models\Beasiswa::max('tahun');
$semuaSiswaAktif = \App\Models\Siswa::aktif()->with('user')->get();
@endphp

<div x-data="{
    openModal: false,
    editMode: false,
    deleteModal: false,
    deleteUrl: '',
    filterKelas: '',
    form: {
        siswa_id: '',
        nama_beasiswa: '',
        penyelenggara: '',
        tahun: '',
        keterangan: '',
    },
    editId: null
}">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="font-headline-lg text-headline-lg text-primary">Beasiswa & Prestasi Non-Akademik</h2>
            <p class="text-on-surface-variant font-body-md">Kelola data beasiswa dan prestasi non-akademik siswa</p>
        </div>
        <button @click="openModal = true; editMode = false; form = { siswa_id: '', nama_beasiswa: '', penyelenggara: '', tahun: '', keterangan: '' }; editId = null; filterKelas = ''" class="bg-primary text-on-primary px-6 py-2.5 rounded-lg flex items-center gap-2 font-label-md hover:bg-primary-container transition-all active:scale-95">
            <span class="material-symbols-outlined">add</span>
            Tambah Beasiswa
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-gutter mb-6">
        <div class="bg-white rounded-xl border border-outline-variant card-shadow p-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-primary-fixed text-primary flex items-center justify-center">
                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">school</span>
                </div>
                <div>
                    <p class="text-sm text-on-surface-variant">Total Beasiswa</p>
                    <p class="font-headline-sm text-headline-sm">{{ $totalBeasiswa }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-outline-variant card-shadow p-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-tertiary-fixed text-on-tertiary-container flex items-center justify-center">
                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">groups</span>
                </div>
                <div>
                    <p class="text-sm text-on-surface-variant">Total Penerima</p>
                    <p class="font-headline-sm text-headline-sm">{{ $totalPenerima }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-outline-variant card-shadow p-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-secondary-fixed text-secondary flex items-center justify-center">
                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">date_range</span>
                </div>
                <div>
                    <p class="text-sm text-on-surface-variant">Rentang Tahun</p>
                    <p class="font-headline-sm text-headline-sm">{{ $tahunMin ? "$tahunMin - $tahunMax" : '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.beasiswa') }}" class="bg-white p-4 md:p-6 rounded-xl border border-outline-variant card-shadow mb-6">
        <div class="flex items-end gap-4 flex-wrap">
            <div class="w-full sm:w-48">
                <label class="font-label-sm text-label-sm text-outline mb-1 block">Tahun</label>
                <select name="tahun" class="w-full px-3 py-2 border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none bg-white">
                    <option value="">Semua Tahun</option>
                    @foreach($tahunList as $tahun)
                    <option value="{{ $tahun }}" {{ request('tahun') == $tahun ? 'selected' : '' }}>{{ $tahun }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-secondary-container text-on-secondary-container rounded-lg font-label-md hover:bg-secondary-container/70 transition-all flex items-center gap-1">
                <span class="material-symbols-outlined text-sm">filter_alt</span>
                Tampilkan
            </button>
        </div>
    </form>

    <div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-surface-container-low">
                    <tr>
                        <th class="px-6 py-4 font-label-md text-on-surface-variant w-12">No</th>
                        <th class="px-6 py-4 font-label-md text-on-surface-variant">Nama Siswa</th>
                        <th class="px-6 py-4 font-label-md text-on-surface-variant">Kelas</th>
                        <th class="px-6 py-4 font-label-md text-on-surface-variant">Nama Beasiswa</th>
                        <th class="px-6 py-4 font-label-md text-on-surface-variant">Penyelenggara</th>
                        <th class="px-6 py-4 font-label-md text-on-surface-variant">Tahun</th>
                        <th class="px-6 py-4 font-label-md text-on-surface-variant">File</th>
                        <th class="px-6 py-4 font-label-md text-on-surface-variant">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse($semuaBeasiswa as $beasiswa)
                    <tr class="hover:bg-surface-container transition-colors">
                        <td class="px-6 py-4 text-sm text-outline">{{ $loop->iteration + ($semuaBeasiswa->currentPage() - 1) * $semuaBeasiswa->perPage() }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-primary-fixed text-primary rounded-full flex items-center justify-center font-bold text-xs">{{ strtoupper(substr($beasiswa->siswa?->user?->name ?? '?', 0, 1)) }}</div>
                                <span class="font-body-md">{{ $beasiswa->siswa?->user?->name ?? '-' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">{{ $beasiswa->siswa?->kelas?->nama ?? '-' }}</td>
                        <td class="px-6 py-4 font-medium">{{ $beasiswa->nama_beasiswa }}</td>
                        <td class="px-6 py-4">{{ $beasiswa->penyelenggara }}</td>
                        <td class="px-6 py-4">{{ $beasiswa->tahun }}</td>
                        <td class="px-6 py-4">
                            @if($beasiswa->file)
                            <a href="{{ $beasiswa->file }}" target="_blank" class="text-primary hover:text-primary-fixed-dim flex items-center gap-1 text-sm" title="Download/ Lihat File">
                                <span class="material-symbols-outlined text-sm">download</span>
                                File
                            </a>
                            @else
                            <span class="text-outline">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-1">
                                <button @click="openModal = true; editMode = true; form = { siswa_id: '{{ $beasiswa->siswa_id }}', nama_beasiswa: '{{ $beasiswa->nama_beasiswa }}', penyelenggara: '{{ $beasiswa->penyelenggara }}', tahun: '{{ $beasiswa->tahun }}', keterangan: '{{ $beasiswa->keterangan ?? '' }}' }; editId = {{ $beasiswa->id }}" class="p-1.5 hover:bg-surface-container rounded transition-colors" title="Edit">
                                    <span class="material-symbols-outlined text-outline text-sm">edit</span>
                                </button>
                                <button @click="deleteUrl = '{{ route('admin.beasiswa.destroy', $beasiswa) }}'; deleteModal = true" class="p-1.5 hover:bg-surface-container rounded transition-colors" title="Hapus">
                                    <span class="material-symbols-outlined text-outline text-sm">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="px-6 py-12 text-center">
                        <span class="material-symbols-outlined text-4xl text-outline mb-3 block">school</span>
                        <p class="text-on-surface-variant">Belum ada data beasiswa.</p>
                    </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($semuaBeasiswa->hasPages())
        <div class="px-6 py-4 border-t border-outline-variant flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-sm text-on-surface-variant">Menampilkan {{ $semuaBeasiswa->firstItem() ?? 0 }}-{{ $semuaBeasiswa->lastItem() ?? 0 }} dari {{ $semuaBeasiswa->total() }} data</p>
            {{ $semuaBeasiswa->links() }}
        </div>
        @endif
    </div>

    <div x-show="openModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.away="openModal = false">
        <div class="fixed inset-0 bg-black/40" @click="openModal = false"></div>
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg p-8 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-headline-sm text-headline-sm" x-text="editMode ? 'Edit Beasiswa' : 'Tambah Beasiswa'"></h3>
                <button @click="openModal = false" class="p-2 hover:bg-surface-container rounded-lg">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form :action="editMode ? '{{ route('admin.beasiswa.update', '__ID__') }}'.replace('__ID__', editId) : '{{ route('admin.beasiswa.store') }}'" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <template x-if="!editMode">
                    <div class="space-y-4">
                        <div class="space-y-2">
                            <label class="font-label-md text-label-md text-on-surface-variant">Kelas</label>
                            <select x-model="filterKelas" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary">
                                <option value="">Semua Kelas</option>
                                @foreach($kelasList as $kelas)
                                <option value="{{ $kelas->id }}">{{ $kelas->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="font-label-md text-label-md text-on-surface-variant">Siswa</label>
                            <select name="siswa_id" x-model="form.siswa_id" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" required>
                                <option value="">Pilih Siswa</option>
                                @foreach($semuaSiswaAktif as $siswa)
                                <option value="{{ $siswa->id }}" x-show="!filterKelas || filterKelas == '{{ $siswa->kelas_id }}'">{{ $siswa->user->name }} ({{ $siswa->nisn }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </template>

                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Nama Beasiswa</label>
                    <input x-model="form.nama_beasiswa" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" placeholder="Contoh: Beasiswa Prestasi Unggulan" type="text" name="nama_beasiswa" required>
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Penyelenggara</label>
                    <input x-model="form.penyelenggara" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" placeholder="Contoh: Kemendikbud" type="text" name="penyelenggara" required>
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Tahun</label>
                    <input x-model="form.tahun" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" placeholder="2025" type="number" name="tahun" min="2000" max="2099" required>
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Keterangan</label>
                    <textarea x-model="form.keterangan" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" placeholder="Keterangan (opsional)" rows="3" name="keterangan"></textarea>
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">
                        File
                        <span class="text-outline text-label-sm">(PDF, JPG, PNG — maks 2MB)</span>
                    </label>
                    <input type="file" name="file" accept=".pdf,.jpg,.jpeg,.png" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2 text-body-md file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-primary file:text-on-primary file:font-label-md file:cursor-pointer hover:file:bg-primary-container">
                    <p x-show="editMode" class="text-sm text-outline mt-1">Kosongkan jika tidak ingin mengganti file.</p>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" @click="openModal = false" class="flex-1 py-3 border border-outline-variant rounded-xl font-label-md hover:bg-surface-container transition-all">Batal</button>
                    <button type="submit" class="flex-1 py-3 bg-primary text-on-primary rounded-xl font-label-md hover:bg-primary-container transition-all" x-text="editMode ? 'Simpan' : 'Tambah'"></button>
                </div>
            </form>
        </div>
    </div>

    <div x-show="deleteModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.away="deleteModal = false">
        <div class="fixed inset-0 bg-black/40" @click="deleteModal = false"></div>
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <span class="material-symbols-outlined text-red-600 text-2xl">delete_forever</span>
                </div>
                <div>
                    <h3 class="font-headline-sm text-headline-sm">Konfirmasi Hapus</h3>
                    <p class="text-sm text-gray-500 mt-1">Apakah Anda yakin ingin menghapus data beasiswa ini? Tindakan ini tidak dapat dibatalkan.</p>
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
</x-layouts.admin>
