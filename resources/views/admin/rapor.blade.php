<x-layouts.admin title="Rapor | EduPortal">
    <x-slot:styles>
    <style>[x-cloak] { display: none !important; }</style>
    </x-slot:styles>
<div x-data="{ aktif: null }">
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-primary">Generasi Rapor</h2>
        <p class="text-on-surface-variant font-body-md">Cetak rapor otomatis per kelas</p>
    </div>
</div>

{{-- Stats --}}
@if($dataSiswa->isNotEmpty())
@php
    $totalSiswa = $dataSiswa->count();
    $rataKelas = $dataSiswa->pluck('rata_rata')->filter()->avg();
@endphp
<div class="grid grid-cols-2 md:grid-cols-4 gap-gutter mb-6">
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-primary-fixed text-primary flex items-center justify-center"><span class="material-symbols-outlined" style="font-variation-settings:'FILL'1">group</span></div>
        <div><p class="text-xs text-on-surface-variant">Total Siswa</p><h3 class="font-headline-md text-headline-md text-primary">{{ $totalSiswa }}</h3></div>
    </div>
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-secondary-fixed text-secondary flex items-center justify-center"><span class="material-symbols-outlined" style="font-variation-settings:'FILL'1">trending_up</span></div>
        <div><p class="text-xs text-on-surface-variant">Rata-rata Kelas</p><h3 class="font-headline-md text-headline-md text-primary">{{ $rataKelas ? number_format($rataKelas, 1) : '-' }}</h3></div>
    </div>
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center"><span class="material-symbols-outlined" style="font-variation-settings:'FILL'1">auto_stories</span></div>
        <div><p class="text-xs text-on-surface-variant">Kelas</p><h3 class="font-headline-md text-headline-md text-primary">{{ $kelasList->firstWhere('id', $kelasId)?->nama ?? '-' }}</h3></div>
    </div>
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-green-100 text-green-700 flex items-center justify-center"><span class="material-symbols-outlined" style="font-variation-settings:'FILL'1">calendar_month</span></div>
        <div><p class="text-xs text-on-surface-variant">Semester</p><h3 class="font-headline-md text-headline-md text-primary">{{ $semesterList->firstWhere('id', $semesterId)?->nama ?? '-' }}</h3></div>
    </div>
</div>
@endif

{{-- Filter --}}
<form method="GET" class="bg-white rounded-xl border border-outline-variant card-shadow p-5 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
        <div>
            <label class="font-label-md text-label-md text-on-surface-variant mb-1 block">Kelas</label>
            <select name="kelas_id" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary">
                <option value="">Pilih Kelas</option>
                @foreach($kelasList as $kelas)
                <option value="{{ $kelas->id }}" {{ $kelasId == $kelas->id ? 'selected' : '' }}>{{ $kelas->nama }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="font-label-md text-label-md text-on-surface-variant mb-1 block">Semester</label>
            <select name="semester_id" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary">
                <option value="">Pilih Semester</option>
                @foreach($semesterList as $sem)
                <option value="{{ $sem->id }}" {{ $semesterId == $sem->id ? 'selected' : '' }}>{{ $sem->nama }} ({{ $sem->semester }})</option>
                @endforeach
            </select>
        </div>
        <div>
            <button type="submit" class="w-full px-6 py-3 bg-primary text-on-primary rounded-xl flex items-center justify-center gap-2 font-label-md hover:bg-primary-container transition-all active:scale-95">
                <span class="material-symbols-outlined">search</span>
                Tampilkan
            </button>
        </div>
    </div>
</form>

{{-- Student List --}}
@if($dataSiswa->isEmpty())
<div class="bg-white rounded-xl border border-outline-variant card-shadow p-8 text-center">
    <span class="material-symbols-outlined text-5xl text-outline mb-4">description</span>
    <p class="text-on-surface-variant">Pilih kelas dan semester, lalu klik Tampilkan.</p>
</div>
@else
<div class="space-y-4">
    @php
        $predikatWarna = ['A' => 'bg-green-100 text-green-700', 'B' => 'bg-blue-100 text-blue-700', 'C' => 'bg-amber-100 text-amber-700', 'D' => 'bg-orange-100 text-orange-700', 'E' => 'bg-red-100 text-red-700'];
    @endphp
    @foreach($dataSiswa as $idx => $item)
    @php
        $siswa = $item['siswa'];
        $rataRata = $item['rata_rata'];
        $rataHuruf = $item['rata_huruf'];
        $warna = $predikatWarna[$rataHuruf] ?? 'bg-gray-100 text-gray-700';
    @endphp
    <div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
        <button @click="aktif = (aktif === {{ $idx }}) ? null : {{ $idx }}" class="w-full flex items-center justify-between p-5 hover:bg-surface-container-low transition-colors text-left">
            <div class="flex items-center gap-4 flex-1 min-w-0">
                <div class="w-10 h-10 rounded-full bg-primary-fixed text-primary flex items-center justify-center font-bold text-sm shrink-0">{{ strtoupper(substr($siswa->user->name ?? '?', 0, 1)) }}</div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-3 flex-wrap">
                        <h4 class="font-headline-sm text-headline-sm text-primary truncate">{{ $siswa->user->name ?? '-' }}</h4>
                        <span class="px-3 py-0.5 rounded-full text-xs font-bold {{ $warna }}">{{ $rataHuruf ?: '-' }}</span>
                    </div>
                    <p class="text-body-sm text-on-surface-variant">NISN {{ $siswa->nisn ?? '-' }} &middot; {{ $siswa->kelas->nama ?? '-' }}</p>
                </div>
            </div>
            <div class="flex items-center gap-6 shrink-0">
                <div class="text-right hidden sm:block">
                    <p class="text-xs text-on-surface-variant">Rata-rata</p>
                    <p class="font-bold text-lg text-primary">{{ $rataRata ? number_format($rataRata, 1) : '-' }}</p>
                </div>
                <span class="material-symbols-outlined text-outline transition-transform duration-300" :class="aktif === {{ $idx }} ? 'rotate-180' : ''">expand_more</span>
            </div>
        </button>

        <div x-show="aktif === {{ $idx }}" x-cloak x-collapse>
            <div class="border-t border-outline-variant">
                {{-- Mapel Table --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-surface-container-low">
                            <tr>
                                <th class="px-4 py-3 font-label-md text-on-surface-variant text-xs">Mapel</th>
                                <th class="px-4 py-3 font-label-md text-on-surface-variant text-xs">Rata UH</th>
                                <th class="px-4 py-3 font-label-md text-on-surface-variant text-xs">Rata Tugas</th>
                                <th class="px-4 py-3 font-label-md text-on-surface-variant text-xs">UTS</th>
                                <th class="px-4 py-3 font-label-md text-on-surface-variant text-xs">UAS</th>
                                <th class="px-4 py-3 font-label-md text-on-surface-variant text-xs">NA</th>
                                <th class="px-4 py-3 font-label-md text-on-surface-variant text-xs">Predikat</th>
                                <th class="px-4 py-3 font-label-md text-on-surface-variant text-xs">Deskripsi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant">
                            @foreach($item['mapel'] as $m)
                            @php $mWarna = $predikatWarna[$m['huruf']] ?? 'bg-gray-100 text-gray-700'; @endphp
                            <tr class="hover:bg-surface-container transition-colors">
                                <td class="px-4 py-3 font-semibold text-sm">{{ $m['mapel'] }}</td>
                                <td class="px-4 py-3 text-sm">{{ $m['uh'] ? number_format($m['uh'], 1) : '-' }}</td>
                                <td class="px-4 py-3 text-sm">{{ $m['tugas'] ? number_format($m['tugas'], 1) : '-' }}</td>
                                <td class="px-4 py-3 text-sm">{{ $m['uts'] ? number_format($m['uts'], 1) : '-' }}</td>
                                <td class="px-4 py-3 text-sm">{{ $m['uas'] ? number_format($m['uas'], 1) : '-' }}</td>
                                <td class="px-4 py-3 font-bold text-sm">{{ $m['na'] ? number_format($m['na'], 1) : '-' }}</td>
                                <td class="px-4 py-3"><span class="px-2 py-0.5 rounded text-xs font-bold {{ $mWarna }}">{{ $m['huruf'] ?: '-' }}</span></td>
                                <td class="px-4 py-3 text-xs text-on-surface-variant max-w-xs truncate">{{ $m['deskripsi'] ?: '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Action --}}
                <div class="px-4 py-3 bg-surface-container-low border-t border-outline-variant flex justify-end">
                    <a href="{{ route('admin.laporan.rapor-pdf', [$siswa->id, 'semester_id' => $semesterId]) }}" target="_blank" class="px-4 py-2 bg-primary text-on-primary rounded-lg flex items-center gap-2 font-label-md text-sm hover:bg-primary-container transition-all active:scale-95">
                        <span class="material-symbols-outlined">print</span>
                        Print Rapor
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif
</div>
</x-layouts.admin>
