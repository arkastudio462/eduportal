<x-layouts.admin title="Nilai | EduPortal">
    <x-slot:styles>
    <style>[x-cloak] { display: none !important; }</style>
    </x-slot:styles>
<div x-data="{ openModal: false, editMode: false, form: { siswa_id: '', ujian_id: '', mapel_id: '', jenis: 'uh', semester: '{{ $semesterList->first() ?? (now()->year . '/' . (now()->year + 1) . ' Ganjil') }}', skor: '', benar: '', salah: '' }, editId: null }">
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-primary">Nilai</h2>
        <p class="text-on-surface-variant font-body-md">Kelola nilai siswa</p>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('admin.export.nilai') }}" class="px-4 py-2.5 border border-outline-variant rounded-lg flex items-center gap-2 font-label-md text-outline hover:bg-surface-container transition-all">
            <span class="material-symbols-outlined">download</span>
            Export
        </a>
        <button @click="openModal = true; editMode = false; form = { siswa_id: '', ujian_id: '', mapel_id: '', jenis: 'uh', semester: '{{ $semesterList->first() ?? (now()->year . '/' . (now()->year + 1) . ' Ganjil') }}', skor: '', benar: '', salah: '' }; editId = null" class="px-4 py-2.5 bg-primary text-on-primary rounded-lg flex items-center gap-2 font-label-md hover:bg-primary-container transition-all active:scale-95">
            <span class="material-symbols-outlined">add</span>
            Tambah Nilai
        </button>
    </div>
</div>

{{-- Stats --}}
<div class="grid grid-cols-4 gap-gutter mb-6">
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-primary-fixed text-primary flex items-center justify-center"><span class="material-symbols-outlined" style="font-variation-settings:'FILL'1">grade</span></div>
        <div><p class="text-xs text-on-surface-variant">Total Nilai</p><h3 class="font-headline-md text-headline-md text-primary">{{ $totalNilai }}</h3></div>
    </div>
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-secondary-fixed text-secondary flex items-center justify-center"><span class="material-symbols-outlined" style="font-variation-settings:'FILL'1">trending_up</span></div>
        <div><p class="text-xs text-on-surface-variant">Rata-rata</p><h3 class="font-headline-md text-headline-md text-primary">{{ number_format($rataRata, 1) }}</h3></div>
    </div>
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-green-100 text-green-700 flex items-center justify-center"><span class="material-symbols-outlined" style="font-variation-settings:'FILL'1">arrow_upward</span></div>
        <div><p class="text-xs text-on-surface-variant">Tertinggi</p><h3 class="font-headline-md text-headline-md text-primary">{{ $tertinggi }}</h3></div>
    </div>
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-red-100 text-red-700 flex items-center justify-center"><span class="material-symbols-outlined" style="font-variation-settings:'FILL'1">arrow_downward</span></div>
        <div><p class="text-xs text-on-surface-variant">Terendah</p><h3 class="font-headline-md text-headline-md text-primary">{{ $terendah }}</h3></div>
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
            <label class="font-label-md text-label-md text-on-surface-variant mb-1 block">Mata Pelajaran</label>
            <select name="mapel_id" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" onchange="this.form.submit()">
                <option value="">Semua Mapel</option>
                @foreach($mapelList as $mapel)
                <option value="{{ $mapel->id }}" {{ $mapelId == $mapel->id ? 'selected' : '' }}>{{ $mapel->nama }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="font-label-md text-label-md text-on-surface-variant mb-1 block">Jenis</label>
            <select name="jenis" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" onchange="this.form.submit()">
                <option value="">Semua Jenis</option>
                <option value="uh" {{ $jenis == 'uh' ? 'selected' : '' }}>Ulangan Harian</option>
                <option value="uts" {{ $jenis == 'uts' ? 'selected' : '' }}>UTS</option>
                <option value="uas" {{ $jenis == 'uas' ? 'selected' : '' }}>UAS</option>
                <option value="tugas" {{ $jenis == 'tugas' ? 'selected' : '' }}>Tugas</option>
            </select>
        </div>
        <div>
            <label class="font-label-md text-label-md text-on-surface-variant mb-1 block">Semester</label>
            <select name="semester" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" onchange="this.form.submit()">
                <option value="">Semua Semester</option>
                @foreach($semesterList as $sem)
                <option value="{{ $sem }}" {{ $semester == $sem ? 'selected' : '' }}>{{ $sem }}</option>
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
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Nama Siswa</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Kelas</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Mapel</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Jenis</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Semester</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Skor</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant">
                @forelse($semuaNilai as $nilai)
                <tr class="hover:bg-surface-container transition-colors">
                    <td class="px-6 py-4 font-body-md font-semibold">{{ $nilai->siswa->user->name ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $nilai->siswa->kelas->nama ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $nilai->mapel->nama ?? '-' }}</td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-secondary-fixed text-secondary">{{ strtoupper($nilai->jenis) }}</span>
                    </td>
                    <td class="px-6 py-4 text-body-sm">{{ $nilai->semester }}</td>
                    <td class="px-6 py-4">
                        @php $skorColor = $nilai->skor >= 75 ? 'text-green-600' : ($nilai->skor >= 60 ? 'text-yellow-600' : 'text-red-600'); @endphp
                        <span class="font-bold text-lg {{ $skorColor }}">{{ number_format($nilai->skor, 1) }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <button @click="openModal = true; editMode = true; form = { siswa_id: '{{ $nilai->siswa_id }}', ujian_id: '{{ $nilai->ujian_id }}', mapel_id: '{{ $nilai->mapel_id }}', jenis: '{{ $nilai->jenis }}', semester: '{{ $nilai->semester }}', skor: '{{ $nilai->skor }}', benar: '{{ $nilai->benar }}', salah: '{{ $nilai->salah }}' }; editId = {{ $nilai->id }}" class="p-2 hover:bg-surface-container-low rounded-lg">
                                <span class="material-symbols-outlined text-outline">edit</span>
                            </button>
                            <form method="POST" action="{{ route('admin.nilai.destroy', $nilai) }}" @submit.prevent="Swal.fire({title:'Konfirmasi',text:'Yakin ingin menghapus nilai ini?',icon:'warning',showCancelButton:true,confirmButtonText:'Ya, hapus!',cancelButtonText:'Batal'}).then(r=>r.isConfirmed&&$el.submit())">
                                @csrf
                                @method('DELETE')
                                <button class="p-2 hover:bg-error/10 rounded-lg"><span class="material-symbols-outlined text-error">delete</span></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td class="px-6 py-8 text-center text-on-surface-variant" colspan="7">Belum ada data nilai.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($semuaNilai->hasPages())
    <div class="p-4 border-t border-outline-variant">{{ $semuaNilai->links('vendor.pagination.custom') }}</div>
    @endif
</div>

{{-- Modal --}}
<div x-show="openModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.away="openModal = false">
    <div class="fixed inset-0 bg-black/40" @click="openModal = false"></div>
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg p-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-headline-sm text-headline-sm" x-text="editMode ? 'Edit Nilai' : 'Tambah Nilai'"></h3>
            <button @click="openModal = false" class="p-2 hover:bg-surface-container rounded-lg"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form :action="editMode ? '{{ route('admin.nilai.update', '__ID__') }}'.replace('__ID__', editId) : '{{ route('admin.nilai.store') }}'" method="POST" class="space-y-5">
            @csrf
            <template x-if="editMode"><input type="hidden" name="_method" value="PUT"></template>
            <div class="space-y-2" x-show="!editMode">
                <label class="font-label-md text-label-md text-on-surface-variant">Siswa</label>
                <select x-model="form.siswa_id" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" name="siswa_id" :required="!editMode">
                    <option value="">-- Pilih Siswa --</option>
                    @foreach($daftarSiswa as $siswa)
                    <option value="{{ $siswa->id }}">{{ $siswa->user->name }} ({{ $siswa->kelas->nama ?? '-' }})</option>
                    @endforeach
                </select>
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
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Jenis</label>
                    <select x-model="form.jenis" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" name="jenis" required>
                        <option value="uh">Ulangan Harian</option>
                        <option value="uts">UTS</option>
                        <option value="uas">UAS</option>
                        <option value="tugas">Tugas</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Semester</label>
                    <input x-model="form.semester" type="text" name="semester" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" placeholder="2023/2024 Ganjil" required>
                </div>
            </div>
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Skor</label>
                <input x-model="form.skor" type="number" name="skor" min="0" max="100" step="0.1" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" required>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Benar</label>
                    <input x-model="form.benar" type="number" name="benar" min="0" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" placeholder="Opsional">
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Salah</label>
                    <input x-model="form.salah" type="number" name="salah" min="0" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" placeholder="Opsional">
                </div>
            </div>
            <div class="space-y-2" x-show="!editMode">
                <label class="font-label-md text-label-md text-on-surface-variant">Ujian (Opsional)</label>
                <select x-model="form.ujian_id" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" name="ujian_id">
                    <option value="">-- Pilih Ujian --</option>
                    @foreach($daftarUjian as $ujian)
                    <option value="{{ $ujian->id }}">{{ $ujian->nama }} ({{ $ujian->mapel->nama ?? '-' }})</option>
                    @endforeach
                </select>
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
