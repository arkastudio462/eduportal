<x-layouts.admin title="Kepegawaian | EduPortal">
    <x-slot:styles>
    <style>[x-cloak] { display: none !important; }</style>
    </x-slot:styles>

@php
    $golonganList = ['I/a','I/b','II/a','II/b','II/c','II/d','III/a','III/b','III/c','III/d','IV/a','IV/b','IV/c','IV/d','IV/e'];
    $jabatanList = ['Kepala Sekolah','Wakil Kepala Sekolah','Guru Kelas','Guru Mapel','Guru BK','Staff TU','Staff Lainnya'];
    $jenisCuti = ['tahunan' => 'Cuti Tahunan','sakit' => 'Cuti Sakit','melahirkan' => 'Cuti Melahirkan','besar' => 'Cuti Besar','alasan_penting' => 'Cuti Alasan Penting','dinas_luar' => 'Cuti Dinas Luar'];
    $jenisSertifikasi = ['pendidik' => 'Sertifikasi Pendidik','profesi' => 'Sertifikasi Profesi','keahlian' => 'Sertifikasi Keahlian'];
    $jenisTunjangan = ['sertifikasi' => 'Tunjangan Sertifikasi','fungsional' => 'Tunjangan Fungsional','struktural' => 'Tunjangan Struktural','khusus' => 'Tunjangan Khusus','insentif' => 'Tunjangan Insentif'];
    $bulanList = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    $predikatPkg = ['Amat Baik','Baik','Cukup','Kurang'];
    $kategoriKinerja = ['guru_kelas' => 'Guru Kelas','guru_mapel' => 'Guru Mapel','guru_bk' => 'Guru BK','lainnya' => 'Lainnya'];
    $statusCuti = ['pending' => ['bg-amber-100 text-amber-700', 'Pending'], 'disetujui' => ['bg-green-100 text-green-700', 'Disetujui'], 'ditolak' => ['bg-red-100 text-red-700', 'Ditolak']];
    $statusTunjangan = ['dibayarkan' => ['bg-green-100 text-green-700', 'Dibayarkan'], 'menunggu' => ['bg-amber-100 text-amber-700', 'Menunggu'], 'ditangguhkan' => ['bg-red-100 text-red-700', 'Ditangguhkan']];
@endphp

<div x-data="{
    tab: 'kepegawaian',
    openModal: false,
    editMode: false,
    editId: null,
    deleteModal: false,
    deleteUrl: '',
    formKepegawaian: { guru_id: '', status_kepegawaian: 'Non-PNS/GTT/PTT', golongan: '', jabatan: '', tmt_cpns: '', tmt_pns: '', masa_kerja_tahun: '', masa_kerja_bulan: '', nik: '', npwp: '', karpeg: '' },
    formCuti: { guru_id: '', jenis_cuti: 'tahunan', tanggal_mulai: '', tanggal_selesai: '', alasan: '', status: 'pending', file: null },
    formKinerja: { guru_id: '', tahun_ajaran: '', semester: '', jam_mengajar_per_minggu: '', skor_pkg: '', predikat_pkg: '', kategori: 'guru_mapel', catatan: '' },
    formSertifikasi: { guru_id: '', jenis_sertifikasi: 'pendidik', nomor_sertifikat: '', tahun_sertifikasi: '', bidang_studi: '', penerbit: '', file: null },
    formTunjangan: { guru_id: '', jenis_tunjangan: 'sertifikasi', besaran: '', periode_bulan: '', periode_tahun: '', status: 'menunggu', tanggal_bayar: '', keterangan: '' },
    resetForms() {
        this.formKepegawaian = { guru_id: '', status_kepegawaian: 'Non-PNS/GTT/PTT', golongan: '', jabatan: '', tmt_cpns: '', tmt_pns: '', masa_kerja_tahun: '', masa_kerja_bulan: '', nik: '', npwp: '', karpeg: '' };
        this.formCuti = { guru_id: '', jenis_cuti: 'tahunan', tanggal_mulai: '', tanggal_selesai: '', alasan: '', status: 'pending', file: null };
        this.formKinerja = { guru_id: '', tahun_ajaran: '', semester: '', jam_mengajar_per_minggu: '', skor_pkg: '', predikat_pkg: '', kategori: 'guru_mapel', catatan: '' };
        this.formSertifikasi = { guru_id: '', jenis_sertifikasi: 'pendidik', nomor_sertifikat: '', tahun_sertifikasi: '', bidang_studi: '', penerbit: '', file: null };
        this.formTunjangan = { guru_id: '', jenis_tunjangan: 'sertifikasi', besaran: '', periode_bulan: '', periode_tahun: '', status: 'menunggu', tanggal_bayar: '', keterangan: '' };
        this.editMode = false;
        this.editId = null;
    },
    openCreateModal() {
        this.resetForms();
        this.openModal = true;
    },
    getSubmitAction() {
        const routes = {
            kepegawaian: this.editMode
                ? '{{ route('admin.kepegawaian.update-kepegawaian', '__ID__') }}'.replace('__ID__', this.editId)
                : '{{ route('admin.kepegawaian.store-kepegawaian') }}',
            cuti: this.editMode
                ? '{{ route('admin.kepegawaian.update-cuti', '__ID__') }}'.replace('__ID__', this.editId)
                : '{{ route('admin.kepegawaian.store-cuti') }}',
            kinerja: this.editMode
                ? '{{ route('admin.kepegawaian.update-kinerja', '__ID__') }}'.replace('__ID__', this.editId)
                : '{{ route('admin.kepegawaian.store-kinerja') }}',
            sertifikasi: this.editMode
                ? '{{ route('admin.kepegawaian.update-sertifikasi', '__ID__') }}'.replace('__ID__', this.editId)
                : '{{ route('admin.kepegawaian.store-sertifikasi') }}',
            tunjangan: this.editMode
                ? '{{ route('admin.kepegawaian.update-tunjangan', '__ID__') }}'.replace('__ID__', this.editId)
                : '{{ route('admin.kepegawaian.store-tunjangan') }}',
        };
        return routes[this.tab] || '';
    },
    getMethod() {
        return this.editMode ? 'PUT' : 'POST';
    },
    confirmDelete(url) {
        this.deleteUrl = url;
        this.deleteModal = true;
    }
}">
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-primary">Kepegawaian</h2>
        <p class="text-on-surface-variant font-body-md">Kelola data kepegawaian guru & staff</p>
    </div>
    <button @click="openCreateModal()" class="px-4 py-2.5 bg-primary text-on-primary rounded-lg flex items-center gap-2 font-label-md hover:bg-primary-container transition-all active:scale-95">
        <span class="material-symbols-outlined">add</span>
        <span x-text="tab === 'kepegawaian' ? 'Tambah Kepegawaian' : tab === 'cuti' ? 'Tambah Cuti' : tab === 'kinerja' ? 'Tambah Kinerja' : tab === 'sertifikasi' ? 'Tambah Sertifikasi' : 'Tambah Tunjangan'"></span>
    </button>
</div>

{{-- Stats --}}
<div class="grid grid-cols-5 gap-gutter mb-6">
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-primary-fixed text-primary flex items-center justify-center"><span class="material-symbols-outlined" style="font-variation-settings:'FILL'1">badge</span></div>
        <div><p class="text-xs text-on-surface-variant">Total Guru</p><h3 class="font-headline-md text-headline-md text-primary">{{ $semuaGuru->count() }}</h3></div>
    </div>
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-secondary-fixed text-secondary flex items-center justify-center"><span class="material-symbols-outlined" style="font-variation-settings:'FILL'1">assignment_turned_in</span></div>
        <div><p class="text-xs text-on-surface-variant">Data Kepegawaian</p><h3 class="font-headline-md text-headline-md text-primary">{{ $kepegawaian->count() }}</h3></div>
    </div>
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center"><span class="material-symbols-outlined" style="font-variation-settings:'FILL'1">event_busy</span></div>
        <div><p class="text-xs text-on-surface-variant">Cuti Aktif</p><h3 class="font-headline-md text-headline-md text-primary">{{ $cuti->where('status', 'disetujui')->count() }}</h3></div>
    </div>
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-tertiary-fixed text-tertiary flex items-center justify-center"><span class="material-symbols-outlined" style="font-variation-settings:'FILL'1">workspace_premium</span></div>
        <div><p class="text-xs text-on-surface-variant">Sertifikasi</p><h3 class="font-headline-md text-headline-md text-primary">{{ $sertifikasi->count() }}</h3></div>
    </div>
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-green-100 text-green-700 flex items-center justify-center"><span class="material-symbols-outlined" style="font-variation-settings:'FILL'1">payments</span></div>
        <div><p class="text-xs text-on-surface-variant">Tunjangan</p><h3 class="font-headline-md text-headline-md text-primary">{{ $tunjangan->count() }}</h3></div>
    </div>
</div>

{{-- Tab Navigation --}}
<div class="flex gap-1 bg-surface-container-low rounded-xl p-1 mb-6 w-fit overflow-x-auto">
    <button @click="tab = 'kepegawaian'" :class="tab === 'kepegawaian' ? 'bg-white text-primary shadow-sm' : 'text-on-surface-variant hover:text-primary'" class="px-5 py-2.5 rounded-lg font-label-md transition-all whitespace-nowrap">
        <span class="flex items-center gap-2"><span class="material-symbols-outlined text-lg">badge</span> Data Kepegawaian</span>
    </button>
    <button @click="tab = 'cuti'" :class="tab === 'cuti' ? 'bg-white text-primary shadow-sm' : 'text-on-surface-variant hover:text-primary'" class="px-5 py-2.5 rounded-lg font-label-md transition-all whitespace-nowrap">
        <span class="flex items-center gap-2"><span class="material-symbols-outlined text-lg">event_busy</span> Cuti & Izin</span>
    </button>
    <button @click="tab = 'kinerja'" :class="tab === 'kinerja' ? 'bg-white text-primary shadow-sm' : 'text-on-surface-variant hover:text-primary'" class="px-5 py-2.5 rounded-lg font-label-md transition-all whitespace-nowrap">
        <span class="flex items-center gap-2"><span class="material-symbols-outlined text-lg">trending_up</span> Kinerja (PKG)</span>
    </button>
    <button @click="tab = 'sertifikasi'" :class="tab === 'sertifikasi' ? 'bg-white text-primary shadow-sm' : 'text-on-surface-variant hover:text-primary'" class="px-5 py-2.5 rounded-lg font-label-md transition-all whitespace-nowrap">
        <span class="flex items-center gap-2"><span class="material-symbols-outlined text-lg">workspace_premium</span> Sertifikasi</span>
    </button>
    <button @click="tab = 'tunjangan'" :class="tab === 'tunjangan' ? 'bg-white text-primary shadow-sm' : 'text-on-surface-variant hover:text-primary'" class="px-5 py-2.5 rounded-lg font-label-md transition-all whitespace-nowrap">
        <span class="flex items-center gap-2"><span class="material-symbols-outlined text-lg">payments</span> Tunjangan</span>
    </button>
</div>

{{-- === TAB: DATA KEPEGAWAIAN === --}}
<div x-show="tab === 'kepegawaian'">
    <div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-surface-container-low">
                    <tr>
                        <th class="px-6 py-4 font-label-md text-on-surface-variant">Nama Guru</th>
                        <th class="px-6 py-4 font-label-md text-on-surface-variant">Status</th>
                        <th class="px-6 py-4 font-label-md text-on-surface-variant">Golongan</th>
                        <th class="px-6 py-4 font-label-md text-on-surface-variant">Jabatan</th>
                        <th class="px-6 py-4 font-label-md text-on-surface-variant">Masa Kerja</th>
                        <th class="px-6 py-4 font-label-md text-on-surface-variant">NIK / NPWP</th>
                        <th class="px-6 py-4 font-label-md text-on-surface-variant">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse($kepegawaian as $item)
                    <tr class="hover:bg-surface-container transition-colors">
                        <td class="px-6 py-4 font-body-md font-semibold">{{ $item->guru->user->name ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $item->status_kepegawaian === 'PNS' ? 'bg-green-100 text-green-700' : ($item->status_kepegawaian === 'PPPK' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700') }}">{{ $item->status_kepegawaian }}</span>
                        </td>
                        <td class="px-6 py-4">{{ $item->golongan ?: '-' }}</td>
                        <td class="px-6 py-4">{{ $item->jabatan ?: '-' }}</td>
                        <td class="px-6 py-4">{{ $item->masa_kerja_tahun ? $item->masa_kerja_tahun . ' thn ' . ($item->masa_kerja_bulan ? $item->masa_kerja_bulan . ' bln' : '') : '-' }}</td>
                        <td class="px-6 py-4 text-body-sm">{{ $item->nik ?: '-' }} / {{ $item->npwp ?: '-' }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <button @click="openModal = true; editMode = true; editId = '{{ $item->id }}'; formKepegawaian = { guru_id: '{{ $item->guru_id }}', status_kepegawaian: '{{ $item->status_kepegawaian }}', golongan: '{{ $item->golongan }}', jabatan: '{{ $item->jabatan }}', tmt_cpns: '{{ $item->tmt_cpns }}', tmt_pns: '{{ $item->tmt_pns }}', masa_kerja_tahun: '{{ $item->masa_kerja_tahun }}', masa_kerja_bulan: '{{ $item->masa_kerja_bulan }}', nik: '{{ $item->nik }}', npwp: '{{ $item->npwp }}', karpeg: '{{ $item->karpeg }}' }" class="p-2 hover:bg-surface-container-low rounded-lg">
                                    <span class="material-symbols-outlined text-outline">edit</span>
                                </button>
                                <button @click="confirmDelete('{{ route('admin.kepegawaian.destroy-kepegawaian', $item) }}')" class="p-2 hover:bg-error/10 rounded-lg">
                                    <span class="material-symbols-outlined text-error">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td class="px-6 py-8 text-center text-on-surface-variant" colspan="7">Belum ada data kepegawaian.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- === TAB: CUTI & IZIN === --}}
<div x-show="tab === 'cuti'">
    <div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-surface-container-low">
                    <tr>
                        <th class="px-6 py-4 font-label-md text-on-surface-variant">Nama Guru</th>
                        <th class="px-6 py-4 font-label-md text-on-surface-variant">Jenis Cuti</th>
                        <th class="px-6 py-4 font-label-md text-on-surface-variant">Tanggal</th>
                        <th class="px-6 py-4 font-label-md text-on-surface-variant">Alasan</th>
                        <th class="px-6 py-4 font-label-md text-on-surface-variant">Status</th>
                        <th class="px-6 py-4 font-label-md text-on-surface-variant">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse($cuti as $item)
                    <tr class="hover:bg-surface-container transition-colors">
                        <td class="px-6 py-4 font-body-md font-semibold">{{ $item->guru->user->name ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $jenisCuti[$item->jenis_cuti] ?? $item->jenis_cuti }}</td>
                        <td class="px-6 py-4 text-body-sm">{{ $item->tanggal_mulai->isoFormat('D MMM') }} - {{ $item->tanggal_selesai->isoFormat('D MMM Y') }}</td>
                        <td class="px-6 py-4 max-w-xs truncate text-body-sm">{{ $item->alasan }}</td>
                        <td class="px-6 py-4">
                            @php $s = $statusCuti[$item->status] ?? ['bg-gray-100 text-gray-700', $item->status]; @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $s[0] }}">{{ $s[1] }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <button @click="openModal = true; editMode = true; editId = '{{ $item->id }}'; formCuti = { guru_id: '{{ $item->guru_id }}', jenis_cuti: '{{ $item->jenis_cuti }}', tanggal_mulai: '{{ $item->tanggal_mulai->format('Y-m-d') }}', tanggal_selesai: '{{ $item->tanggal_selesai->format('Y-m-d') }}', alasan: `{{ $item->alasan }}`, status: '{{ $item->status }}', file: null }" class="p-2 hover:bg-surface-container-low rounded-lg">
                                    <span class="material-symbols-outlined text-outline">edit</span>
                                </button>
                                <button @click="confirmDelete('{{ route('admin.kepegawaian.destroy-cuti', $item) }}')" class="p-2 hover:bg-error/10 rounded-lg">
                                    <span class="material-symbols-outlined text-error">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td class="px-6 py-8 text-center text-on-surface-variant" colspan="6">Belum ada data cuti.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- === TAB: KINERJA === --}}
<div x-show="tab === 'kinerja'">
    <div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-surface-container-low">
                    <tr>
                        <th class="px-6 py-4 font-label-md text-on-surface-variant">Nama Guru</th>
                        <th class="px-6 py-4 font-label-md text-on-surface-variant">TA / Semester</th>
                        <th class="px-6 py-4 font-label-md text-on-surface-variant">Jam Mengajar</th>
                        <th class="px-6 py-4 font-label-md text-on-surface-variant">Skor PKG</th>
                        <th class="px-6 py-4 font-label-md text-on-surface-variant">Predikat</th>
                        <th class="px-6 py-4 font-label-md text-on-surface-variant">Kategori</th>
                        <th class="px-6 py-4 font-label-md text-on-surface-variant">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse($kinerja as $item)
                    <tr class="hover:bg-surface-container transition-colors">
                        <td class="px-6 py-4 font-body-md font-semibold">{{ $item->guru->user->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-body-sm">{{ $item->tahun_ajaran }} / {{ $item->semester }}</td>
                        <td class="px-6 py-4">{{ $item->jam_mengajar_per_minggu ? $item->jam_mengajar_per_minggu . ' jam' : '-' }}</td>
                        <td class="px-6 py-4">{{ $item->skor_pkg ?? '-' }}</td>
                        <td class="px-6 py-4">
                            @if($item->predikat_pkg)
                            <span class="px-3 py-1 rounded-full text-xs font-bold
                                {{ $item->predikat_pkg == 'Amat Baik' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $item->predikat_pkg == 'Baik' ? 'bg-blue-100 text-blue-700' : '' }}
                                {{ $item->predikat_pkg == 'Cukup' ? 'bg-amber-100 text-amber-700' : '' }}
                                {{ $item->predikat_pkg == 'Kurang' ? 'bg-red-100 text-red-700' : '' }}">{{ $item->predikat_pkg }}</span>
                            @else
                            -
                            @endif
                        </td>
                        <td class="px-6 py-4 text-body-sm">{{ $kategoriKinerja[$item->kategori] ?? $item->kategori }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <button @click="openModal = true; editMode = true; editId = '{{ $item->id }}'; formKinerja = { guru_id: '{{ $item->guru_id }}', tahun_ajaran: '{{ $item->tahun_ajaran }}', semester: '{{ $item->semester }}', jam_mengajar_per_minggu: '{{ $item->jam_mengajar_per_minggu }}', skor_pkg: '{{ $item->skor_pkg }}', predikat_pkg: '{{ $item->predikat_pkg }}', kategori: '{{ $item->kategori }}', catatan: `{{ $item->catatan }}` }" class="p-2 hover:bg-surface-container-low rounded-lg">
                                    <span class="material-symbols-outlined text-outline">edit</span>
                                </button>
                                <button @click="confirmDelete('{{ route('admin.kepegawaian.destroy-kinerja', $item) }}')" class="p-2 hover:bg-error/10 rounded-lg">
                                    <span class="material-symbols-outlined text-error">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td class="px-6 py-8 text-center text-on-surface-variant" colspan="7">Belum ada data kinerja.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- === TAB: SERTIFIKASI === --}}
<div x-show="tab === 'sertifikasi'">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-gutter">
        @forelse($sertifikasi as $item)
        <div class="bg-white rounded-xl border border-outline-variant card-shadow p-5 flex flex-col gap-3">
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-tertiary-fixed text-tertiary flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined">workspace_premium</span>
                    </div>
                    <div>
                        <h4 class="font-label-md text-label-md font-semibold text-primary">{{ $jenisSertifikasi[$item->jenis_sertifikasi] ?? $item->jenis_sertifikasi }}</h4>
                        <p class="text-xs text-on-surface-variant">{{ $item->guru->user->name ?? '-' }}</p>
                    </div>
                </div>
            </div>
            <div class="text-body-sm space-y-1">
                <p><span class="text-on-surface-variant">No:</span> {{ $item->nomor_sertifikat }}</p>
                <p><span class="text-on-surface-variant">Tahun:</span> {{ $item->tahun_sertifikasi }}</p>
                <p><span class="text-on-surface-variant">Bidang:</span> {{ $item->bidang_studi ?: '-' }}</p>
                <p><span class="text-on-surface-variant">Penerbit:</span> {{ $item->penerbit }}</p>
            </div>
            <div class="flex items-center gap-2 mt-auto pt-2 border-t border-outline-variant">
                @if($item->file)
                <a href="{{ $item->file }}" target="_blank" class="px-3 py-1.5 bg-secondary-fixed text-secondary rounded-lg text-xs font-bold flex items-center gap-1 hover:bg-secondary-fixed/80 transition-all">
                    <span class="material-symbols-outlined text-sm">download</span> File
                </a>
                @endif
                <div class="flex items-center gap-1 ml-auto">
                    <button @click="openModal = true; editMode = true; editId = '{{ $item->id }}'; formSertifikasi = { guru_id: '{{ $item->guru_id }}', jenis_sertifikasi: '{{ $item->jenis_sertifikasi }}', nomor_sertifikat: '{{ $item->nomor_sertifikat }}', tahun_sertifikasi: '{{ $item->tahun_sertifikasi }}', bidang_studi: '{{ $item->bidang_studi }}', penerbit: '{{ $item->penerbit }}', file: null }" class="p-1.5 hover:bg-surface-container-low rounded-lg">
                        <span class="material-symbols-outlined text-sm text-outline">edit</span>
                    </button>
                    <button @click="confirmDelete('{{ route('admin.kepegawaian.destroy-sertifikasi', $item) }}')" class="p-1.5 hover:bg-error/10 rounded-lg">
                        <span class="material-symbols-outlined text-sm text-error">delete</span>
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white rounded-xl border border-outline-variant card-shadow p-8 text-center text-on-surface-variant">
            <span class="material-symbols-outlined text-4xl mb-2 block">workspace_premium</span>
            <p>Belum ada data sertifikasi.</p>
        </div>
        @endforelse
    </div>
</div>

{{-- === TAB: TUNJANGAN === --}}
<div x-show="tab === 'tunjangan'">
    <div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-surface-container-low">
                    <tr>
                        <th class="px-6 py-4 font-label-md text-on-surface-variant">Nama Guru</th>
                        <th class="px-6 py-4 font-label-md text-on-surface-variant">Jenis Tunjangan</th>
                        <th class="px-6 py-4 font-label-md text-on-surface-variant">Besaran</th>
                        <th class="px-6 py-4 font-label-md text-on-surface-variant">Periode</th>
                        <th class="px-6 py-4 font-label-md text-on-surface-variant">Status</th>
                        <th class="px-6 py-4 font-label-md text-on-surface-variant">Tgl Bayar</th>
                        <th class="px-6 py-4 font-label-md text-on-surface-variant">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse($tunjangan as $item)
                    <tr class="hover:bg-surface-container transition-colors">
                        <td class="px-6 py-4 font-body-md font-semibold">{{ $item->guru->user->name ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $jenisTunjangan[$item->jenis_tunjangan] ?? $item->jenis_tunjangan }}</td>
                        <td class="px-6 py-4 font-semibold">Rp {{ number_format($item->besaran, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-body-sm">{{ $item->periode_bulan }} {{ $item->periode_tahun }}</td>
                        <td class="px-6 py-4">
                            @php $s = $statusTunjangan[$item->status] ?? ['bg-gray-100 text-gray-700', $item->status]; @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $s[0] }}">{{ $s[1] }}</span>
                        </td>
                        <td class="px-6 py-4 text-body-sm">{{ $item->tanggal_bayar ? $item->tanggal_bayar->isoFormat('D MMM Y') : '-' }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <button @click="openModal = true; editMode = true; editId = '{{ $item->id }}'; formTunjangan = { guru_id: '{{ $item->guru_id }}', jenis_tunjangan: '{{ $item->jenis_tunjangan }}', besaran: '{{ $item->besaran }}', periode_bulan: '{{ $item->periode_bulan }}', periode_tahun: '{{ $item->periode_tahun }}', status: '{{ $item->status }}', tanggal_bayar: '{{ $item->tanggal_bayar }}', keterangan: `{{ $item->keterangan }}` }" class="p-2 hover:bg-surface-container-low rounded-lg">
                                    <span class="material-symbols-outlined text-outline">edit</span>
                                </button>
                                <button @click="confirmDelete('{{ route('admin.kepegawaian.destroy-tunjangan', $item) }}')" class="p-2 hover:bg-error/10 rounded-lg">
                                    <span class="material-symbols-outlined text-error">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td class="px-6 py-8 text-center text-on-surface-variant" colspan="7">Belum ada data tunjangan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- === CRUD MODAL === --}}
<div x-show="openModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.away="openModal = false">
    <div class="fixed inset-0 bg-black/40" @click="openModal = false"></div>
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg p-8 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-headline-sm text-headline-sm">
                <template x-if="tab === 'kepegawaian'"><span x-text="editMode ? 'Edit Kepegawaian' : 'Tambah Kepegawaian'"></span></template>
                <template x-if="tab === 'cuti'"><span x-text="editMode ? 'Edit Cuti' : 'Tambah Cuti'"></span></template>
                <template x-if="tab === 'kinerja'"><span x-text="editMode ? 'Edit Kinerja' : 'Tambah Kinerja'"></span></template>
                <template x-if="tab === 'sertifikasi'"><span x-text="editMode ? 'Edit Sertifikasi' : 'Tambah Sertifikasi'"></span></template>
                <template x-if="tab === 'tunjangan'"><span x-text="editMode ? 'Edit Tunjangan' : 'Tambah Tunjangan'"></span></template>
            </h3>
            <button @click="openModal = false" class="p-2 hover:bg-surface-container rounded-lg"><span class="material-symbols-outlined">close</span></button>
        </div>

        {{-- FORM: Kepegawaian --}}
        <form x-show="tab === 'kepegawaian'" :action="getSubmitAction()" method="POST" class="space-y-4">
            @csrf
            <template x-if="editMode"><input type="hidden" name="_method" value="PUT"></template>
            <div class="space-y-2" x-show="!editMode">
                <label class="font-label-md text-label-md text-on-surface-variant">Guru</label>
                <select x-model="formKepegawaian.guru_id" name="guru_id" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" required>
                    <option value="">-- Pilih Guru --</option>
                    @foreach($semuaGuru as $guru)
                    <option value="{{ $guru->id }}">{{ $guru->user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Status Kepegawaian</label>
                    <select x-model="formKepegawaian.status_kepegawaian" name="status_kepegawaian" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" required>
                        <option value="PNS">PNS</option>
                        <option value="PPPK">PPPK</option>
                        <option value="Non-PNS/GTT/PTT">Non-PNS / GTT / PTT</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Golongan</label>
                    <select x-model="formKepegawaian.golongan" name="golongan" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary">
                        <option value="">-- Pilih --</option>
                        @foreach($golonganList as $g)
                        <option value="{{ $g }}">{{ $g }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Jabatan</label>
                <select x-model="formKepegawaian.jabatan" name="jabatan" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary">
                    <option value="">-- Pilih --</option>
                    @foreach($jabatanList as $j)
                    <option value="{{ $j }}">{{ $j }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">TMT CPNS</label>
                    <input x-model="formKepegawaian.tmt_cpns" type="date" name="tmt_cpns" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary">
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">TMT PNS</label>
                    <input x-model="formKepegawaian.tmt_pns" type="date" name="tmt_pns" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Masa Kerja (Tahun)</label>
                    <input x-model="formKepegawaian.masa_kerja_tahun" type="number" name="masa_kerja_tahun" min="0" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary">
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Masa Kerja (Bulan)</label>
                    <input x-model="formKepegawaian.masa_kerja_bulan" type="number" name="masa_kerja_bulan" min="0" max="11" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary">
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">NIK</label>
                    <input x-model="formKepegawaian.nik" type="text" name="nik" maxlength="20" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary">
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">NPWP</label>
                    <input x-model="formKepegawaian.npwp" type="text" name="npwp" maxlength="30" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary">
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Karpeg</label>
                    <input x-model="formKepegawaian.karpeg" type="text" name="karpeg" maxlength="30" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary">
                </div>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" @click="openModal = false" class="flex-1 py-3 border border-outline-variant rounded-xl font-label-md hover:bg-surface-container">Batal</button>
                <button type="submit" class="flex-1 py-3 bg-primary text-on-primary rounded-xl font-label-md hover:bg-primary-container" x-text="editMode ? 'Simpan' : 'Tambah'"></button>
            </div>
        </form>

        {{-- FORM: Cuti --}}
        <form x-show="tab === 'cuti'" :action="getSubmitAction()" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <template x-if="editMode"><input type="hidden" name="_method" value="PUT"></template>
            <div class="space-y-2" x-show="!editMode">
                <label class="font-label-md text-label-md text-on-surface-variant">Guru</label>
                <select x-model="formCuti.guru_id" name="guru_id" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" required>
                    <option value="">-- Pilih Guru --</option>
                    @foreach($semuaGuru as $guru)
                    <option value="{{ $guru->id }}">{{ $guru->user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Jenis Cuti</label>
                <select x-model="formCuti.jenis_cuti" name="jenis_cuti" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" required>
                    @foreach($jenisCuti as $val => $label)
                    <option value="{{ $val }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Tanggal Mulai</label>
                    <input x-model="formCuti.tanggal_mulai" type="date" name="tanggal_mulai" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" required>
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Tanggal Selesai</label>
                    <input x-model="formCuti.tanggal_selesai" type="date" name="tanggal_selesai" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" required>
                </div>
            </div>
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Alasan</label>
                <textarea x-model="formCuti.alasan" name="alasan" rows="3" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" required maxlength="500"></textarea>
            </div>
            <template x-if="editMode">
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Status</label>
                <select x-model="formCuti.status" name="status" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" required>
                    <option value="pending">Pending</option>
                    <option value="disetujui">Disetujui</option>
                    <option value="ditolak">Ditolak</option>
                </select>
            </div>
            </template>
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">File (PDF/Gambar)</label>
                <input type="file" name="file" accept=".pdf,.jpg,.jpeg,.png" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-3 py-2.5 focus:outline-none focus:border-primary file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-primary file:text-on-primary file:text-xs file:font-bold hover:file:bg-primary-container cursor-pointer">
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" @click="openModal = false" class="flex-1 py-3 border border-outline-variant rounded-xl font-label-md hover:bg-surface-container">Batal</button>
                <button type="submit" class="flex-1 py-3 bg-primary text-on-primary rounded-xl font-label-md hover:bg-primary-container" x-text="editMode ? 'Simpan' : 'Tambah'"></button>
            </div>
        </form>

        {{-- FORM: Kinerja --}}
        <form x-show="tab === 'kinerja'" :action="getSubmitAction()" method="POST" class="space-y-4">
            @csrf
            <template x-if="editMode"><input type="hidden" name="_method" value="PUT"></template>
            <div class="space-y-2" x-show="!editMode">
                <label class="font-label-md text-label-md text-on-surface-variant">Guru</label>
                <select x-model="formKinerja.guru_id" name="guru_id" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" required>
                    <option value="">-- Pilih Guru --</option>
                    @foreach($semuaGuru as $guru)
                    <option value="{{ $guru->id }}">{{ $guru->user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Tahun Ajaran</label>
                    <input x-model="formKinerja.tahun_ajaran" type="text" name="tahun_ajaran" placeholder="2024/2025" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" required>
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Semester</label>
                    <select x-model="formKinerja.semester" name="semester" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" required>
                        <option value="Ganjil">Ganjil</option>
                        <option value="Genap">Genap</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Jam Mengajar / Minggu</label>
                    <input x-model="formKinerja.jam_mengajar_per_minggu" type="number" name="jam_mengajar_per_minggu" min="0" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary">
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Skor PKG</label>
                    <input x-model="formKinerja.skor_pkg" type="number" name="skor_pkg" step="0.01" min="0" max="100" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Predikat PKG</label>
                    <select x-model="formKinerja.predikat_pkg" name="predikat_pkg" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary">
                        <option value="">-- Pilih --</option>
                        @foreach($predikatPkg as $p)
                        <option value="{{ $p }}">{{ $p }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Kategori</label>
                    <select x-model="formKinerja.kategori" name="kategori" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" required>
                        @foreach($kategoriKinerja as $val => $label)
                        <option value="{{ $val }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Catatan</label>
                <textarea x-model="formKinerja.catatan" name="catatan" rows="3" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary resize-none"></textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" @click="openModal = false" class="flex-1 py-3 border border-outline-variant rounded-xl font-label-md hover:bg-surface-container">Batal</button>
                <button type="submit" class="flex-1 py-3 bg-primary text-on-primary rounded-xl font-label-md hover:bg-primary-container" x-text="editMode ? 'Simpan' : 'Tambah'"></button>
            </div>
        </form>

        {{-- FORM: Sertifikasi --}}
        <form x-show="tab === 'sertifikasi'" :action="getSubmitAction()" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <template x-if="editMode"><input type="hidden" name="_method" value="PUT"></template>
            <div class="space-y-2" x-show="!editMode">
                <label class="font-label-md text-label-md text-on-surface-variant">Guru</label>
                <select x-model="formSertifikasi.guru_id" name="guru_id" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" required>
                    <option value="">-- Pilih Guru --</option>
                    @foreach($semuaGuru as $guru)
                    <option value="{{ $guru->id }}">{{ $guru->user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Jenis Sertifikasi</label>
                    <select x-model="formSertifikasi.jenis_sertifikasi" name="jenis_sertifikasi" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" required>
                        @foreach($jenisSertifikasi as $val => $label)
                        <option value="{{ $val }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Tahun Sertifikasi</label>
                    <input x-model="formSertifikasi.tahun_sertifikasi" type="number" name="tahun_sertifikasi" min="1990" max="2099" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" required>
                </div>
            </div>
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Nomor Sertifikat</label>
                <input x-model="formSertifikasi.nomor_sertifikat" type="text" name="nomor_sertifikat" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" required>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Bidang Studi</label>
                    <input x-model="formSertifikasi.bidang_studi" type="text" name="bidang_studi" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary">
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Penerbit</label>
                    <input x-model="formSertifikasi.penerbit" type="text" name="penerbit" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" required>
                </div>
            </div>
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">File (PDF/Gambar)</label>
                <input type="file" name="file" accept=".pdf,.jpg,.jpeg,.png" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-3 py-2.5 focus:outline-none focus:border-primary file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-primary file:text-on-primary file:text-xs file:font-bold hover:file:bg-primary-container cursor-pointer">
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" @click="openModal = false" class="flex-1 py-3 border border-outline-variant rounded-xl font-label-md hover:bg-surface-container">Batal</button>
                <button type="submit" class="flex-1 py-3 bg-primary text-on-primary rounded-xl font-label-md hover:bg-primary-container" x-text="editMode ? 'Simpan' : 'Tambah'"></button>
            </div>
        </form>

        {{-- FORM: Tunjangan --}}
        <form x-show="tab === 'tunjangan'" :action="getSubmitAction()" method="POST" class="space-y-4">
            @csrf
            <template x-if="editMode"><input type="hidden" name="_method" value="PUT"></template>
            <div class="space-y-2" x-show="!editMode">
                <label class="font-label-md text-label-md text-on-surface-variant">Guru</label>
                <select x-model="formTunjangan.guru_id" name="guru_id" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" required>
                    <option value="">-- Pilih Guru --</option>
                    @foreach($semuaGuru as $guru)
                    <option value="{{ $guru->id }}">{{ $guru->user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Jenis Tunjangan</label>
                    <select x-model="formTunjangan.jenis_tunjangan" name="jenis_tunjangan" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" required>
                        @foreach($jenisTunjangan as $val => $label)
                        <option value="{{ $val }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Besaran (Rp)</label>
                    <input x-model="formTunjangan.besaran" type="number" name="besaran" step="0.01" min="0" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" required>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Periode Bulan</label>
                    <select x-model="formTunjangan.periode_bulan" name="periode_bulan" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" required>
                        <option value="">-- Pilih --</option>
                        @foreach($bulanList as $b)
                        <option value="{{ $b }}">{{ $b }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Periode Tahun</label>
                    <input x-model="formTunjangan.periode_tahun" type="number" name="periode_tahun" min="2000" max="2099" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" required>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Status</label>
                    <select x-model="formTunjangan.status" name="status" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" required>
                        <option value="menunggu">Menunggu</option>
                        <option value="dibayarkan">Dibayarkan</option>
                        <option value="ditangguhkan">Ditangguhkan</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Tanggal Bayar</label>
                    <input x-model="formTunjangan.tanggal_bayar" type="date" name="tanggal_bayar" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary">
                </div>
            </div>
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Keterangan</label>
                <textarea x-model="formTunjangan.keterangan" name="keterangan" rows="2" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary resize-none"></textarea>
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
                <p class="text-sm text-on-surface-variant mt-1">Apakah Anda yakin ingin menghapus data ini?</p>
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
</div>
</x-layouts.admin>
