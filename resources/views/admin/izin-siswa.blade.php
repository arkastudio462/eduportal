<x-layouts.admin title="Izin Siswa | EduPortal">
    <x-slot:styles>
    <style>[x-cloak] { display: none !important; }</style>
    </x-slot:styles>

@php
    $total = $semuaIzin->total();
    $pending = $semuaIzin->where('status', 'pending')->count();
    $disetujui = $semuaIzin->where('status', 'disetujui')->count();
    $ditolak = $semuaIzin->where('status', 'ditolak')->count();

    $kelasFilter = request('kelas', '');
    $statusFilter = request('status', '');
@endphp

<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-primary">Izin Siswa Online</h2>
        <p class="text-on-surface-variant font-body-md">Kelola pengajuan izin dari siswa</p>
    </div>
</div>

{{-- Stat Cards --}}
<div class="grid grid-cols-4 gap-gutter mb-6">
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-primary-fixed text-primary flex items-center justify-center">
            <span class="material-symbols-outlined" style="font-variation-settings:'FILL'1">assignment</span>
        </div>
        <div>
            <p class="text-xs text-on-surface-variant">Total</p>
            <h3 class="font-headline-md text-headline-md text-primary">{{ $total }}</h3>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center">
            <span class="material-symbols-outlined" style="font-variation-settings:'FILL'1">hourglass_empty</span>
        </div>
        <div>
            <p class="text-xs text-on-surface-variant">Pending</p>
            <h3 class="font-headline-md text-headline-md text-amber-600">{{ $pending }}</h3>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-green-100 text-green-700 flex items-center justify-center">
            <span class="material-symbols-outlined" style="font-variation-settings:'FILL'1">check_circle</span>
        </div>
        <div>
            <p class="text-xs text-on-surface-variant">Disetujui</p>
            <h3 class="font-headline-md text-headline-md text-green-700">{{ $disetujui }}</h3>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-red-100 text-red-700 flex items-center justify-center">
            <span class="material-symbols-outlined" style="font-variation-settings:'FILL'1">cancel</span>
        </div>
        <div>
            <p class="text-xs text-on-surface-variant">Ditolak</p>
            <h3 class="font-headline-md text-headline-md text-red-600">{{ $ditolak }}</h3>
        </div>
    </div>
</div>

{{-- Filters --}}
<form method="GET" class="bg-white rounded-xl border border-outline-variant card-shadow p-5 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="font-label-md text-label-md text-on-surface-variant mb-1 block">Kelas</label>
            <select name="kelas" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary">
                <option value="">Semua Kelas</option>
                @foreach($kelasList as $kelas)
                <option value="{{ $kelas->id }}" {{ $kelasFilter == $kelas->id ? 'selected' : '' }}>{{ $kelas->nama }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="font-label-md text-label-md text-on-surface-variant mb-1 block">Status</label>
            <select name="status" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary">
                <option value="">Semua Status</option>
                <option value="pending" {{ $statusFilter == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="disetujui" {{ $statusFilter == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                <option value="ditolak" {{ $statusFilter == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
        </div>
        <div class="flex items-end">
            <button type="submit" class="px-6 py-3 bg-primary text-on-primary rounded-xl font-label-md hover:bg-primary-container transition-all active:scale-95 flex items-center gap-2">
                <span class="material-symbols-outlined">search</span>
                Filter
            </button>
        </div>
    </div>
</form>

{{-- Table --}}
<div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-surface-container-low">
                <tr>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant w-12">No</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Nama Siswa</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Kelas</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Tanggal</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Alasan</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Status</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">File</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant">
                @forelse($semuaIzin as $i => $izin)
                <tr class="hover:bg-surface-container transition-colors">
                    <td class="px-6 py-4 font-body-md text-on-surface-variant">{{ $semuaIzin->firstItem() + $i }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-primary-fixed text-primary rounded-full flex items-center justify-center font-bold text-xs">
                                {{ substr($izin->siswa->user->name ?? '?', 0, 1) }}
                            </div>
                            <span class="font-body-md font-semibold">{{ $izin->siswa->user->name ?? '-' }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 font-body-md">{{ $izin->siswa->kelas->nama ?? '-' }}</td>
                    <td class="px-6 py-4 font-body-md">
                        {{ \Carbon\Carbon::parse($izin->tanggal_mulai)->format('d M') }} -
                        {{ \Carbon\Carbon::parse($izin->tanggal_selesai)->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4 font-body-md">{{ $izin->alasan }}</td>
                    <td class="px-6 py-4">
                        @php
                            $statusMap = [
                                'pending' => ['bg-amber-100 text-amber-700', 'Pending'],
                                'disetujui' => ['bg-green-100 text-green-700', 'Disetujui'],
                                'ditolak' => ['bg-red-100 text-red-700', 'Ditolak'],
                            ];
                            [$sClass, $sLabel] = $statusMap[$izin->status] ?? ['bg-gray-100 text-gray-700', $izin->status];
                        @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $sClass }}">{{ $sLabel }}</span>
                    </td>
                    <td class="px-6 py-4">
                        @if($izin->file)
                        <a href="{{ asset('storage/' . $izin->file) }}" target="_blank" class="text-secondary hover:text-secondary-fixed-dim font-label-md flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">attachment</span>
                            Lihat
                        </a>
                        @else
                        <span class="text-outline font-body-sm">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            @if($izin->status === 'pending')
                            <form method="POST" action="{{ route('admin.izin-siswa.approve', $izin->id) }}" class="inline"
                                @submit.prevent="Swal.fire({title:'Setujui Izin?',text:'Izin akan disetujui',icon:'question',showCancelButton:true,confirmButtonText:'Ya, Setujui',cancelButtonText:'Batal',confirmButtonColor:'#16a34a'}).then(r=>r.isConfirmed&&$el.submit())">
                                @csrf
                                <button class="p-2 hover:bg-green-100 rounded-lg" title="Setujui">
                                    <span class="material-symbols-outlined text-green-600">check_circle</span>
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.izin-siswa.reject', $izin->id) }}" class="inline"
                                @submit.prevent="Swal.fire({title:'Tolak Izin?',text:'Izin akan ditolak',icon:'warning',showCancelButton:true,confirmButtonText:'Ya, Tolak',cancelButtonText:'Batal',confirmButtonColor:'#dc2626'}).then(r=>r.isConfirmed&&$el.submit())">
                                @csrf
                                <button class="p-2 hover:bg-red-100 rounded-lg" title="Tolak">
                                    <span class="material-symbols-outlined text-red-600">cancel</span>
                                </button>
                            </form>
                            @endif
                            <form method="POST" action="{{ route('admin.izin-siswa.destroy', $izin->id) }}" class="inline"
                                @submit.prevent="Swal.fire({title:'Konfirmasi Hapus',text:'Yakin ingin menghapus izin ini?',icon:'warning',showCancelButton:true,confirmButtonText:'Ya, hapus!',cancelButtonText:'Batal',confirmButtonColor:'#dc2626'}).then(r=>r.isConfirmed&&$el.submit())">
                                @csrf
                                @method('DELETE')
                                <button class="p-2 hover:bg-error/10 rounded-lg" title="Hapus">
                                    <span class="material-symbols-outlined text-error">delete</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td class="px-6 py-8 text-center text-on-surface-variant" colspan="8">Belum ada data izin siswa.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($semuaIzin->hasPages())
    <div class="p-4 border-t border-outline-variant">{{ $semuaIzin->links('vendor.pagination.custom') }}</div>
    @endif
</div>
</x-layouts.admin>
