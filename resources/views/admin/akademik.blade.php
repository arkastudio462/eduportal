<x-layouts.admin title="Akademik | EduPortal">
    <x-slot:styles>
    <style>
        [x-cloak] { display: none !important; }
    </style>
    </x-slot:styles>
<div x-data="{ openModal: false, editMode: false, deleteModal: false, deleteUrl: '', form: { kelas_id: '', mapel_id: '', guru_id: '', hari: 'Senin', jam_mulai: '07:00', jam_selesai: '08:00', ruang: '' }, editId: null }">
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-primary">Akademik</h2>
        <p class="text-on-surface-variant font-body-md">Kelola jadwal pelajaran</p>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('admin.laporan.jadwal-pdf') }}" target="_blank" class="px-4 py-2.5 border border-outline-variant rounded-lg flex items-center gap-2 font-label-md text-outline hover:bg-surface-container transition-all">
            <span class="material-symbols-outlined">print</span>
            Cetak PDF
        </a>
        <button @click="openModal = true; editMode = false; form = { kelas_id: '', mapel_id: '', guru_id: '', hari: 'Senin', jam_mulai: '07:00', jam_selesai: '08:00', ruang: '' }; editId = null" class="bg-primary text-on-primary px-6 py-2.5 rounded-lg flex items-center gap-2 font-label-md hover:bg-primary-container transition-all active:scale-95">
            <span class="material-symbols-outlined">add</span>
            Tambah Jadwal
        </button>
    </div>
</div>

<div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-surface-container-low">
                <tr>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Hari</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Jam</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Kelas</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Mata Pelajaran</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Guru</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Ruang</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant">
                @forelse($semuaJadwal as $jadwal)
                <tr class="hover:bg-surface-container transition-colors">
                    <td class="px-6 py-4 font-body-md font-semibold">{{ $jadwal->hari }}</td>
                    <td class="px-6 py-4">{{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}</td>
                    <td class="px-6 py-4">{{ $jadwal->kelas->nama ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $jadwal->mapel->nama ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $jadwal->guru->user->name ?? '-' }}</td>
                    <td class="px-6 py-4"><span class="px-2 py-0.5 bg-secondary-fixed text-secondary rounded text-xs font-bold">{{ $jadwal->ruang ?? '-' }}</span></td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <button @click="openModal = true; editMode = true; form = { kelas_id: '{{ $jadwal->kelas_id }}', mapel_id: '{{ $jadwal->mapel_id }}', guru_id: '{{ $jadwal->guru_id }}', hari: '{{ $jadwal->hari }}', jam_mulai: '{{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }}', jam_selesai: '{{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}', ruang: '{{ $jadwal->ruang ?? '' }}' }; editId = {{ $jadwal->id }}" class="p-2 hover:bg-surface-container-low rounded-lg">
                                <span class="material-symbols-outlined text-outline">edit</span>
                            </button>
                            <button @click="deleteUrl = '{{ route('admin.akademik.destroy', $jadwal) }}'; deleteModal = true" class="p-2 hover:bg-error/10 rounded-lg">
                                <span class="material-symbols-outlined text-error">delete</span>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td class="px-6 py-8 text-center text-on-surface-variant" colspan="7">Belum ada data jadwal.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div x-show="openModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.away="openModal = false">
    <div class="fixed inset-0 bg-black/40" @click="openModal = false"></div>
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg p-8 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-headline-sm text-headline-sm" x-text="editMode ? 'Edit Jadwal' : 'Tambah Jadwal'"></h3>
            <button @click="openModal = false" class="p-2 hover:bg-surface-container rounded-lg"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form :action="editMode ? '{{ route('admin.akademik.update', '__ID__') }}'.replace('__ID__', editId) : '{{ route('admin.akademik.store') }}'" method="POST" class="space-y-4">
            @csrf
            <template x-if="editMode"><input type="hidden" name="_method" value="PUT"></template>
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Kelas</label>
                    <select x-model="form.kelas_id" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" name="kelas_id" required>
                        <option value="">Pilih Kelas</option>
                        @foreach($semuaKelas as $kelas)
                        <option value="{{ $kelas->id }}">{{ $kelas->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Mata Pelajaran</label>
                    <select x-model="form.mapel_id" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" name="mapel_id" required>
                        <option value="">Pilih Mapel</option>
                        @foreach($semuaMapel as $mapel)
                        <option value="{{ $mapel->id }}">{{ $mapel->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Guru</label>
                <select x-model="form.guru_id" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" name="guru_id" required>
                    <option value="">Pilih Guru</option>
                    @foreach($semuaGuru as $guru)
                    <option value="{{ $guru->id }}">{{ $guru->user->name }} ({{ $guru->mata_pelajaran }})</option>
                    @endforeach
                </select>
            </div>
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Hari</label>
                <select x-model="form.hari" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" name="hari" required>
                    @foreach($hariList as $hari)
                    <option value="{{ $hari }}">{{ $hari }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Jam Mulai</label>
                    <input x-model="form.jam_mulai" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" type="time" name="jam_mulai" required>
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Jam Selesai</label>
                    <input x-model="form.jam_selesai" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" type="time" name="jam_selesai" required>
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Ruang</label>
                    <input x-model="form.ruang" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" placeholder="R-01" type="text" name="ruang">
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

<!-- Delete Modal -->
<div x-show="deleteModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.away="deleteModal = false">
    <div class="fixed inset-0 bg-black/40" @click="deleteModal = false"></div>
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
        <div class="flex items-center gap-4 mb-4">
            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                <span class="material-symbols-outlined text-red-600 text-2xl">delete_forever</span>
            </div>
            <div>
                <h3 class="font-headline-sm text-headline-sm">Konfirmasi Hapus</h3>
                <p class="text-sm text-gray-500 mt-1">Apakah Anda yakin ingin menghapus jadwal ini? Tindakan ini tidak dapat dibatalkan.</p>
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
</x-layouts.admin>
