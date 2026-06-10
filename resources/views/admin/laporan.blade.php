<x-layouts.admin title="Laporan & Analitik | EduPortal">
    <x-slot:styles>
    <style>
        [x-cloak] { display: none !important; }
        .chart-wrap { position: relative; height: 200px; }
        .chart-wrap canvas { display: block; max-width: 100%; max-height: 100%; }
    </style>
    </x-slot:styles>
<div x-data="{ tab: 'ringkasan' }">
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-primary">Laporan & Analitik</h2>
        <p class="text-on-surface-variant font-body-md">Dashboard monitoring dan ekspor data</p>
    </div>
</div>

{{-- Stat Cards --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-gutter mb-8">
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-primary-fixed text-primary flex items-center justify-center">
            <span class="material-symbols-outlined" style="font-variation-settings:'FILL'1">group</span>
        </div>
        <div>
            <p class="text-xs text-on-surface-variant">Siswa Aktif</p>
            <h3 class="font-headline-md text-headline-md text-primary">{{ $totalSiswa }}</h3>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-secondary-fixed text-secondary flex items-center justify-center">
            <span class="material-symbols-outlined" style="font-variation-settings:'FILL'1">badge</span>
        </div>
        <div>
            <p class="text-xs text-on-surface-variant">Guru</p>
            <h3 class="font-headline-md text-headline-md text-primary">{{ $totalGuru }}</h3>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center">
            <span class="material-symbols-outlined" style="font-variation-settings:'FILL'1">meeting_room</span>
        </div>
        <div>
            <p class="text-xs text-on-surface-variant">Kelas</p>
            <h3 class="font-headline-md text-headline-md text-primary">{{ $totalKelas }}</h3>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-green-100 text-green-700 flex items-center justify-center">
            <span class="material-symbols-outlined" style="font-variation-settings:'FILL'1">book</span>
        </div>
        <div>
            <p class="text-xs text-on-surface-variant">Mata Pelajaran</p>
            <h3 class="font-headline-md text-headline-md text-primary">{{ $totalMapel }}</h3>
        </div>
    </div>
</div>

{{-- Tab Navigation --}}
<div class="flex gap-2 mb-6 border-b border-outline-variant pb-2 overflow-x-auto">
    <button @click="tab = 'ringkasan'" :class="tab === 'ringkasan' ? 'border-b-2 border-primary text-primary' : 'text-on-surface-variant hover:text-primary'" class="px-4 py-2 font-label-md whitespace-nowrap transition-all">Ringkasan</button>
    <button @click="tab = 'ekspor'" :class="tab === 'ekspor' ? 'border-b-2 border-primary text-primary' : 'text-on-surface-variant hover:text-primary'" class="px-4 py-2 font-label-md whitespace-nowrap transition-all">Ekspor PDF</button>
</div>

{{-- Tab: Ringkasan --}}
<div x-show="tab === 'ringkasan'" x-cloak class="space-y-4">
    {{-- Siswa Per Kelas + Per Jurusan --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-gutter">
        <div class="bg-white rounded-xl border border-outline-variant card-shadow p-4">
            <h4 class="font-label-md text-label-md text-primary mb-2">Siswa per Kelas</h4>
            <div class="chart-wrap"><canvas id="chartSiswaKelas"></canvas></div>
        </div>
        <div class="bg-white rounded-xl border border-outline-variant card-shadow p-4">
            <h4 class="font-label-md text-label-md text-primary mb-2">Siswa per Jurusan</h4>
            <div class="chart-wrap"><canvas id="chartSiswaJurusan"></canvas></div>
        </div>
    </div>

    {{-- Kehadiran + Rata-rata Nilai --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-gutter">
        <div class="bg-white rounded-xl border border-outline-variant card-shadow p-4">
            <h4 class="font-label-md text-label-md text-primary mb-2">Kehadiran Bulanan (Tahun Ini)</h4>
            <div class="chart-wrap"><canvas id="chartAbsensi"></canvas></div>
        </div>
        <div class="bg-white rounded-xl border border-outline-variant card-shadow p-4">
            <h4 class="font-label-md text-label-md text-primary mb-2">Rata-rata Nilai per Mapel</h4>
            <div class="chart-wrap"><canvas id="chartNilai"></canvas></div>
        </div>
    </div>

    {{-- SPP Stats --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-gutter">
        <div class="bg-white rounded-xl border border-outline-variant card-shadow p-4">
            <h4 class="font-label-md text-label-md text-primary mb-2">Status Pembayaran SPP</h4>
            <div class="chart-wrap"><canvas id="chartSpp"></canvas></div>
        </div>
    </div>
</div>

{{-- Tab: Ekspor PDF --}}
<div x-show="tab === 'ekspor'" x-cloak class="space-y-6">
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-6">
        <h4 class="font-label-lg text-label-lg text-primary mb-2">Ekspor Data ke PDF</h4>
        <p class="text-body-sm text-on-surface-variant mb-6">Pilih data yang ingin diekspor dalam format PDF.</p>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <a href="{{ route('admin.laporan.siswa-pdf') }}" target="_blank" class="p-4 border border-outline-variant rounded-xl hover:bg-surface-container transition-all flex items-center gap-4">
                <div class="w-10 h-10 rounded-lg bg-primary-fixed text-primary flex items-center justify-center"><span class="material-symbols-outlined">group</span></div>
                <div><p class="font-label-md text-label-md">Data Siswa</p><p class="text-xs text-on-surface-variant">Semua siswa aktif</p></div>
            </a>
            <a href="{{ route('admin.laporan.guru-pdf') }}" target="_blank" class="p-4 border border-outline-variant rounded-xl hover:bg-surface-container transition-all flex items-center gap-4">
                <div class="w-10 h-10 rounded-lg bg-secondary-fixed text-secondary flex items-center justify-center"><span class="material-symbols-outlined">badge</span></div>
                <div><p class="font-label-md text-label-md">Data Guru</p><p class="text-xs text-on-surface-variant">Semua guru & staff</p></div>
            </a>
            <a href="{{ route('admin.laporan.jadwal-pdf') }}" target="_blank" class="p-4 border border-outline-variant rounded-xl hover:bg-surface-container transition-all flex items-center gap-4">
                <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center"><span class="material-symbols-outlined">calendar_month</span></div>
                <div><p class="font-label-md text-label-md">Jadwal Pelajaran</p><p class="text-xs text-on-surface-variant">Semua jadwal</p></div>
            </a>
            <a href="{{ route('admin.laporan.nilai-pdf') }}" target="_blank" class="p-4 border border-outline-variant rounded-xl hover:bg-surface-container transition-all flex items-center gap-4">
                <div class="w-10 h-10 rounded-lg bg-green-100 text-green-700 flex items-center justify-center"><span class="material-symbols-outlined">grade</span></div>
                <div><p class="font-label-md text-label-md">Data Nilai</p><p class="text-xs text-on-surface-variant">Semua nilai siswa</p></div>
            </a>
            <a href="{{ route('admin.laporan.absensi-pdf') }}" target="_blank" class="p-4 border border-outline-variant rounded-xl hover:bg-surface-container transition-all flex items-center gap-4">
                <div class="w-10 h-10 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center"><span class="material-symbols-outlined">fact_check</span></div>
                <div><p class="font-label-md text-label-md">Data Absensi</p><p class="text-xs text-on-surface-variant">Semua catatan absensi</p></div>
            </a>
        </div>
    </div>
</div>
</div>

<x-slot:scripts>
<script>
(function() {
    function initCharts() {
        if (typeof Chart === 'undefined') { setTimeout(initCharts, 50); return; }

        var warna = [
            '#000421', '#A6600C', '#FEAF2C', '#384475', '#8894c9',
            '#633f00', '#0a1a5e', '#f9ba67', '#1a2a5e', '#b8c4fe'
        ];

        var ctx1 = document.getElementById('chartSiswaKelas');
        if (ctx1) {
            new Chart(ctx1, {
                type: 'bar',
                data: {
                    labels: @json($siswaPerKelas->pluck('label')),
                    datasets: [{ label: 'Jumlah Siswa', data: @json($siswaPerKelas->pluck('count')), backgroundColor: '#000421', borderRadius: 6 }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
            });
        }

        var ctx2 = document.getElementById('chartSiswaJurusan');
        if (ctx2) {
            new Chart(ctx2, {
                type: 'pie',
                data: {
                    labels: @json($siswaPerJurusan->pluck('nama')),
                    datasets: [{ data: @json($siswaPerJurusan->pluck('total')), backgroundColor: warna.slice(0, @json($siswaPerJurusan->count())) }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
            });
        }

        var ctx3 = document.getElementById('chartAbsensi');
        if (ctx3) {
            new Chart(ctx3, {
                type: 'line',
                data: {
                    labels: @json($chartAbsensi->pluck('bulan')),
                    datasets: [
                        { label: 'Hadir', data: @json($chartAbsensi->pluck('hadir')), borderColor: '#22c55e', backgroundColor: 'rgba(34,197,94,0.1)', tension: 0.3, fill: true },
                        { label: 'Izin', data: @json($chartAbsensi->pluck('izin')), borderColor: '#3b82f6', backgroundColor: 'rgba(59,130,246,0.1)', tension: 0.3, fill: true },
                        { label: 'Sakit', data: @json($chartAbsensi->pluck('sakit')), borderColor: '#f59e0b', backgroundColor: 'rgba(245,158,11,0.1)', tension: 0.3, fill: true },
                        { label: 'Alfa', data: @json($chartAbsensi->pluck('alfa')), borderColor: '#ef4444', backgroundColor: 'rgba(239,68,68,0.1)', tension: 0.3, fill: true },
                    ]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
            });
        }

        var ctx4 = document.getElementById('chartNilai');
        if (ctx4) {
            new Chart(ctx4, {
                type: 'bar',
                data: {
                    labels: @json($rataNilai->pluck('mapel')),
                    datasets: [{ label: 'Rata-rata', data: @json($rataNilai->pluck('rata')), backgroundColor: '#A6600C', borderRadius: 6 }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, max: 100 } } }
            });
        }

        var ctx5 = document.getElementById('chartSpp');
        if (ctx5) {
            var sppLabels = { 'belum_lunas': 'Belum Lunas', 'lunas': 'Lunas', 'pending': 'Pending' };
            var sppData = @json($sppStats);
            var sppKeys = Object.keys(sppLabels).filter(function(k) { return sppData.hasOwnProperty(k); });
            new Chart(ctx5, {
                type: 'doughnut',
                data: {
                    labels: sppKeys.map(function(k) { return sppLabels[k]; }),
                    datasets: [{ data: sppKeys.map(function(k) { return sppData[k] || 0; }), backgroundColor: ['#ef4444', '#22c55e', '#f59e0b'] }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
            });
        }
    }
    initCharts();
})();
</script>
</x-slot:scripts>
</x-layouts.admin>
