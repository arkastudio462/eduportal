<x-layouts.admin title="Kelas | EduPortal">
    <x-slot:styles>
    <style>
        [x-cloak] { display: none !important; }
    </style>
    </x-slot:styles>
<div x-data="{ openModal: false, editMode: false, form: { nama: '', tingkat: '10', jurusan_id: '', wali_kelas_id: '' }, editId: null }">
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-primary">Kelas</h2>
        <p class="text-on-surface-variant font-body-md">Kelola kelas, wali kelas, dan penempatan siswa</p>
    </div>
    <button @click="openModal = true; editMode = false; form = { nama: '', tingkat: '10', jurusan_id: '', wali_kelas_id: '' }; editId = null" class="bg-primary text-on-primary px-6 py-2.5 rounded-lg flex items-center gap-2 font-label-md hover:bg-primary-container transition-all active:scale-95">
        <span class="material-symbols-outlined">add</span>
        Tambah Kelas
    </button>
</div>

<div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-surface-container-low">
                <tr>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Nama Kelas</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Tingkat</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Jurusan</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Wali Kelas</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Jumlah Siswa</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant">
                @forelse($semuaKelas as $kelas)
                <tr class="hover:bg-surface-container transition-colors">
                    <td class="px-6 py-4 font-body-md font-semibold">{{ $kelas->nama }}</td>
                    <td class="px-6 py-4">Kelas {{ $kelas->tingkat }}</td>
                    <td class="px-6 py-4">{{ $kelas->jurusanRel?->nama ?? '-' }}</td>
                    <td class="px-6 py-4">
                        @if($kelas->waliKelas)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-secondary-container text-secondary rounded-full text-body-sm font-medium">
                                <span class="material-symbols-outlined text-[16px]">supervisor_account</span>
                                {{ $kelas->waliKelas->user->name }}
                            </span>
                        @else
                            <span class="text-on-surface-variant text-body-sm">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">{{ $kelas->siswa_count }} siswa</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <button @click="openModal = true; editMode = true; form = { nama: '{{ $kelas->nama }}', tingkat: '{{ $kelas->tingkat }}', jurusan_id: '{{ $kelas->jurusan_id }}', wali_kelas_id: '{{ $kelas->wali_kelas_id }}' }; editId = {{ $kelas->id }}" class="p-2 hover:bg-surface-container-low rounded-lg transition-colors">
                                <span class="material-symbols-outlined text-outline">edit</span>
                            </button>
                            <form method="POST" action="{{ route('admin.kelas.destroy', $kelas) }}" @submit.prevent="Swal.fire({title:'Konfirmasi',text:'Yakin ingin menghapus kelas {{ $kelas->nama }}?',icon:'warning',showCancelButton:true,confirmButtonText:'Ya, hapus!',cancelButtonText:'Batal'}).then(r=>r.isConfirmed&&$el.submit())">
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
                <tr><td class="px-6 py-8 text-center text-on-surface-variant" colspan="6">Belum ada data kelas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Create/Edit -->
<div x-show="openModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.away="openModal = false">
    <div class="fixed inset-0 bg-black/40" @click="openModal = false"></div>
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg p-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-headline-sm text-headline-sm" x-text="editMode ? 'Edit Kelas' : 'Tambah Kelas'"></h3>
            <button @click="openModal = false" class="p-2 hover:bg-surface-container rounded-lg">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form :action="editMode ? '{{ route('admin.kelas.update', '__ID__') }}'.replace('__ID__', editId) : '{{ route('admin.kelas.store') }}'" method="POST" class="space-y-5">
            @csrf
            <template x-if="editMode">
                <input type="hidden" name="_method" value="PUT">
            </template>
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Nama Kelas</label>
                <input x-model="form.nama" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10" placeholder="Contoh: X IPA 1" type="text" name="nama" required>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Tingkat</label>
                    <select x-model="form.tingkat" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" name="tingkat" required>
                        <option value="10">Kelas 10</option>
                        <option value="11">Kelas 11</option>
                        <option value="12">Kelas 12</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Jurusan</label>
                    <select x-model="form.jurusan_id" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" name="jurusan_id" required>
                        <option value="">-- Pilih Jurusan --</option>
                        @foreach($semuaJurusan as $j)
                        <option value="{{ $j->id }}">{{ $j->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Wali Kelas</label>
                <select x-model="form.wali_kelas_id" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" name="wali_kelas_id">
                    <option value="">-- Pilih Wali Kelas --</option>
                    @foreach($semuaGuru as $guru)
                    <option value="{{ $guru->id }}">{{ $guru->user->name }} ({{ $guru->mata_pelajaran }})</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" @click="openModal = false" class="flex-1 py-3 border border-outline-variant rounded-xl font-label-md hover:bg-surface-container transition-all">Batal</button>
                <button type="submit" class="flex-1 py-3 bg-primary text-on-primary rounded-xl font-label-md hover:bg-primary-container transition-all" x-text="editMode ? 'Simpan' : 'Tambah'"></button>
            </div>
        </form>
    </div>
    </div>
</div>
</x-layouts.admin>
