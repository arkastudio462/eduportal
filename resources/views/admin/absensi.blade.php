<x-layouts.admin title="Absensi | EduPortal">
    <x-slot:styles>
    <style>[x-cloak] { display: none !important; }</style>
    </x-slot:styles>
<div x-data="{ openModal: false, editMode: false, form: { siswa_id: '', tanggal: '{{ now()->format('Y-m-d') }}', status: 'hadir', keterangan: '' }, editId: null }">
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-primary">Absensi</h2>
        <p class="text-on-surface-variant font-body-md">Kelola kehadiran siswa</p>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('admin.export.absensi') }}" class="px-4 py-2.5 border border-outline-variant rounded-lg flex items-center gap-2 font-label-md text-outline hover:bg-surface-container transition-all">
            <span class="material-symbols-outlined">download</span>
            Export
        </a>
        <button @click="openModal = true; editMode = false; form = { siswa_id: '', tanggal: '{{ now()->format('Y-m-d') }}', status: 'hadir', keterangan: '' }; editId = null" class="px-4 py-2.5 bg-primary text-on-primary rounded-lg flex items-center gap-2 font-label-md hover:bg-primary-container transition-all active:scale-95">
            <span class="material-symbols-outlined">add</span>
            Catat Absensi
        </button>
    </div>
</div>

{{-- Stats --}}
<div class="grid grid-cols-4 gap-gutter mb-6">
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-green-100 text-green-700 flex items-center justify-center"><span class="material-symbols-outlined" style="font-variation-settings:'FILL'1">check_circle</span></div>
        <div><p class="text-xs text-on-surface-variant">Hadir</p><h3 class="font-headline-md text-headline-md text-primary">{{ $totalHadir }}</h3></div>
    </div>
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-yellow-100 text-yellow-700 flex items-center justify-center"><span class="material-symbols-outlined" style="font-variation-settings:'FILL'1">sick</span></div>
        <div><p class="text-xs text-on-surface-variant">Sakit</p><h3 class="font-headline-md text-headline-md text-primary">{{ $totalSakit }}</h3></div>
    </div>
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center"><span class="material-symbols-outlined" style="font-variation-settings:'FILL'1">description</span></div>
        <div><p class="text-xs text-on-surface-variant">Izin</p><h3 class="font-headline-md text-headline-md text-primary">{{ $totalIzin }}</h3></div>
    </div>
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-red-100 text-red-700 flex items-center justify-center"><span class="material-symbols-outlined" style="font-variation-settings:'FILL'1">cancel</span></div>
        <div><p class="text-xs text-on-surface-variant">Alpha</p><h3 class="font-headline-md text-headline-md text-primary">{{ $totalAlpha }}</h3></div>
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
                <option value="{{ $kelas->id }}" {{ $kelasId == $kelas->id ? 'selected' : '' }}>{{ $kelas->nama }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="font-label-md text-label-md text-on-surface-variant mb-1 block">Tanggal Mulai</label>
            <input type="date" name="tanggal_mulai" value="{{ $tanggalMulai }}" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" onchange="this.form.submit()">
        </div>
        <div>
            <label class="font-label-md text-label-md text-on-surface-variant mb-1 block">Tanggal Selesai</label>
            <input type="date" name="tanggal_selesai" value="{{ $tanggalSelesai }}" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" onchange="this.form.submit()">
        </div>
        <div>
            <label class="font-label-md text-label-md text-on-surface-variant mb-1 block">Status</label>
            <select name="status" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="hadir" {{ $statusFilter == 'hadir' ? 'selected' : '' }}>Hadir</option>
                <option value="sakit" {{ $statusFilter == 'sakit' ? 'selected' : '' }}>Sakit</option>
                <option value="izin" {{ $statusFilter == 'izin' ? 'selected' : '' }}>Izin</option>
                <option value="alpha" {{ $statusFilter == 'alpha' ? 'selected' : '' }}>Alpha</option>
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
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Nama Siswa</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Kelas</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Tanggal</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Status</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Keterangan</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant">
                @forelse($semuaAbsensi as $absensi)
                <tr class="hover:bg-surface-container transition-colors">
                    <td class="px-6 py-4 font-body-md font-semibold">{{ $absensi->siswa->user->name ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $absensi->siswa->kelas->nama ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $absensi->tanggal->format('d M Y') }}</td>
                    <td class="px-6 py-4">
                        @php
                            $mapStatus = ['hadir' => ['bg-green-100 text-green-700', 'Hadir'], 'sakit' => ['bg-yellow-100 text-yellow-700', 'Sakit'], 'izin' => ['bg-blue-100 text-blue-700', 'Izin'], 'alpha' => ['bg-red-100 text-red-700', 'Alpha']];
                            $s = $mapStatus[$absensi->status] ?? ['bg-gray-100 text-gray-700', $absensi->status];
                        @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $s[0] }}">{{ $s[1] }}</span>
                    </td>
                    <td class="px-6 py-4 text-body-sm text-on-surface-variant">{{ $absensi->keterangan ?: '-' }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <button @click="openModal = true; editMode = true; form = { siswa_id: '{{ $absensi->siswa_id }}', tanggal: '{{ $absensi->tanggal->format('Y-m-d') }}', status: '{{ $absensi->status }}', keterangan: '{{ $absensi->keterangan }}' }; editId = {{ $absensi->id }}" class="p-2 hover:bg-surface-container-low rounded-lg">
                                <span class="material-symbols-outlined text-outline">edit</span>
                            </button>
                            <form method="POST" action="{{ route('admin.absensi.destroy', $absensi) }}" @submit.prevent="Swal.fire({title:'Konfirmasi',text:'Yakin ingin menghapus absensi ini?',icon:'warning',showCancelButton:true,confirmButtonText:'Ya, hapus!',cancelButtonText:'Batal'}).then(r=>r.isConfirmed&&$el.submit())">
                                @csrf
                                @method('DELETE')
                                <button class="p-2 hover:bg-error/10 rounded-lg"><span class="material-symbols-outlined text-error">delete</span></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td class="px-6 py-8 text-center text-on-surface-variant" colspan="6">Belum ada data absensi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($semuaAbsensi->hasPages())
    <div class="p-4 border-t border-outline-variant">{{ $semuaAbsensi->links('vendor.pagination.custom') }}</div>
    @endif
</div>

{{-- Modal --}}
<div x-show="openModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.away="openModal = false">
    <div class="fixed inset-0 bg-black/40" @click="openModal = false"></div>
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg p-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-headline-sm text-headline-sm" x-text="editMode ? 'Edit Absensi' : 'Catat Absensi'"></h3>
            <button @click="openModal = false" class="p-2 hover:bg-surface-container rounded-lg"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form :action="editMode ? '{{ route('admin.absensi.update', '__ID__') }}'.replace('__ID__', editId) : '{{ route('admin.absensi.store') }}'" method="POST" class="space-y-5">
            @csrf
            <template x-if="editMode"><input type="hidden" name="_method" value="PUT"></template>
            <div class="space-y-2" x-show="!editMode">
                <label class="font-label-md text-label-md text-on-surface-variant">Siswa</label>
                <select x-model="form.siswa_id" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" name="siswa_id" :required="!editMode">
                    <option value="">-- Pilih Siswa --</option>
                    @foreach($daftarSiswa as $siswa)
                    <option value="{{ $siswa->id }}">{{ $siswa->user->name }} ({{ $siswa->kelas->nama ?? 'Tanpa Kelas' }})</option>
                    @endforeach
                </select>
            </div>
            <div class="space-y-2" x-show="!editMode">
                <label class="font-label-md text-label-md text-on-surface-variant">Tanggal</label>
                <input x-model="form.tanggal" type="date" name="tanggal" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" :required="!editMode">
            </div>
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Status</label>
                <select x-model="form.status" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" name="status" required>
                    <option value="hadir">Hadir</option>
                    <option value="sakit">Sakit</option>
                    <option value="izin">Izin</option>
                    <option value="alpha">Alpha</option>
                </select>
            </div>
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Keterangan</label>
                <input x-model="form.keterangan" type="text" name="keterangan" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" placeholder="Opsional">
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" @click="openModal = false" class="flex-1 py-3 border border-outline-variant rounded-xl font-label-md hover:bg-surface-container">Batal</button>
                <button type="submit" class="flex-1 py-3 bg-primary text-on-primary rounded-xl font-label-md hover:bg-primary-container" x-text="editMode ? 'Simpan' : 'Catat'"></button>
            </div>
        </form>
    </div>
</div>
</div>
</x-layouts.admin>
