<x-layouts.admin title="Presensi Guru | EduPortal">
    <x-slot:styles>
    <style>[x-cloak] { display: none !important; }</style>
    </x-slot:styles>
@php
    $today = now()->format('Y-m-d');
    $totalToday = $semuaPresensi->count();
    $totalHadir = $semuaPresensi->where('status', 'hadir')->count();
    $totalIzin = $semuaPresensi->where('status', 'izin')->count();
    $totalSakit = $semuaPresensi->where('status', 'sakit')->count();
    $totalAlpha = $semuaPresensi->where('status', 'alpha')->count();
    $tanggalFilter = request('tanggal', '');
    $statusFilter = request('status', '');
    $qrUrl = $qrToken ? route('portal-guru.presensi-guru.scan-token', $qrToken) : null;
@endphp
<div x-data="{ openEdit: false, editForm: { check_in: '', check_out: '', status: 'hadir', keterangan: '' }, editId: null }">
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-primary">Presensi Guru</h2>
        <p class="text-on-surface-variant font-body-md">Presensi otomatis via QR Code — guru scan untuk check-in/check-out</p>
    </div>
</div>

{{-- QR Global --}}
<div class="bg-white rounded-xl border border-outline-variant card-shadow p-6 mb-6">
    <div class="flex flex-col lg:flex-row items-center gap-8">
        <div class="flex-1 text-center lg:text-left">
            <h3 class="font-headline-sm text-headline-sm text-primary flex items-center justify-center lg:justify-start gap-2 mb-2">
                <span class="material-symbols-outlined">qr_code</span>
                QR Code Presensi
            </h3>
            <p class="text-on-surface-variant mb-1">QR Code ini dapat digunakan oleh <strong>semua guru</strong> untuk check-in / check-out setiap hari.</p>
            <p class="text-xs text-on-surface-variant">Tampilkan QR ini di layar agar guru dapat memindai menggunakan kamera ponsel mereka.</p>
            @if($qrToken)
            <div class="mt-4 flex flex-wrap items-center justify-center lg:justify-start gap-3">
                <form method="POST" action="{{ route('admin.presensi-guru.generate-qr') }}">
                    @csrf
                    <button class="px-4 py-2 border border-outline-variant rounded-lg text-sm font-bold text-outline hover:bg-surface-container transition-all flex items-center gap-1">
                        <span class="material-symbols-outlined text-[18px]">refresh</span>
                        Regenerate QR
                    </button>
                </form>
                <button onclick="window.open('{{ route('portal-guru.presensi-guru.scan-token', $qrToken) }}', '_blank')" class="px-4 py-2 bg-secondary-container text-on-secondary-container rounded-lg text-sm font-bold hover:bg-secondary-container/70 transition-all flex items-center gap-1">
                    <span class="material-symbols-outlined text-[18px]">open_in_new</span>
                    Buka Link Scan
                </button>
            </div>
            @endif
        </div>
        <div class="flex-shrink-0">
            @if($qrToken)
            <div class="bg-surface-container-low rounded-xl p-5 text-center">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode($qrUrl) }}" alt="QR Code Presensi" class="w-48 h-48 mx-auto rounded-lg">
                <p class="text-xs text-on-surface-variant mt-3 font-mono tracking-wider">{{ $qrToken }}</p>
            </div>
            @else
            <div class="bg-surface-container-low rounded-xl p-5 w-48 h-48 flex flex-col items-center justify-center text-center">
                <span class="material-symbols-outlined text-5xl text-outline mb-2">qr_code</span>
                <p class="text-xs text-on-surface-variant">QR belum digenerate</p>
                <form method="POST" action="{{ route('admin.presensi-guru.generate-qr') }}" class="mt-3">
                    @csrf
                    <button class="px-4 py-2 bg-primary text-on-primary rounded-lg text-xs font-bold hover:bg-primary-container transition-all">Generate QR</button>
                </form>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Stats --}}
<div class="grid grid-cols-5 gap-gutter mb-6">
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-primary-fixed text-primary flex items-center justify-center"><span class="material-symbols-outlined" style="font-variation-settings:'FILL'1">today</span></div>
        <div><p class="text-xs text-on-surface-variant">Total Presensi</p><h3 class="font-headline-md text-headline-md text-primary">{{ $totalToday }}</h3></div>
    </div>
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-green-100 text-green-700 flex items-center justify-center"><span class="material-symbols-outlined" style="font-variation-settings:'FILL'1">check_circle</span></div>
        <div><p class="text-xs text-on-surface-variant">Hadir</p><h3 class="font-headline-md text-headline-md text-primary">{{ $totalHadir }}</h3></div>
    </div>
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center"><span class="material-symbols-outlined" style="font-variation-settings:'FILL'1">description</span></div>
        <div><p class="text-xs text-on-surface-variant">Izin</p><h3 class="font-headline-md text-headline-md text-primary">{{ $totalIzin }}</h3></div>
    </div>
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-red-100 text-red-700 flex items-center justify-center"><span class="material-symbols-outlined" style="font-variation-settings:'FILL'1">sick</span></div>
        <div><p class="text-xs text-on-surface-variant">Sakit</p><h3 class="font-headline-md text-headline-md text-primary">{{ $totalSakit }}</h3></div>
    </div>
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-gray-100 text-gray-700 flex items-center justify-center"><span class="material-symbols-outlined" style="font-variation-settings:'FILL'1">cancel</span></div>
        <div><p class="text-xs text-on-surface-variant">Alpha</p><h3 class="font-headline-md text-headline-md text-primary">{{ $totalAlpha }}</h3></div>
    </div>
</div>

{{-- Filters --}}
<form method="GET" class="bg-white rounded-xl border border-outline-variant card-shadow p-5 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="font-label-md text-label-md text-on-surface-variant mb-1 block">Tanggal</label>
            <input type="date" name="tanggal" value="{{ $tanggalFilter }}" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary">
        </div>
        <div>
            <label class="font-label-md text-label-md text-on-surface-variant mb-1 block">Status</label>
            <select name="status" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary">
                <option value="">Semua Status</option>
                <option value="hadir" {{ $statusFilter == 'hadir' ? 'selected' : '' }}>Hadir</option>
                <option value="izin" {{ $statusFilter == 'izin' ? 'selected' : '' }}>Izin</option>
                <option value="sakit" {{ $statusFilter == 'sakit' ? 'selected' : '' }}>Sakit</option>
                <option value="alpha" {{ $statusFilter == 'alpha' ? 'selected' : '' }}>Alpha</option>
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
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Nama Guru</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Tanggal</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Check In</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Check Out</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Status</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Keterangan</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant">
                @forelse($semuaPresensi as $i => $presensi)
                <tr class="hover:bg-surface-container transition-colors">
                    <td class="px-6 py-4 font-body-md text-on-surface-variant">{{ $semuaPresensi->firstItem() + $i }}</td>
                    <td class="px-6 py-4 font-body-md font-semibold">{{ $presensi->guru->user->name ?? '-' }}</td>
                    <td class="px-6 py-4">{{ \Carbon\Carbon::parse($presensi->tanggal)->format('d M Y') }}</td>
                    <td class="px-6 py-4">{{ $presensi->check_in ? \Carbon\Carbon::parse($presensi->check_in)->format('H:i') : '-' }}</td>
                    <td class="px-6 py-4">{{ $presensi->check_out ? \Carbon\Carbon::parse($presensi->check_out)->format('H:i') : '-' }}</td>
                    <td class="px-6 py-4">
                        @php
                            $mapStatus = ['hadir' => ['bg-green-100 text-green-700', 'Hadir'], 'izin' => ['bg-amber-100 text-amber-700', 'Izin'], 'sakit' => ['bg-red-100 text-red-700', 'Sakit'], 'alpha' => ['bg-gray-100 text-gray-700', 'Alpha']];
                            $s = $mapStatus[$presensi->status] ?? ['bg-gray-100 text-gray-700', $presensi->status];
                        @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $s[0] }}">{{ $s[1] }}</span>
                    </td>
                    <td class="px-6 py-4 text-body-sm text-on-surface-variant max-w-xs truncate">{{ $presensi->keterangan ?: '-' }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <button @click="openEdit = true; editForm = { check_in: '{{ $presensi->check_in ? \Carbon\Carbon::parse($presensi->check_in)->format('H:i') : '' }}', check_out: '{{ $presensi->check_out ? \Carbon\Carbon::parse($presensi->check_out)->format('H:i') : '' }}', status: '{{ $presensi->status }}', keterangan: '{{ $presensi->keterangan }}' }; editId = {{ $presensi->id }}" class="p-2 hover:bg-surface-container-low rounded-lg">
                                <span class="material-symbols-outlined text-outline">edit</span>
                            </button>
                            <form method="POST" action="{{ route('admin.presensi-guru.destroy', $presensi) }}" @submit.prevent="Swal.fire({title:'Konfirmasi',text:'Yakin ingin menghapus presensi ini?',icon:'warning',showCancelButton:true,confirmButtonText:'Ya, hapus!',cancelButtonText:'Batal'}).then(r=>r.isConfirmed&&$el.submit())">
                                @csrf
                                @method('DELETE')
                                <button class="p-2 hover:bg-error/10 rounded-lg"><span class="material-symbols-outlined text-error">delete</span></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td class="px-6 py-8 text-center text-on-surface-variant" colspan="8">Belum ada data presensi guru.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($semuaPresensi->hasPages())
    <div class="p-4 border-t border-outline-variant">{{ $semuaPresensi->links('vendor.pagination.custom') }}</div>
    @endif
</div>

{{-- Edit Modal --}}
<div x-show="openEdit" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.away="openEdit = false">
    <div class="fixed inset-0 bg-black/40" @click="openEdit = false"></div>
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg p-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-headline-sm text-headline-sm">Edit Presensi</h3>
            <button @click="openEdit = false" class="p-2 hover:bg-surface-container rounded-lg"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form :action="'{{ route('admin.presensi-guru.update', '__ID__') }}'.replace('__ID__', editId)" method="POST" class="space-y-5">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Check In</label>
                    <input x-model="editForm.check_in" type="time" name="check_in" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary">
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Check Out</label>
                    <input x-model="editForm.check_out" type="time" name="check_out" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary">
                </div>
            </div>
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Status</label>
                <select x-model="editForm.status" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" name="status" required>
                    <option value="hadir">Hadir</option>
                    <option value="izin">Izin</option>
                    <option value="sakit">Sakit</option>
                    <option value="alpha">Alpha</option>
                </select>
            </div>
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Keterangan</label>
                <textarea x-model="editForm.keterangan" name="keterangan" rows="3" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary resize-none" placeholder="Opsional"></textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" @click="openEdit = false" class="flex-1 py-3 border border-outline-variant rounded-xl font-label-md hover:bg-surface-container">Batal</button>
                <button type="submit" class="flex-1 py-3 bg-primary text-on-primary rounded-xl font-label-md hover:bg-primary-container">Simpan</button>
            </div>
        </form>
    </div>
</div>
</div>
</x-layouts.admin>
