<x-layouts.admin title="Remedial & Pengayaan | EduPortal">
    <x-slot:styles>
    <style>[x-cloak] { display: none !important; }</style>
    </x-slot:styles>
<div x-data="{
    openModal: false,
    editMode: false,
    form: {
        kelas_id: '',
        siswa_id: '',
        mapel_id: '',
        jenis: 'remedial',
        nilai_awal: '',
        nilai_akhir: '',
        tanggal: '{{ date('Y-m-d') }}',
        semester: '{{ $semesterList->first() ?? (now()->year . '/' . (now()->year + 1) . ' Ganjil') }}',
        keterangan: ''
    },
    editId: null,
    daftarSiswa: [],
    loadingSiswa: false,
    async loadSiswa(kelasId) {
        if (!kelasId) { this.daftarSiswa = []; return; }
        this.loadingSiswa = true;
        try {
            const res = await fetch('{{ route('admin.remedial.get-siswa') }}?kelas_id=' + kelasId);
            this.daftarSiswa = await res.json();
        } catch(e) { this.daftarSiswa = []; }
        finally { this.loadingSiswa = false; }
    },
    resetForm() {
        this.form = {
            kelas_id: '',
            siswa_id: '',
            mapel_id: '',
            jenis: 'remedial',
            nilai_awal: '',
            nilai_akhir: '',
            tanggal: '{{ date('Y-m-d') }}',
            semester: '{{ $semesterList->first() ?? (now()->year . '/' . (now()->year + 1) . ' Ganjil') }}',
            keterangan: ''
        };
        this.daftarSiswa = [];
        this.editId = null;
    }
}">
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-primary">Remedial & Pengayaan</h2>
        <p class="text-on-surface-variant font-body-md">Kelola remedial dan pengayaan siswa</p>
    </div>
    <div class="flex gap-3">
        <button @click="openModal = true; editMode = false; resetForm()" class="px-4 py-2.5 bg-primary text-on-primary rounded-lg flex items-center gap-2 font-label-md hover:bg-primary-container transition-all active:scale-95">
            <span class="material-symbols-outlined">add</span>
            Tambah Data
        </button>
    </div>
</div>

{{-- Stats --}}
<div class="grid grid-cols-3 gap-gutter mb-6">
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-red-100 text-red-700 flex items-center justify-center"><span class="material-symbols-outlined" style="font-variation-settings:'FILL'1">replay</span></div>
        <div><p class="text-xs text-on-surface-variant">Total Remedial</p><h3 class="font-headline-md text-headline-md text-primary">{{ $totalRemedial ?? 0 }}</h3></div>
    </div>
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-green-100 text-green-700 flex items-center justify-center"><span class="material-symbols-outlined" style="font-variation-settings:'FILL'1">upgrade</span></div>
        <div><p class="text-xs text-on-surface-variant">Total Pengayaan</p><h3 class="font-headline-md text-headline-md text-primary">{{ $totalPengayaan ?? 0 }}</h3></div>
    </div>
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center"><span class="material-symbols-outlined" style="font-variation-settings:'FILL'1">trending_up</span></div>
        <div><p class="text-xs text-on-surface-variant">Rata-rata Peningkatan</p><h3 class="font-headline-md text-headline-md text-primary">{{ number_format($rataPeningkatan ?? 0, 1) }}</h3></div>
    </div>
</div>

{{-- Filters --}}
<form method="GET" class="bg-white rounded-xl border border-outline-variant card-shadow p-5 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="font-label-md text-label-md text-on-surface-variant mb-1 block">Kelas</label>
            <select name="kelas_id" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" onchange="this.form.submit()">
                <option value="">Semua Kelas</option>
                @foreach($kelasList as $kelas)
                <option value="{{ $kelas->id }}" {{ ($filterKelasId ?? '') == $kelas->id ? 'selected' : '' }}>{{ $kelas->nama }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="font-label-md text-label-md text-on-surface-variant mb-1 block">Mata Pelajaran</label>
            <select name="mapel_id" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" onchange="this.form.submit()">
                <option value="">Semua Mapel</option>
                @foreach($mapelList as $mapel)
                <option value="{{ $mapel->id }}" {{ ($filterMapelId ?? '') == $mapel->id ? 'selected' : '' }}>{{ $mapel->nama }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="font-label-md text-label-md text-on-surface-variant mb-1 block">Jenis</label>
            <select name="jenis" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" onchange="this.form.submit()">
                <option value="">Semua Jenis</option>
                <option value="remedial" {{ ($filterJenis ?? '') == 'remedial' ? 'selected' : '' }}>Remedial</option>
                <option value="pengayaan" {{ ($filterJenis ?? '') == 'pengayaan' ? 'selected' : '' }}>Pengayaan</option>
            </select>
        </div>
        <div>
            <label class="font-label-md text-label-md text-on-surface-variant mb-1 block">Semester</label>
            <select name="semester" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" onchange="this.form.submit()">
                <option value="">Semua Semester</option>
                @foreach($semesterList as $sem)
                <option value="{{ $sem }}" {{ ($filterSemester ?? '') == $sem ? 'selected' : '' }}>{{ $sem }}</option>
                @endforeach
            </select>
        </div>
    </div>
</form>

{{-- Table --}}
<div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-surface-container-low">
                <tr>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">No</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Nama Siswa</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Kelas</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Mapel</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Jenis</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Nilai Awal</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Nilai Akhir</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Tanggal</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Keterangan</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant">
                @forelse($semuaRemedial as $i => $remedial)
                <tr class="hover:bg-surface-container transition-colors">
                    <td class="px-6 py-4 font-body-md">{{ $semuaRemedial->firstItem() + $i }}</td>
                    <td class="px-6 py-4 font-body-md font-semibold">{{ $remedial->siswa->user->name ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $remedial->siswa->kelas->nama ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $remedial->mapel->nama ?? '-' }}</td>
                    <td class="px-6 py-4">
                        @if($remedial->jenis == 'remedial')
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">Remedial</span>
                        @else
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">Pengayaan</span>
                        @endif
                    </td>
                    <td class="px-6 py-4"><span class="font-semibold text-red-600">{{ number_format($remedial->nilai_awal, 1) }}</span></td>
                    <td class="px-6 py-4"><span class="font-semibold text-green-600">{{ number_format($remedial->nilai_akhir, 1) }}</span></td>
                    <td class="px-6 py-4 text-body-sm">{{ \Carbon\Carbon::parse($remedial->tanggal)->format('d/m/Y') }}</td>
                    <td class="px-6 py-4 text-body-sm max-w-[150px] truncate">{{ $remedial->keterangan ?? '-' }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <button @click="openModal = true; editMode = true; form = { kelas_id: '{{ $remedial->siswa->kelas_id }}', siswa_id: '{{ $remedial->siswa_id }}', mapel_id: '{{ $remedial->mapel_id }}', jenis: '{{ $remedial->jenis }}', nilai_awal: '{{ $remedial->nilai_awal }}', nilai_akhir: '{{ $remedial->nilai_akhir }}', tanggal: '{{ $remedial->tanggal }}', semester: '{{ $remedial->semester }}', keterangan: '{{ addslashes($remedial->keterangan) }}' }; editId = {{ $remedial->id }}; loadSiswa({{ $remedial->siswa->kelas_id }})" class="p-2 hover:bg-surface-container-low rounded-lg">
                                <span class="material-symbols-outlined text-outline">edit</span>
                            </button>
                            <form method="POST" action="{{ route('admin.remedial.destroy', $remedial) }}" @submit.prevent="Swal.fire({title:'Konfirmasi',text:'Yakin ingin menghapus data ini?',icon:'warning',showCancelButton:true,confirmButtonText:'Ya, hapus!',cancelButtonText:'Batal'}).then(r=>r.isConfirmed&&$el.submit())">
                                @csrf
                                @method('DELETE')
                                <button class="p-2 hover:bg-error/10 rounded-lg"><span class="material-symbols-outlined text-error">delete</span></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td class="px-6 py-8 text-center text-on-surface-variant" colspan="10">Belum ada data remedial & pengayaan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($semuaRemedial->hasPages())
    <div class="p-4 border-t border-outline-variant">{{ $semuaRemedial->links('vendor.pagination.custom') }}</div>
    @endif
</div>

{{-- Modal --}}
<div x-show="openModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.away="openModal = false">
    <div class="fixed inset-0 bg-black/40" @click="openModal = false"></div>
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg p-8 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-headline-sm text-headline-sm" x-text="editMode ? 'Edit Data' : 'Tambah Data'"></h3>
            <button @click="openModal = false" class="p-2 hover:bg-surface-container rounded-lg"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form :action="editMode ? '{{ route('admin.remedial.update', '__ID__') }}'.replace('__ID__', editId) : '{{ route('admin.remedial.store') }}'" method="POST" class="space-y-5">
            @csrf
            <template x-if="editMode"><input type="hidden" name="_method" value="PUT"></template>

            {{-- Create-only fields --}}
            <template x-if="!editMode">
                <div class="space-y-5">
                    <div class="space-y-2">
                        <label class="font-label-md text-label-md text-on-surface-variant">Kelas</label>
                        <select x-model="form.kelas_id" @change="form.siswa_id = ''; loadSiswa($event.target.value)" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" name="kelas_id" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelasList as $kelas)
                            <option value="{{ $kelas->id }}">{{ $kelas->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="font-label-md text-label-md text-on-surface-variant">Siswa</label>
                        <select x-model="form.siswa_id" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" name="siswa_id" required>
                            <option value="">-- Pilih Siswa --</option>
                            <template x-for="siswa in daftarSiswa" :key="siswa.id">
                                <option :value="siswa.id" x-text="siswa.nama"></option>
                            </template>
                        </select>
                        <p x-show="loadingSiswa" class="text-xs text-outline">Memuat data siswa...</p>
                    </div>
                    <div class="space-y-2">
                        <label class="font-label-md text-label-md text-on-surface-variant">Mata Pelajaran</label>
                        <select x-model="form.mapel_id" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" name="mapel_id" required>
                            <option value="">-- Pilih Mapel --</option>
                            @foreach($mapelList as $mapel)
                            <option value="{{ $mapel->id }}">{{ $mapel->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="font-label-md text-label-md text-on-surface-variant">Semester</label>
                        <input x-model="form.semester" type="text" name="semester" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" placeholder="2023/2024 Ganjil" required>
                    </div>
                </div>
            </template>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Jenis</label>
                    <select x-model="form.jenis" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" name="jenis" required>
                        <option value="remedial">Remedial</option>
                        <option value="pengayaan">Pengayaan</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Tanggal</label>
                    <input x-model="form.tanggal" type="date" name="tanggal" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" required>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Nilai Awal</label>
                    <input x-model="form.nilai_awal" type="number" name="nilai_awal" min="0" max="100" step="0.1" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" required>
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Nilai Akhir</label>
                    <input x-model="form.nilai_akhir" type="number" name="nilai_akhir" min="0" max="100" step="0.1" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" required>
                </div>
            </div>

            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Keterangan</label>
                <textarea x-model="form.keterangan" name="keterangan" rows="3" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary resize-none" placeholder="Opsional"></textarea>
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
