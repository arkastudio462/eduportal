@php
$statusBadge = fn($s) => match($s) {
    'menunggu' => 'bg-warning/10 text-warning',
    'diverifikasi' => 'bg-info/10 text-info',
    'diterima' => 'bg-success/10 text-success',
    'ditolak' => 'bg-error/10 text-error',
    default => 'bg-surface-container-low text-on-surface',
};
$statusLabel = fn($s) => match($s) {
    'menunggu' => 'Menunggu',
    'diverifikasi' => 'Diverifikasi',
    'diterima' => 'Diterima',
    'ditolak' => 'Ditolak',
    default => $s,
};
@endphp
<x-layouts.admin title="Verifikasi PPDB | EduPortal">
    <x-slot:styles>
    <style>
        [x-cloak] { display: none !important; }
    </style>
    </x-slot:styles>
<div>
<div class="flex items-center gap-4 mb-6">
    <a href="{{ route('admin.ppdb') }}" class="p-2 hover:bg-surface-container rounded-lg transition-colors">
        <span class="material-symbols-outlined">arrow_back</span>
    </a>
    <div>
        <h2 class="font-headline-lg text-headline-lg text-primary">Verifikasi Pendaftaran</h2>
        <p class="text-on-surface-variant font-body-md">{{ $pendaftaran->no_pendaftaran }} - {{ $pendaftaran->nama_lengkap }}</p>
    </div>
    <div class="ml-auto">
        <span class="px-4 py-2 rounded-full text-sm font-label-md {{ $statusBadge($pendaftaran->status) }}">{{ $statusLabel($pendaftaran->status) }}</span>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl border border-outline-variant card-shadow p-6">
            <h3 class="font-headline-sm text-headline-sm text-primary mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined">person</span>
                Data Calon Siswa
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div><span class="text-on-surface-variant">Nama Lengkap:</span><br><strong>{{ $pendaftaran->nama_lengkap }}</strong></div>
                <div><span class="text-on-surface-variant">NISN:</span><br><strong>{{ $pendaftaran->nisn }}</strong></div>
                <div><span class="text-on-surface-variant">Tempat / Tgl Lahir:</span><br><strong>{{ $pendaftaran->tempat_lahir ?? '-' }} / {{ $pendaftaran->tanggal_lahir?->format('d M Y') ?? '-' }}</strong></div>
                <div><span class="text-on-surface-variant">Jenis Kelamin:</span><br><strong>{{ $pendaftaran->jenis_kelamin == 'L' ? 'Laki-laki' : ($pendaftaran->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}</strong></div>
                <div><span class="text-on-surface-variant">Agama:</span><br><strong>{{ $pendaftaran->agama ?? '-' }}</strong></div>
                <div><span class="text-on-surface-variant">No. HP:</span><br><strong>{{ $pendaftaran->no_hp ?? '-' }}</strong></div>
                <div><span class="text-on-surface-variant">Email:</span><br><strong>{{ $pendaftaran->email ?? '-' }}</strong></div>
                <div><span class="text-on-surface-variant">Alamat:</span><br><strong>{{ $pendaftaran->alamat ?? '-' }}</strong></div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-outline-variant card-shadow p-6">
            <h3 class="font-headline-sm text-headline-sm text-primary mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined">school</span>
                Data Pendidikan
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div><span class="text-on-surface-variant">Asal Sekolah:</span><br><strong>{{ $pendaftaran->asal_sekolah ?? '-' }}</strong></div>
                <div><span class="text-on-surface-variant">Jurusan Dipilih:</span><br><strong>{{ $pendaftaran->jurusan_daftar ?? '-' }}</strong></div>
                <div><span class="text-on-surface-variant">Nilai Rata-rata:</span><br><strong>{{ $pendaftaran->nilai_rata_rata ?? '-' }}</strong></div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-outline-variant card-shadow p-6">
            <h3 class="font-headline-sm text-headline-sm text-primary mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined">family_restroom</span>
                Data Orang Tua
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div class="p-3 bg-surface-container-low rounded-xl">
                    <p class="font-label-md text-label-md text-primary mb-2">Ayah</p>
                    <div>Nama: <strong>{{ $pendaftaran->nama_ayah ?? '-' }}</strong></div>
                    <div>No. HP: <strong>{{ $pendaftaran->no_hp_ayah ?? '-' }}</strong></div>
                    <div>Pekerjaan: <strong>{{ $pendaftaran->pekerjaan_ayah ?? '-' }}</strong></div>
                </div>
                <div class="p-3 bg-surface-container-low rounded-xl">
                    <p class="font-label-md text-label-md text-primary mb-2">Ibu</p>
                    <div>Nama: <strong>{{ $pendaftaran->nama_ibu ?? '-' }}</strong></div>
                    <div>No. HP: <strong>{{ $pendaftaran->no_hp_ibu ?? '-' }}</strong></div>
                    <div>Pekerjaan: <strong>{{ $pendaftaran->pekerjaan_ibu ?? '-' }}</strong></div>
                </div>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-xl border border-outline-variant card-shadow p-6">
            <h3 class="font-headline-sm text-headline-sm text-primary mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined">upload_file</span>
                Dokumen
            </h3>
            @forelse($pendaftaran->berkas as $berkas)
            @php
                $isImage = in_array(pathinfo($berkas->original_name, PATHINFO_EXTENSION), ['jpg','jpeg','png','gif','webp']);
            @endphp
            <div class="flex items-start justify-between py-3 border-b border-outline-variant last:border-0">
                <div class="flex items-start gap-3 min-w-0">
                    <span class="material-symbols-outlined text-outline mt-1 {{ $isImage ? 'text-secondary' : '' }}">
                        {{ $isImage ? 'image' : 'description' }}
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-semibold truncate">{{ ucfirst($berkas->jenis) }}</p>
                        <p class="text-xs text-on-surface-variant truncate">{{ $berkas->original_name }}</p>
                        @if($isImage && $berkas->file_url)
                        <div class="mt-2 relative group">
                            <img src="{{ $berkas->file_url }}"
                                 alt="{{ $berkas->original_name }}"
                                 class="max-w-full h-32 object-contain rounded-lg border border-outline-variant cursor-pointer bg-surface-container-low transition-opacity hover:opacity-90"
                                 onclick="window.open(this.src)"
                                 loading="lazy"
                                 onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
                            <p class="hidden text-xs text-on-surface-variant italic mt-1">Pratinjau tidak tersedia</p>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="flex items-center gap-1 flex-none ml-2">
                    <a href="{{ route('admin.ppdb.download-berkas', $berkas) }}" class="p-1.5 hover:bg-surface-container rounded-lg transition-colors" title="Download">
                        <span class="material-symbols-outlined text-outline">download</span>
                    </a>
                </div>
            </div>
            @empty
            <p class="text-sm text-on-surface-variant text-center py-4">Belum ada dokumen</p>
            @endforelse
        </div>

        <div class="bg-white rounded-xl border border-outline-variant card-shadow p-6">
            <h3 class="font-headline-sm text-headline-sm text-primary mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined">verified</span>
                Verifikasi
            </h3>
            <form method="POST" action="{{ route('admin.ppdb.update-status', $pendaftaran) }}" class="space-y-4">
                @csrf
                @method('PUT')
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Status</label>
                    <select name="status" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2">
                        <option value="menunggu" {{ $pendaftaran->status == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                        <option value="diverifikasi" {{ $pendaftaran->status == 'diverifikasi' ? 'selected' : '' }}>Diverifikasi</option>
                        <option value="diterima" {{ $pendaftaran->status == 'diterima' ? 'selected' : '' }}>Diterima</option>
                        <option value="ditolak" {{ $pendaftaran->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Catatan</label>
                    <textarea name="catatan" rows="3"
                        class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2 resize-none"
                        placeholder="Catatan untuk pendaftar">{{ $pendaftaran->catatan }}</textarea>
                </div>
                <button type="submit" class="w-full py-3 bg-primary text-on-primary rounded-xl font-label-md hover:bg-primary-container transition-all">
                    Simpan Perubahan
                </button>
            </form>
        </div>
    </div>
</div>
</div>
</x-layouts.admin>
