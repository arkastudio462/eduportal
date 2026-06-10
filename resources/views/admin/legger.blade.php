<x-layouts.admin title="Legger Nilai | EduPortal">
    <x-slot:styles>
    <style>[x-cloak] { display: none !important; }</style>
    </x-slot:styles>

@php
    $totalSiswa = $data->count();
    $nilaiAkhirList = $data->pluck('nilai_akhir')->filter();
    $rataKelas = $nilaiAkhirList->count() > 0 ? number_format($nilaiAkhirList->avg(), 2) : '-';
    $tertinggi = $nilaiAkhirList->isNotEmpty() ? number_format($nilaiAkhirList->max(), 2) : '-';
    $terendah = $nilaiAkhirList->isNotEmpty() ? number_format($nilaiAkhirList->min(), 2) : '-';

    $predikat = fn($na) => match (true) {
        $na >= 92 => 'A',
        $na >= 83 => 'B',
        $na >= 75 => 'C',
        $na >= 60 => 'D',
        default => 'E',
    };

    $predikatColor = fn($p) => match ($p) {
        'A' => 'text-green-600 bg-green-100',
        'B' => 'text-blue-700 bg-blue-100',
        'C' => 'text-amber-600 bg-amber-100',
        'D' => 'text-orange-700 bg-orange-100',
        default => 'text-red-600 bg-red-100',
    };
@endphp

<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-primary">Legger Nilai</h2>
        <p class="text-on-surface-variant font-body-md">Rekap nilai per kelas per semester</p>
    </div>
</div>

@if($totalSiswa > 0)
<div class="grid grid-cols-4 gap-gutter mb-6">
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-primary-fixed text-primary flex items-center justify-center"><span class="material-symbols-outlined" style="font-variation-settings:'FILL'1">group</span></div>
        <div><p class="text-xs text-on-surface-variant">Total Siswa</p><h3 class="font-headline-md text-headline-md text-primary">{{ $totalSiswa }}</h3></div>
    </div>
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-secondary-fixed text-secondary flex items-center justify-center"><span class="material-symbols-outlined" style="font-variation-settings:'FILL'1">trending_up</span></div>
        <div><p class="text-xs text-on-surface-variant">Rata-rata Kelas</p><h3 class="font-headline-md text-headline-md text-primary">{{ $rataKelas }}</h3></div>
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
@endif

<form method="GET" action="{{ route('admin.legger') }}" class="bg-white rounded-xl border border-outline-variant card-shadow p-5 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="font-label-md text-label-md text-on-surface-variant mb-1 block">Kelas</label>
            <select name="kelas_id" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary">
                <option value="">-- Pilih Kelas --</option>
                @foreach($kelasList as $kelas)
                <option value="{{ $kelas->id }}" {{ $kelasId == $kelas->id ? 'selected' : '' }}>{{ $kelas->nama }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="font-label-md text-label-md text-on-surface-variant mb-1 block">Mata Pelajaran</label>
            <select name="mapel_id" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary">
                <option value="">-- Pilih Mapel --</option>
                @foreach($mapelList as $mapel)
                <option value="{{ $mapel->id }}" {{ $mapelId == $mapel->id ? 'selected' : '' }}>{{ $mapel->nama }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="font-label-md text-label-md text-on-surface-variant mb-1 block">Semester</label>
            <select name="semester_id" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary">
                <option value="">-- Pilih Semester --</option>
                @foreach($semesterList as $sem)
                <option value="{{ $sem->id }}" {{ $semesterId == $sem->id ? 'selected' : '' }}>{{ $sem->tahun_ajaran }} {{ $sem->semester }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end">
            <button type="submit" class="w-full px-4 py-3 bg-primary text-on-primary rounded-xl flex items-center justify-center gap-2 font-label-md hover:bg-primary-container transition-all active:scale-95">
                <span class="material-symbols-outlined">search</span>
                Tampilkan
            </button>
        </div>
    </div>
</form>

@if($totalSiswa > 0)
<div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-surface-container-low">
                <tr>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant w-12">No</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Nama Siswa</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">NISN</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant text-center">Rata-rata UH</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant text-center">Rata-rata Tugas</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant text-center">UTS</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant text-center">UAS</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant text-center">Nilai Akhir</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant text-center">Predikat</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant">
                @foreach($data as $index => $item)
                @php
                    $na = $item['nilai_akhir'];
                    $p = $na !== null ? $predikat($na) : '-';
                    $pClass = $na !== null ? $predikatColor($p) : 'text-gray-500 bg-gray-100';
                @endphp
                <tr class="hover:bg-surface-container transition-colors {{ $loop->even ? 'bg-surface-container-low/30' : '' }}">
                    <td class="px-6 py-4 text-center text-on-surface-variant">{{ $index + 1 }}</td>
                    <td class="px-6 py-4 font-body-md font-semibold">{{ $item['siswa']->user->name ?? '-' }}</td>
                    <td class="px-6 py-4 text-on-surface-variant">{{ $item['siswa']->nisn ?? '-' }}</td>
                    <td class="px-6 py-4 text-center font-semibold">{{ $item['rata_uh'] !== null ? number_format($item['rata_uh'], 2) : '-' }}</td>
                    <td class="px-6 py-4 text-center font-semibold">{{ $item['rata_tugas'] !== null ? number_format($item['rata_tugas'], 2) : '-' }}</td>
                    <td class="px-6 py-4 text-center font-semibold">{{ $item['uts'] !== null ? number_format($item['uts'], 2) : '-' }}</td>
                    <td class="px-6 py-4 text-center font-semibold">{{ $item['uas'] !== null ? number_format($item['uas'], 2) : '-' }}</td>
                    <td class="px-6 py-4 text-center font-bold text-lg {{ $na !== null ? ($na >= 75 ? 'text-green-600' : ($na >= 60 ? 'text-yellow-600' : 'text-red-600')) : 'text-gray-400' }}">{{ $na !== null ? number_format($na, 2) : '-' }}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-bold {{ $pClass }}">{{ $p }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@else
<div class="bg-white rounded-xl border border-outline-variant card-shadow p-12 text-center">
    <span class="material-symbols-outlined text-5xl text-outline mb-4" style="font-variation-settings:'FILL'1">bar_chart</span>
    <h3 class="font-headline-sm text-headline-sm text-primary mb-2">Belum Ada Data</h3>
    <p class="text-on-surface-variant font-body-md">Pilih kelas, mata pelajaran, dan semester lalu klik <strong>Tampilkan</strong> untuk melihat rekap nilai.</p>
</div>
@endif

</x-layouts.admin>
