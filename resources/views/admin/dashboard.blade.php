<x-layouts.admin title="EduPortal SMA Nusantara - Admin Dashboard">
    <x-slot:styles>
<style>
    body { font-family: 'Source Sans 3', sans-serif; background-color: #f8f9fa; }
    h1, h2, h3, .headline { font-family: 'Work Sans', sans-serif; }
</style>
    </x-slot:styles>
<!-- Welcome Header -->
<section class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-primary">Selamat Datang, Admin!</h2>
        <p class="text-on-surface-variant font-body-md flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">calendar_today</span>
            {{ $tanggalSekarang }}
        </p>
    </div>
    <a href="{{ route('admin.data-siswa') }}" class="bg-primary text-on-primary px-6 py-2.5 rounded-lg flex items-center gap-2 font-label-md hover:bg-primary-container transition-all active:scale-95">
        <span class="material-symbols-outlined">add</span>
        Input Data Baru
    </a>
</section>
<!-- KPI Row -->
<section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-gutter">
    <div class="bg-white p-6 rounded-xl border border-outline-variant card-shadow hover:translate-y-[-4px] transition-transform duration-300">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-on-surface-variant font-label-md mb-1">Total Siswa Aktif</p>
                <h3 class="font-headline-md text-headline-md text-primary">{{ $totalSiswaAktif }}</h3>
            </div>
            <div class="w-12 h-12 rounded-full bg-primary-fixed flex items-center justify-center text-primary">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">group</span>
            </div>
        </div>
        <div class="mt-4 flex items-center gap-1 text-sm font-semibold text-green-600">
            <span class="material-symbols-outlined text-sm">arrow_upward</span>
            <span>4.2%</span>
            <span class="text-on-surface-variant font-normal text-xs ml-1">dari bulan lalu</span>
        </div>
    </div>
    <div class="bg-white p-6 rounded-xl border border-outline-variant card-shadow hover:translate-y-[-4px] transition-transform duration-300">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-on-surface-variant font-label-md mb-1">Guru & Staff</p>
                <h3 class="font-headline-md text-headline-md text-primary">{{ $totalGuru }}</h3>
            </div>
            <div class="w-12 h-12 rounded-full bg-secondary-fixed flex items-center justify-center text-secondary">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">person_pin_circle</span>
            </div>
        </div>
        <div class="mt-4 flex items-center gap-1 text-on-surface-variant text-sm">
            <span class="font-semibold text-primary">12</span>
            <span>Pegawai baru ditambahkan</span>
        </div>
    </div>
    <div class="bg-white p-6 rounded-xl border border-outline-variant card-shadow hover:translate-y-[-4px] transition-transform duration-300">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-on-surface-variant font-label-md mb-1">Kelas Aktif</p>
                <h3 class="font-headline-md text-headline-md text-primary">{{ $totalKelas }}</h3>
            </div>
            <div class="w-12 h-12 rounded-full bg-tertiary-fixed flex items-center justify-center text-on-tertiary-container">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">meeting_room</span>
            </div>
        </div>
        <div class="mt-4 flex items-center gap-1 text-on-surface-variant text-sm">
            <span>Kapasitas Rata-rata: </span>
            <span class="font-semibold text-primary">35 siswa</span>
        </div>
    </div>
    <div class="bg-white p-6 rounded-xl border border-outline-variant card-shadow hover:translate-y-[-4px] transition-transform duration-300">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-on-surface-variant font-label-md mb-1">Kehadiran Hari Ini</p>
                <h3 class="font-headline-md text-headline-md text-primary">{{ $kehadiranHariIni }}%</h3>
            </div>
            <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center text-green-700">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">check_circle</span>
            </div>
        </div>
        <div class="mt-4 w-full bg-surface-container rounded-full h-2 overflow-hidden">
            <div class="bg-secondary-container h-full" style="width: {{ $kehadiranHariIni }}%"></div>
        </div>
    </div>
</section>
<!-- Shortcut Menu -->
<section class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 mb-6">
    <a href="{{ route('admin.data-siswa') }}" class="flex flex-col items-center gap-2 p-4 bg-white rounded-xl border border-outline-variant card-shadow hover:bg-primary-fixed hover:border-primary hover:scale-[1.02] transition-all duration-200">
        <span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">group</span>
        <span class="text-xs font-label-md text-center text-on-surface-variant">Siswa</span>
    </a>
    <a href="{{ route('admin.guru') }}" class="flex flex-col items-center gap-2 p-4 bg-white rounded-xl border border-outline-variant card-shadow hover:bg-primary-fixed hover:border-primary hover:scale-[1.02] transition-all duration-200">
        <span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">school</span>
        <span class="text-xs font-label-md text-center text-on-surface-variant">Guru</span>
    </a>
    <a href="{{ route('admin.kelas') }}" class="flex flex-col items-center gap-2 p-4 bg-white rounded-xl border border-outline-variant card-shadow hover:bg-primary-fixed hover:border-primary hover:scale-[1.02] transition-all duration-200">
        <span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">meeting_room</span>
        <span class="text-xs font-label-md text-center text-on-surface-variant">Kelas</span>
    </a>
    <a href="{{ route('admin.mapel') }}" class="flex flex-col items-center gap-2 p-4 bg-white rounded-xl border border-outline-variant card-shadow hover:bg-primary-fixed hover:border-primary hover:scale-[1.02] transition-all duration-200">
        <span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">book</span>
        <span class="text-xs font-label-md text-center text-on-surface-variant">Mapel</span>
    </a>
    <a href="{{ route('admin.akademik') }}" class="flex flex-col items-center gap-2 p-4 bg-white rounded-xl border border-outline-variant card-shadow hover:bg-primary-fixed hover:border-primary hover:scale-[1.02] transition-all duration-200">
        <span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">calendar_month</span>
        <span class="text-xs font-label-md text-center text-on-surface-variant">Jadwal</span>
    </a>
    <a href="{{ route('admin.bank-soal') }}" class="flex flex-col items-center gap-2 p-4 bg-white rounded-xl border border-outline-variant card-shadow hover:bg-primary-fixed hover:border-primary hover:scale-[1.02] transition-all duration-200">
        <span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">quiz</span>
        <span class="text-xs font-label-md text-center text-on-surface-variant">Bank Soal</span>
    </a>
    <a href="{{ route('admin.ujian-online') }}" class="flex flex-col items-center gap-2 p-4 bg-white rounded-xl border border-outline-variant card-shadow hover:bg-primary-fixed hover:border-primary hover:scale-[1.02] transition-all duration-200">
        <span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">laptop_mac</span>
        <span class="text-xs font-label-md text-center text-on-surface-variant">Ujian</span>
    </a>
    <a href="{{ route('admin.nilai') }}" class="flex flex-col items-center gap-2 p-4 bg-white rounded-xl border border-outline-variant card-shadow hover:bg-primary-fixed hover:border-primary hover:scale-[1.02] transition-all duration-200">
        <span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">grade</span>
        <span class="text-xs font-label-md text-center text-on-surface-variant">Nilai</span>
    </a>
    <a href="{{ route('admin.absensi') }}" class="flex flex-col items-center gap-2 p-4 bg-white rounded-xl border border-outline-variant card-shadow hover:bg-primary-fixed hover:border-primary hover:scale-[1.02] transition-all duration-200">
        <span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">fact_check</span>
        <span class="text-xs font-label-md text-center text-on-surface-variant">Absensi</span>
    </a>
    <a href="{{ route('admin.berita') }}" class="flex flex-col items-center gap-2 p-4 bg-white rounded-xl border border-outline-variant card-shadow hover:bg-primary-fixed hover:border-primary hover:scale-[1.02] transition-all duration-200">
        <span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">newspaper</span>
        <span class="text-xs font-label-md text-center text-on-surface-variant">Berita</span>
    </a>
    <a href="{{ route('admin.ekskul') }}" class="flex flex-col items-center gap-2 p-4 bg-white rounded-xl border border-outline-variant card-shadow hover:bg-primary-fixed hover:border-primary hover:scale-[1.02] transition-all duration-200">
        <span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">sports</span>
        <span class="text-xs font-label-md text-center text-on-surface-variant">Ekskul</span>
    </a>
    <a href="{{ route('admin.pengaturan-website') }}" class="flex flex-col items-center gap-2 p-4 bg-white rounded-xl border border-outline-variant card-shadow hover:bg-primary-fixed hover:border-primary hover:scale-[1.02] transition-all duration-200">
        <span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">public</span>
        <span class="text-xs font-label-md text-center text-on-surface-variant">Website</span>
    </a>
</section>

<!-- Charts Row: Bento Layout -->
<section class="grid grid-cols-1 lg:grid-cols-3 gap-gutter">
    <div class="lg:col-span-2 bg-white p-6 rounded-xl border border-outline-variant card-shadow">
        <div class="flex items-center justify-between mb-8">
            <h3 class="font-headline-sm text-headline-sm">Statistik Kehadiran Mingguan</h3>
            <select class="text-sm border-none bg-surface-container-low rounded-lg px-3 py-1 text-primary focus:ring-1 focus:ring-primary">
                <option>Minggu Ini</option>
                <option>Minggu Lalu</option>
            </select>
        </div>
        <div class="h-64 flex items-end gap-4 md:gap-8 px-4">
            @foreach($weeklyChart as $day => $data)
            <div class="flex-1 flex flex-col items-center group">
                <div class="w-full bg-primary-container/20 rounded-t-lg relative group-hover:bg-primary-container transition-colors" style="height: {{ $data['pct'] }}%">
                    <span class="absolute -top-8 left-1/2 -translate-x-1/2 text-xs font-bold text-primary opacity-0 group-hover:opacity-100 transition-opacity">{{ $data['count'] }} jadwal</span>
                </div>
                <span class="text-xs mt-2 text-outline">{{ $day }}</span>
            </div>
            @endforeach
        </div>
    </div>
    <div class="bg-white p-6 rounded-xl border border-outline-variant card-shadow flex flex-col">
        <h3 class="font-headline-sm text-headline-sm mb-6">Distribusi Kelas</h3>
        <div class="flex-1 flex items-center justify-center relative">
            <div class="w-40 h-40 rounded-full border-[15px] border-primary relative flex items-center justify-center">
                <div class="absolute inset-[-15px] rounded-full border-[15px] border-secondary-container border-l-transparent border-t-transparent rotate-[45deg]"></div>
                <div class="absolute inset-[-15px] rounded-full border-[15px] border-tertiary-fixed-dim border-l-transparent border-b-transparent border-r-transparent rotate-[-45deg]"></div>
                <div class="text-center">
                    <p class="text-xs text-outline">Total</p>
                    <p class="font-bold text-headline-sm">100%</p>
                </div>
            </div>
        </div>
        <div class="mt-6 space-y-2">
            @forelse($distribusiKelas as $item)
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full {{ $item['color'] }}"></div>
                    <span class="text-sm font-label-md">{{ $item['label'] }}</span>
                </div>
                <span class="text-sm font-bold">{{ $item['pct'] }}%</span>
            </div>
            @empty
            <p class="text-sm text-on-surface-variant">Belum ada data kelas.</p>
            @endforelse
        </div>
    </div>
</section>
<!-- Recent Activity Tables -->
<section class="grid grid-cols-1 lg:grid-cols-2 gap-gutter">
    <div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
        <div class="p-6 border-b border-outline-variant flex items-center justify-between">
            <h3 class="font-headline-sm text-headline-sm">Siswa Baru Terdaftar</h3>
            <a class="text-primary text-sm font-semibold hover:underline" href="{{ route('admin.data-siswa') }}">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-surface-container-low">
                    <tr>
                        <th class="px-6 py-4 font-label-md text-on-surface-variant">Nama Siswa</th>
                        <th class="px-6 py-4 font-label-md text-on-surface-variant">Kelas</th>
                        <th class="px-6 py-4 font-label-md text-on-surface-variant">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse($siswaBaru as $siswa)
                    <tr class="{{ $loop->index % 2 == 0 ? '' : 'bg-surface-container-lowest' }} hover:bg-surface-container transition-colors">
                        <td class="px-6 py-4 flex items-center gap-3">
                            <div class="w-8 h-8 bg-primary-fixed text-primary rounded-full flex items-center justify-center font-bold text-xs">{{ substr($siswa->user->name, 0, 1) }}</div>
                            <span class="font-body-md">{{ $siswa->user->name }}</span>
                        </td>
                        <td class="px-6 py-4 font-body-md text-on-surface-variant">{{ $siswa->kelas->nama ?? '-' }}</td>
                        <td class="px-6 py-4 font-body-md text-on-surface-variant">{{ $siswa->created_at->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr><td class="px-6 py-4 text-on-surface-variant" colspan="3">Belum ada data siswa.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="bg-white p-6 rounded-xl border border-outline-variant card-shadow">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-headline-sm text-headline-sm">Ujian Berlangsung Hari Ini</h3>
            <span class="px-2 py-1 bg-error-container text-on-error-container text-xs font-bold rounded animate-pulse">LIVE</span>
        </div>
        <div class="space-y-4">
            @forelse($ujianBerlangsung as $ujian)
            <div class="p-4 rounded-lg bg-surface-container-low border-l-4 border-primary flex items-center justify-between">
                <div>
                    <p class="font-bold text-primary">{{ $ujian->nama }}</p>
                    <p class="text-sm text-on-surface-variant">{{ $ujian->mapel->nama ?? '-' }}</p>
                </div>
                <div class="text-right">
                    <p class="font-label-md text-secondary">{{ $ujian->tanggal_mulai->format('d M') }} - {{ $ujian->tanggal_selesai->format('d M Y') }}</p>
                    <p class="text-xs text-outline">{{ $ujian->durasi }} menit</p>
                </div>
            </div>
            @empty
            <p class="text-on-surface-variant text-sm">Tidak ada ujian berlangsung.</p>
            @endforelse
        </div>
        <button class="w-full mt-6 py-3 border-2 border-dashed border-outline-variant rounded-lg text-outline font-label-md hover:border-primary hover:text-primary transition-all">
            + Tambah Jadwal Ujian
        </button>
    </div>
</section>
<!-- Bottom Row -->
<section class="grid grid-cols-1 lg:grid-cols-3 gap-gutter pb-8">
    <div class="lg:col-span-2 bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
        <div class="p-6 border-b border-outline-variant flex items-center justify-between">
            <h3 class="font-headline-sm text-headline-sm">Pembayaran SPP Terbaru</h3>
            <div class="flex gap-2">
                <button class="p-1 hover:bg-surface-container rounded transition-colors"><span class="material-symbols-outlined text-outline">filter_list</span></button>
                <button class="p-1 hover:bg-surface-container rounded transition-colors"><span class="material-symbols-outlined text-outline">download</span></button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-surface-container-low">
                    <tr>
                        <th class="px-6 py-4 font-label-md text-on-surface-variant">Nama Siswa</th>
                        <th class="px-6 py-4 font-label-md text-on-surface-variant">Jumlah</th>
                        <th class="px-6 py-4 font-label-md text-on-surface-variant">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse($pembayaranTerbaru as $bayar)
                    <tr class="hover:bg-surface-container transition-colors">
                        <td class="px-6 py-4 font-body-md">{{ $bayar->siswa->user->name ?? '-' }}</td>
                        <td class="px-6 py-4 font-body-md font-semibold">Rp {{ number_format($bayar->jumlah, 0, ',', '.') }}</td>
                        <td class="px-6 py-4"><span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold uppercase">{{ $bayar->status }}</span></td>
                    </tr>
                    @empty
                    <tr><td class="px-6 py-4 text-on-surface-variant" colspan="3">Belum ada pembayaran.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="bg-white p-6 rounded-xl border border-outline-variant card-shadow">
        <h3 class="font-headline-sm text-headline-sm mb-6">Pengumuman Aktif</h3>
        <div class="space-y-6">
            @forelse($pengumuman as $ann)
            <div class="relative pl-6 border-l-2 border-secondary-container">
                <p class="text-xs text-outline mb-1">{{ $ann->tanggal->format('d M Y, H:i') }}</p>
                <p class="font-bold text-on-surface leading-tight">{{ $ann->judul }}</p>
                <p class="text-sm text-on-surface-variant mt-1">{{ Str::limit($ann->konten, 60) }}</p>
            </div>
            @empty
            <p class="text-on-surface-variant text-sm">Tidak ada pengumuman.</p>
            @endforelse
        </div>
        <button class="mt-8 text-primary font-semibold text-sm flex items-center gap-1 hover:gap-2 transition-all">
            Semua Pengumuman <span class="material-symbols-outlined text-sm">arrow_forward</span>
        </button>
    </div>
</section>

</x-layouts.admin>
