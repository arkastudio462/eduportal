<x-layouts.portal-guru title="Dashboard - Portal Guru">
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-primary">Halo, {{ $guru?->user?->name ?? 'Guru' }} 👋</h2>
        <p class="text-on-surface-variant font-body-md">Selamat datang di Portal Guru</p>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('portal-guru.absensi') }}" class="px-4 py-2 border border-outline-variant rounded-lg flex items-center gap-2 text-sm text-outline hover:bg-surface-container transition-all">
            <span class="material-symbols-outlined">today</span>
            Absen Sekarang
        </a>
        <a href="{{ route('portal-guru.ujian-online') }}" class="px-4 py-2 bg-primary text-on-primary rounded-lg flex items-center gap-2 text-sm font-bold hover:bg-primary-container transition-all">
            <span class="material-symbols-outlined">add</span>
            Buat Ujian
        </a>
    </div>
</div>
<!-- KPI -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-gutter mb-8">
    @php
        $rataKelas = $perbandinganNilai->avg('rata_rata') ?? 0;
    @endphp
    @foreach([['Total Siswa Binaan', $totalSiswa, 'group', 'bg-primary-fixed text-primary'],['Kelas Diampu', $kelasDiampu->count(), 'meeting_room', 'bg-secondary-fixed text-secondary'],['Tugas Aktif', $tugasAktif, 'assignment', 'bg-tertiary-fixed text-on-tertiary-container'],['Rata-rata Kelas', number_format($rataKelas, 1), 'trending_up', 'bg-green-100 text-green-700']] as $kpi)
    <div class="bg-white p-5 rounded-xl border border-outline-variant hover:translate-y-[-4px] transition-all duration-300">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl {{ $kpi[3] }} flex items-center justify-center">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">{{ $kpi[2] }}</span>
            </div>
            <div>
                <p class="text-on-surface-variant text-xs font-label-md">{{ $kpi[0] }}</p>
                <h3 class="font-headline-md text-headline-md text-primary">{{ $kpi[1] }}</h3>
            </div>
        </div>
    </div>
    @endforeach
</div>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-gutter mb-8">
    <!-- Jadwal Mengajar -->
    <div class="lg:col-span-2 bg-white rounded-xl border border-outline-variant overflow-hidden">
        <div class="p-6 border-b border-outline-variant flex items-center justify-between">
            <h3 class="font-headline-sm text-headline-sm">Jadwal Mengajar Hari Ini</h3>
            <a class="text-primary text-sm font-bold hover:underline" href="{{ route('portal-guru.jadwal') }}">Atur Jadwal</a>
        </div>
        <div class="divide-y divide-outline-variant">
            @forelse($jadwalHariIni as $jadwal)
            <div class="p-4 hover:bg-surface-container transition-all cursor-pointer">
                <div class="flex items-center gap-4">
                    <div class="w-24 shrink-0">
                        <p class="text-sm font-bold text-primary">{{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}</p>
                    </div>
                    <div class="w-0.5 h-12 bg-outline-variant"></div>
                    <div class="flex-1">
                        <p class="font-bold text-on-surface">{{ $jadwal->mapel->nama ?? '-' }}</p>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-sm text-on-surface-variant">{{ $jadwal->kelas->nama ?? '-' }}</span>
                            <span class="px-2 py-0.5 rounded text-xs font-bold bg-secondary-fixed text-secondary">{{ $jadwal->ruang ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-4 text-on-surface-variant text-sm">Tidak ada jadwal hari ini.</div>
            @endforelse
        </div>
    </div>
    <!-- Aktivitas Terkini -->
    <div class="space-y-6">
        @if($guru && $guru->kelasWali && $guru->kelasWali->isNotEmpty())
        <div class="bg-white p-6 rounded-xl border border-outline-variant">
            <h3 class="font-headline-sm text-headline-sm mb-1">🏫 Wali Kelas</h3>
            <p class="text-body-sm text-on-surface-variant mb-4">Kelas yang diampu sebagai wali</p>
            @foreach($guru->kelasWali as $wk)
            <div class="flex items-center gap-3 p-3 rounded-lg bg-secondary-container/30 mb-2 last:mb-0">
                <div class="w-10 h-10 rounded-full bg-secondary-container flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-secondary">supervisor_account</span>
                </div>
                <div>
                    <p class="font-label-md text-label-md text-on-surface">{{ $wk->nama }}</p>
                    <p class="text-body-xs text-on-surface-variant">{{ $wk->tingkat }} • {{ $wk->jurusanRel?->nama ?? '-' }}</p>
                </div>
            </div>
            @endforeach
        </div>
        @endif
        <div class="bg-white p-6 rounded-xl border border-outline-variant">
            <h3 class="font-headline-sm text-headline-sm mb-4">⚡ Aktivitas Terkini</h3>
            <div class="space-y-4">
                @forelse($aktivitasTerkini as $act)
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-full bg-primary-fixed text-primary flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">campaign</span>
                    </div>
                    <div>
                        <p class="text-sm font-semibold">{{ $act->judul }}</p>
                        <p class="text-xs text-outline mt-0.5">{{ $act->tanggal->diffForHumans() }}</p>
                    </div>
                </div>
                @empty
                <p class="text-sm text-on-surface-variant">Belum ada aktivitas terbaru.</p>
                @endforelse
            </div>
        </div>
        <div class="bg-white p-6 rounded-xl border border-outline-variant">
            <h3 class="font-headline-sm text-headline-sm mb-4">📋 Kelas yang Diampu</h3>
            <div class="space-y-3">
                @forelse($kelasDiampu as $kelas)
                <div class="flex items-center gap-3 p-3 rounded-lg bg-surface-container-low">
                    <div class="w-1.5 h-10 rounded-full bg-primary"></div>
                    <div class="flex-1">
                        <p class="font-bold text-sm">{{ $kelas->nama ?? $kelas }}</p>
                        <p class="text-xs text-on-surface-variant"> {{ $guru->mata_pelajaran }}</p>
                    </div>
                    <span class="material-symbols-outlined text-outline text-sm">chevron_right</span>
                </div>
                @empty
                <p class="text-sm text-on-surface-variant">Belum ada kelas.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
<!-- Perbandingan Nilai -->
<div class="bg-white rounded-xl border border-outline-variant p-6 mb-8">
    <div class="flex items-center justify-between mb-6">
        <h3 class="font-headline-sm text-headline-sm">Perbandingan Nilai Per Kelas</h3>
        <select class="border border-outline-variant rounded-lg px-3 py-1.5 text-sm bg-white focus:ring-2 focus:ring-primary focus:border-primary outline-none">
            <option>Semester Ganjil 2024</option>
            <option>Semester Genap 2024</option>
        </select>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead><tr><th class="py-3 font-label-md text-on-surface-variant">Kelas</th><th class="py-3 font-label-md text-on-surface-variant">Rata-rata</th><th class="py-3 font-label-md text-on-surface-variant">Tertinggi</th><th class="py-3 font-label-md text-on-surface-variant">Terendah</th><th class="py-3 font-label-md text-on-surface-variant">Lulus</th><th class="py-3 font-label-md text-on-surface-variant">Tidak Lulus</th></tr></thead>
            <tbody class="divide-y divide-outline-variant">
                @forelse($perbandinganNilai as $row)
                <tr class="hover:bg-surface-container transition-colors">
                    <td class="py-4 font-body-md font-semibold">{{ $row->kelas }}</td>
                    <td class="py-4"><span class="font-bold text-primary">{{ number_format($row->rata_rata, 1) }}</span></td>
                    <td class="py-4">{{ $row->tertinggi }}</td>
                    <td class="py-4">{{ $row->terendah }}</td>
                    <td class="py-4 text-green-600 font-bold">{{ $row->lulus }}</td>
                    <td class="py-4 text-error font-bold">{{ $row->tidak_lulus }}</td>
                </tr>
                @empty
                <tr><td class="py-4 text-on-surface-variant" colspan="6">Belum ada data nilai.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-gutter pb-8">
    <div class="bg-white rounded-xl border border-outline-variant p-6">
        <h3 class="font-headline-sm text-headline-sm mb-4">📤 Ujian yang Periksa</h3>
        @forelse($tugasPeriksa as $tgs)
        <div class="mb-4 last:mb-0">
            <div class="flex justify-between text-sm mb-1">
                <span class="font-semibold">{{ $tgs['nama'] }}</span>
                <span>{{ $tgs['sudah'] }} dari {{ $tgs['total'] }} siswa</span>
            </div>
            <div class="w-full bg-surface-container rounded-full h-2 overflow-hidden">
                <div class="bg-secondary-container h-full rounded-full" style="width: {{ $tgs['total'] > 0 ? round($tgs['sudah'] / $tgs['total'] * 100) : 0 }}%"></div>
            </div>
        </div>
        @empty
        <p class="text-sm text-on-surface-variant">Belum ada ujian yang perlu diperiksa.</p>
        @endforelse
    </div>
    <div class="bg-white rounded-xl border border-outline-variant p-6">
        <h3 class="font-headline-sm text-headline-sm mb-4">📅 Ujian Mendatang</h3>
        <div class="space-y-4">
            @forelse($agendaMendatang as $agenda)
            <div class="flex items-start gap-3">
                <div class="w-12 shrink-0 text-center">
                    <p class="text-xs text-outline">{{ $agenda->tanggal_mulai->isoFormat('MMM') }}</p>
                    <p class="font-bold text-primary">{{ $agenda->tanggal_mulai->isoFormat('DD') }}</p>
                </div>
                <div class="w-0.5 h-full min-h-[3rem] bg-outline-variant"></div>
                <div>
                    <p class="font-bold text-sm">{{ $agenda->nama }}</p>
                    <p class="text-xs text-on-surface-variant">{{ $agenda->mapel->nama ?? '-' }} • {{ $agenda->durasi }} menit</p>
                </div>
            </div>
            @empty
            <p class="text-sm text-on-surface-variant">Tidak ada ujian mendatang.</p>
            @endforelse
        </div>
    </div>
</div>
</x-layouts.portal-guru>
