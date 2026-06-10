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
<x-layouts.admin title="PPDB | EduPortal">
    <x-slot:styles>
    <style>
        [x-cloak] { display: none !important; }
    </style>
    </x-slot:styles>
<div>
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-primary">PPDB</h2>
        <p class="text-on-surface-variant font-body-md">Penerimaan Peserta Didik Baru</p>
    </div>
    <a href="{{ route('ppdb.form') }}" target="_blank" class="bg-primary text-on-primary px-6 py-2.5 rounded-lg flex items-center gap-2 font-label-md hover:bg-primary-container transition-all">
        <span class="material-symbols-outlined">open_in_new</span>
        Buka Halaman Pendaftaran
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-outline-variant p-5 card-shadow">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-lg bg-warning/10 flex items-center justify-center">
                <span class="material-symbols-outlined text-warning">hourglass_empty</span>
            </div>
            <div>
                <p class="text-2xl font-headline-sm font-bold">{{ $countMenunggu }}</p>
                <p class="text-xs text-on-surface-variant">Menunggu</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-outline-variant p-5 card-shadow">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-lg bg-info/10 flex items-center justify-center">
                <span class="material-symbols-outlined text-info">visibility</span>
            </div>
            <div>
                <p class="text-2xl font-headline-sm font-bold">{{ $countDiverifikasi }}</p>
                <p class="text-xs text-on-surface-variant">Diverifikasi</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-outline-variant p-5 card-shadow">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-lg bg-success/10 flex items-center justify-center">
                <span class="material-symbols-outlined text-success">check_circle</span>
            </div>
            <div>
                <p class="text-2xl font-headline-sm font-bold">{{ $countDiterima }}</p>
                <p class="text-xs text-on-surface-variant">Diterima</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-outline-variant p-5 card-shadow">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-lg bg-error/10 flex items-center justify-center">
                <span class="material-symbols-outlined text-error">cancel</span>
            </div>
            <div>
                <p class="text-2xl font-headline-sm font-bold">{{ $countDitolak }}</p>
                <p class="text-xs text-on-surface-variant">Ditolak</p>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-surface-container-low">
                <tr>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">No. Pendaftaran</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Nama Lengkap</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Asal Sekolah</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Jurusan</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Berkas</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Status</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant">
                @forelse($semuaPendaftaran as $pendaftaran)
                <tr class="hover:bg-surface-container transition-colors">
                    <td class="px-6 py-4 font-mono text-sm font-semibold">{{ $pendaftaran->no_pendaftaran }}</td>
                    <td class="px-6 py-4 font-body-md font-semibold">{{ $pendaftaran->nama_lengkap }}</td>
                    <td class="px-6 py-4">{{ $pendaftaran->asal_sekolah ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $pendaftaran->jurusan_daftar ?? '-' }}</td>
                    <td class="px-6 py-4">
                        <span class="text-xs text-on-surface-variant">{{ $pendaftaran->berkas->count() }} file</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-xs font-label-md {{ $statusBadge($pendaftaran->status) }}">{{ $statusLabel($pendaftaran->status) }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.ppdb.verifikasi', $pendaftaran) }}" class="p-2 hover:bg-surface-container-low rounded-lg transition-colors">
                                <span class="material-symbols-outlined text-outline">visibility</span>
                            </a>
                            <form method="POST" action="{{ route('admin.ppdb.destroy', $pendaftaran) }}" @submit.prevent="Swal.fire({title:'Konfirmasi',text:'Yakin ingin menghapus pendaftaran {{ $pendaftaran->nama_lengkap }}?',icon:'warning',showCancelButton:true,confirmButtonText:'Ya, hapus!',cancelButtonText:'Batal'}).then(r=>r.isConfirmed&&$el.submit())">
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
                <tr><td class="px-6 py-8 text-center text-on-surface-variant" colspan="7">Belum ada data pendaftaran.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($semuaPendaftaran->hasPages())
    <div class="px-6 py-4 border-t border-outline-variant">
        {{ $semuaPendaftaran->links() }}
    </div>
    @endif
</div>
</div>
</x-layouts.admin>
