<x-layouts.portal-siswa title="Dashboard - Portal Siswa">
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-primary">Halo, {{ $siswa?->user?->name ?? 'Siswa' }} 👋</h2>
        <p class="text-on-surface-variant font-body-md">Selamat datang di Portal Siswa</p>
    </div>
    <div class="bg-primary-fixed text-primary px-4 py-2 rounded-lg text-sm font-bold">
        📅 Semester Ganjil 2024/2025
    </div>
</div>
<!-- KPI -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-gutter mb-8">
    @php
        $rataRata = $rataNilaiMapel->avg('rata_rata') ?? 0;
    @endphp
    @foreach([['Nilai Rata-rata', number_format($rataRata, 1), 'trending_up', 'bg-secondary-fixed text-secondary'],['Total Tugas', $totalTugas . ' tugas', 'assignment', 'bg-primary-fixed text-primary'],['Kehadiran', $kehadiran . '%', 'event_available', 'bg-green-100 text-green-700'],['Poin Prestasi', $poinPrestasi, 'stars', 'bg-yellow-100 text-yellow-700']] as $kpi)
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
    <!-- Jadwal Hari Ini -->
    <div class="lg:col-span-2 bg-white rounded-xl border border-outline-variant overflow-hidden">
        <div class="p-6 border-b border-outline-variant flex items-center justify-between">
            <h3 class="font-headline-sm text-headline-sm">Jadwal Hari Ini</h3>
            <a class="text-primary text-sm font-bold hover:underline" href="{{ route('portal-siswa.jadwal') }}">Lihat Semua</a>
        </div>
        <div class="divide-y divide-outline-variant">
            @forelse($jadwalHariIni as $jadwal)
            <div class="p-4 hover:bg-surface-container transition-colors">
                <div class="flex items-center gap-4">
                    <div class="text-center min-w-[60px]">
                        <p class="font-bold text-sm text-primary">{{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }}</p>
                        <p class="text-xs text-outline">{{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}</p>
                    </div>
                    <div class="w-0.5 h-12 bg-outline-variant"></div>
                    <div class="flex-1">
                        <p class="font-bold text-on-surface">{{ $jadwal->mapel->nama ?? '-' }}</p>
                        <div class="flex items-center gap-2 mt-1">
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
    <!-- Pengumuman & Aktivitas -->
    <div class="space-y-6">
        @if($siswa && $siswa->kelas && $siswa->kelas->waliKelas)
        <div class="bg-white p-6 rounded-xl border border-outline-variant">
            <h3 class="font-headline-sm text-headline-sm mb-1">🏫 Wali Kelas</h3>
            <p class="text-body-sm text-on-surface-variant mb-4">Kelas {{ $siswa->kelas->nama }}</p>
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-secondary-container flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-secondary">supervisor_account</span>
                </div>
                <div>
                    <p class="font-label-md text-label-md text-on-surface">{{ $siswa->kelas->waliKelas->user->name }}</p>
                    <p class="text-body-sm text-on-surface-variant">{{ $siswa->kelas->waliKelas->mata_pelajaran }}</p>
                </div>
            </div>
        </div>
        @endif
        <div class="bg-white p-6 rounded-xl border border-outline-variant">
            <h3 class="font-headline-sm text-headline-sm mb-4">📢 Pengumuman</h3>
            <div class="space-y-4">
                @forelse($pengumuman as $ann)
                <div class="pb-4 border-b border-outline-variant last:border-b-0 last:pb-0">
                    <p class="text-xs text-outline mb-1">{{ $ann->tanggal->diffForHumans() }}</p>
                    <p class="font-bold text-sm">{{ $ann->judul }}</p>
                    <p class="text-sm text-on-surface-variant">{{ Str::limit($ann->konten, 50) }}</p>
                </div>
                @empty
                <p class="text-sm text-on-surface-variant">Tidak ada pengumuman.</p>
                @endforelse
            </div>
        </div>
        <div class="bg-white p-6 rounded-xl border border-outline-variant">
            <h3 class="font-headline-sm text-headline-sm mb-4">📊 Rata-rata Nilai</h3>
            <div class="space-y-3">
                @forelse($rataNilaiMapel as $mapel)
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span>{{ $mapel->mapel }}</span>
                        <span class="font-bold">{{ number_format($mapel->rata_rata, 1) }}</span>
                    </div>
                    <div class="w-full bg-surface-container rounded-full h-2 overflow-hidden">
                        <div class="bg-secondary-container h-full rounded-full" style="width: {{ $mapel->rata_rata }}%"></div>
                    </div>
                </div>
                @empty
                <p class="text-sm text-on-surface-variant">Belum ada nilai.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
<!-- Ujian Mendatang -->
<div class="bg-white rounded-xl border border-outline-variant overflow-hidden mb-8">
    <div class="p-6 border-b border-outline-variant flex items-center justify-between">
        <h3 class="font-headline-sm text-headline-sm">📝 Ujian Mendatang</h3>
        <a class="text-primary text-sm font-bold hover:underline" href="{{ route('portal-siswa.ujian-online') }}">Lihat Semua</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-surface-container-low">
                <tr>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Mata Pelajaran</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Tanggal</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Durasi</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Ruang</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant">
                @forelse($ujianMendatang as $ujian)
                <tr class="hover:bg-surface-container transition-colors">
                    <td class="px-6 py-4 font-body-md font-semibold">{{ $ujian->nama }}</td>
                    <td class="px-6 py-4 font-body-md">{{ $ujian->tanggal_mulai->format('d M Y') }}</td>
                    <td class="px-6 py-4 font-body-md">{{ $ujian->durasi }} Menit</td>
                    <td class="px-6 py-4 font-body-md">-</td>
                    <td class="px-6 py-4"><span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-bold">{{ $ujian->status }}</span></td>
                </tr>
                @empty
                <tr><td class="px-6 py-4 text-on-surface-variant" colspan="5">Tidak ada ujian mendatang.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</x-layouts.portal-siswa>
