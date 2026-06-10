<x-layouts.portal-siswa title="Nilai - Portal Siswa">
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-primary">Nilai</h2>
        <p class="text-on-surface-variant font-body-md">Lihat nilai dan rapor Anda per semester</p>
    </div>
</div>

@if($semesterList->isNotEmpty())
<form method="GET" class="mb-6 flex items-center gap-3">
    <label class="font-label-md text-label-md text-on-surface-variant">Semester:</label>
    <select name="semester" onchange="this.form.submit()"
        class="px-4 py-2 border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none bg-white">
        @foreach($semesterList as $s)
        <option value="{{ $s }}" {{ $semesterAktif == $s ? 'selected' : '' }}>{{ $s }}</option>
        @endforeach
    </select>
</form>

<div class="space-y-6">
    @forelse($nilaiPerMapel as $nm)
    <div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
        <div class="px-6 py-4 bg-primary-fixed border-b border-outline-variant flex items-center justify-between">
            <div>
                <h3 class="font-headline-sm text-headline-sm text-primary">{{ $nm['mapel']->nama }}</h3>
            </div>
            <div class="text-right">
                <span class="text-xs text-on-surface-variant">Rata-rata</span>
                <div class="text-2xl font-bold {{ $nm['rata_rata'] >= 75 ? 'text-green-600' : 'text-error' }}">{{ $nm['rata_rata'] }}</div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-surface-container-low">
                    <tr>
                        <th class="px-6 py-3 font-label-md text-on-surface-variant text-xs">Jenis</th>
                        <th class="px-6 py-3 font-label-md text-on-surface-variant text-xs">Skor</th>
                        <th class="px-6 py-3 font-label-md text-on-surface-variant text-xs">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @foreach($nm['items'] as $n)
                    <tr>
                        <td class="px-6 py-3">
                            <span class="px-2 py-0.5 rounded text-xs font-bold
                                {{ $n->jenis == 'uts' ? 'bg-blue-100 text-blue-700' : '' }}
                                {{ $n->jenis == 'uas' ? 'bg-purple-100 text-purple-700' : '' }}
                                {{ $n->jenis == 'uh' ? 'bg-orange-100 text-orange-700' : '' }}
                                {{ $n->jenis == 'tugas' ? 'bg-green-100 text-green-700' : '' }}
                                uppercase">{{ $n->jenis }}</span>
                            @if($n->ujian_id)
                            <span class="text-xs text-outline ml-2">({{ $n->ujian->nama ?? 'Ujian' }})</span>
                            @endif
                        </td>
                        <td class="px-6 py-3">
                            <span class="font-bold text-lg {{ $n->skor >= 75 ? 'text-green-600' : 'text-error' }}">{{ $n->skor ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-3 text-sm text-on-surface-variant">
                            @php
                                $b = $n->benar; $s = $n->salah; $t = $n->tidak_dijawab;
                            @endphp
                            @if($b || $s || $t)
                                B:{{ $b }} S:{{ $s }} TD:{{ $t }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-8 text-center">
        <span class="material-symbols-outlined text-5xl text-outline mb-4">grade</span>
        <p class="text-on-surface-variant">Belum ada nilai untuk semester ini.</p>
    </div>
    @endforelse
</div>
@else
<div class="bg-white rounded-xl border border-outline-variant card-shadow p-8 text-center">
    <span class="material-symbols-outlined text-5xl text-outline mb-4">grade</span>
    <h3 class="font-headline-sm text-headline-sm text-on-surface mb-2">Belum Ada Nilai</h3>
    <p class="text-on-surface-variant">Data nilai belum tersedia.</p>
</div>
@endif
</x-layouts.portal-siswa>
