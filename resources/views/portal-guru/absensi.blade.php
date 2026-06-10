<x-layouts.portal-guru title="Absensi - Portal Guru">
<x-slot:styles>
<style>[x-cloak] { display: none !important; }</style>
</x-slot:styles>
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-primary">Absensi</h2>
        <p class="text-on-surface-variant font-body-md">Kelola kehadiran siswa</p>
    </div>
</div>

<!-- Filter -->
<form method="GET" class="bg-white p-6 rounded-xl border border-outline-variant card-shadow mb-6">
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div>
            <label class="font-label-sm text-label-sm text-outline mb-1 block">Kelas</label>
            <select name="kelas_id" class="w-full px-3 py-2 border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none bg-white" onchange="this.form.submit()">
                @foreach($kelasList as $kelas)
                <option value="{{ $kelas->id }}" {{ ($selectedKelas->id ?? null) == $kelas->id ? 'selected' : '' }}>{{ $kelas->nama }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="font-label-sm text-label-sm text-outline mb-1 block">Tanggal</label>
            <input type="date" name="tanggal" value="{{ $tanggal }}"
                class="w-full px-3 py-2 border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none"
                onchange="this.form.submit()">
        </div>
        <div class="flex items-end">
            <button type="submit" class="w-full px-3 py-2 bg-secondary-container text-on-secondary-container rounded-lg font-label-md hover:bg-secondary-container/70 transition-all">
                Tampilkan
            </button>
        </div>
    </div>
</form>

@if($siswaList->isNotEmpty())
<form method="POST" action="{{ route('portal-guru.absensi.store') }}">
    @csrf
    <input type="hidden" name="kelas_id" value="{{ $selectedKelas->id }}">
    <input type="hidden" name="tanggal" value="{{ $tanggal }}">

    <div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-surface-container-low">
                    <tr>
                        <th class="px-6 py-4 font-label-md text-on-surface-variant">No</th>
                        <th class="px-6 py-4 font-label-md text-on-surface-variant">NISN</th>
                        <th class="px-6 py-4 font-label-md text-on-surface-variant">Nama Siswa</th>
                        <th class="px-6 py-4 font-label-md text-on-surface-variant">Status</th>
                        <th class="px-6 py-4 font-label-md text-on-surface-variant">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @foreach($siswaList as $index => $siswa)
                    @php $abs = $dataAbsensi->get($siswa->id); @endphp
                    <tr class="hover:bg-surface-container transition-colors">
                        <td class="px-6 py-4 font-body-md">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 font-label-md text-on-surface-variant">{{ $siswa->nisn }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-primary-fixed text-primary rounded-full flex items-center justify-center font-bold text-xs">{{ substr($siswa->user->name, 0, 1) }}</div>
                                <span class="font-body-md">{{ $siswa->user->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <select name="absensi[{{ $siswa->id }}][status]" class="px-3 py-2 border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none bg-white">
                                <option value="hadir" {{ ($abs->status ?? '') == 'hadir' ? 'selected' : '' }}>Hadir</option>
                                <option value="sakit" {{ ($abs->status ?? '') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                                <option value="izin" {{ ($abs->status ?? '') == 'izin' ? 'selected' : '' }}>Izin</option>
                                <option value="alpha" {{ ($abs->status ?? '') == 'alpha' ? 'selected' : '' }}>Alpha</option>
                            </select>
                        </td>
                        <td class="px-6 py-4">
                            <input type="text" name="absensi[{{ $siswa->id }}][keterangan]" value="{{ $abs->keterangan ?? '' }}"
                                class="w-full px-3 py-2 border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none"
                                placeholder="Opsional">
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6 flex justify-end">
        <button type="submit" class="px-8 py-3 bg-primary text-on-primary rounded-xl font-label-md hover:bg-primary-container transition-all active:scale-95 flex items-center gap-2">
            <span class="material-symbols-outlined">save</span>
            Simpan Absensi
        </button>
    </div>
</form>
@elseif($selectedKelas)
<div class="bg-white rounded-xl border border-outline-variant card-shadow p-8 text-center">
    <p class="text-on-surface-variant">Tidak ada siswa aktif di kelas ini.</p>
</div>
@else
<div class="bg-white rounded-xl border border-outline-variant card-shadow p-8 text-center">
    <span class="material-symbols-outlined text-5xl text-outline mb-4">person_check</span>
    <h3 class="font-headline-sm text-headline-sm text-on-surface mb-2">Pilih Kelas</h3>
    <p class="text-on-surface-variant">Silakan pilih kelas untuk memulai absensi.</p>
</div>
@endif
</x-layouts.portal-guru>
