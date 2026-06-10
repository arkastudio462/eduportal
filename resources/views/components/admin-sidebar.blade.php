<aside :class="sidebarOpen ? 'flex' : 'hidden'" class="fixed left-0 top-0 h-full w-64 flex-col bg-primary md:shadow-none shadow-md md:flex z-50 md:z-0 transition-all duration-300"
        x-data="{         openGroup: localStorage.getItem('sidebarGroups') ? JSON.parse(localStorage.getItem('sidebarGroups')) : { master: {{ request()->routeIs('admin.data-siswa') || request()->routeIs('admin.guru') || request()->routeIs('admin.kelas') || request()->routeIs('admin.jurusan') || request()->routeIs('admin.mapel') ? 'true' : 'false' }}, akademik: {{ request()->routeIs('admin.akademik') || request()->routeIs('admin.nilai') || request()->routeIs('admin.absensi') || request()->routeIs('admin.bank-soal') || request()->routeIs('admin.ujian-online') || request()->routeIs('admin.kurikulum') || request()->routeIs('admin.jadwal-piket') || request()->routeIs('admin.presensi-guru') || request()->routeIs('admin.legger') || request()->routeIs('admin.remedial') || request()->routeIs('admin.rapor') ? 'true' : 'false' }}, kesiswaan: {{ request()->routeIs('admin.izin-siswa') || request()->routeIs('admin.pelanggaran-siswa') || request()->routeIs('admin.mutasi-siswa') || request()->routeIs('admin.beasiswa') || request()->routeIs('admin.konseling') ? 'true' : 'false' }}, kepegawaian: {{ request()->routeIs('admin.kepegawaian') ? 'true' : 'false' }}, konten: {{ request()->routeIs('admin.berita') || request()->routeIs('admin.pengumuman') || request()->routeIs('admin.prestasi') ? 'true' : 'false' }}, manajemen: {{ request()->routeIs('admin.ekskul') || request()->routeIs('admin.semester') || request()->routeIs('admin.keuangan') || request()->routeIs('admin.notifications') ? 'true' : 'false' }}, aset: {{ request()->routeIs('admin.ruang') || request()->routeIs('admin.barang') || request()->routeIs('admin.peminjaman-aset') || request()->routeIs('admin.maintenance-aset') ? 'true' : 'false' }}, ppdb: {{ request()->routeIs('admin.ppdb') ? 'true' : 'false' }}, lainnya: {{ request()->routeIs('admin.kontak') || request()->routeIs('admin.tracer-study') || request()->routeIs('admin.activity-log') || request()->routeIs('admin.pengaturan-website') ? 'true' : 'false' }} }, toggleGroup(name) { this.openGroup[name] = !this.openGroup[name]; localStorage.setItem('sidebarGroups', JSON.stringify(this.openGroup)); } }">
    <div class="p-6 flex items-center gap-3">
        <div class="w-10 h-10 bg-secondary-container rounded-lg flex items-center justify-center">
            <span class="material-symbols-outlined text-on-secondary-container" style="font-variation-settings: 'FILL' 1;">school</span>
        </div>
        <div>
            <h2 class="font-headline-sm text-headline-sm text-on-primary">Admin Sekolah</h2>
            <p class="font-label-md text-label-md text-on-primary-fixed-variant">Manajemen Institusi</p>
        </div>
    </div>
    <nav class="flex-1 overflow-y-auto sidebar-scroll px-4 space-y-1 mt-4">

        {{-- Dashboard --}}
        <a class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.dashboard') ? 'bg-secondary-container text-on-secondary-container rounded-lg scale-95' : 'text-on-primary-fixed-variant hover:bg-primary-container hover:text-white' }} transition-colors duration-200" href="{{ route('admin.dashboard') }}">
            <span class="material-symbols-outlined">dashboard</span>
            <span class="font-label-md text-label-md">Beranda</span>
        </a>

        {{-- Data Master --}}
        <div x-data="{ isActive: {{ request()->routeIs('admin.data-siswa') || request()->routeIs('admin.guru') || request()->routeIs('admin.kelas') || request()->routeIs('admin.jurusan') || request()->routeIs('admin.mapel') ? 'true' : 'false' }} }">
            <button @click="toggleGroup('master')" class="w-full flex items-center gap-3 px-4 py-3 text-on-primary-fixed-variant hover:bg-primary-container hover:text-white transition-colors duration-200 rounded-lg">
                <span class="material-symbols-outlined">storage</span>
                <span class="font-label-md text-label-md flex-1 text-left">Data Master</span>
                <span class="material-symbols-outlined text-sm transition-transform duration-200" :class="openGroup.master ? 'rotate-180' : ''">expand_more</span>
            </button>
            <div x-show="openGroup.master" x-collapse.duration.200ms class="ml-4 space-y-0.5">
                <a class="flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('admin.data-siswa') ? 'bg-secondary-container text-on-secondary-container rounded-lg' : 'text-on-primary-fixed-variant hover:bg-primary-container hover:text-white' }} transition-colors duration-200 text-sm" href="{{ route('admin.data-siswa') }}">
                    <span class="material-symbols-outlined text-lg">group</span>
                    <span>Data Siswa</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('admin.guru') ? 'bg-secondary-container text-on-secondary-container rounded-lg' : 'text-on-primary-fixed-variant hover:bg-primary-container hover:text-white' }} transition-colors duration-200 text-sm" href="{{ route('admin.guru') }}">
                    <span class="material-symbols-outlined text-lg">school</span>
                    <span>Data Guru</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('admin.kelas') ? 'bg-secondary-container text-on-secondary-container rounded-lg' : 'text-on-primary-fixed-variant hover:bg-primary-container hover:text-white' }} transition-colors duration-200 text-sm" href="{{ route('admin.kelas') }}">
                    <span class="material-symbols-outlined text-lg">meeting_room</span>
                    <span>Kelas</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('admin.jurusan') ? 'bg-secondary-container text-on-secondary-container rounded-lg' : 'text-on-primary-fixed-variant hover:bg-primary-container hover:text-white' }} transition-colors duration-200 text-sm" href="{{ route('admin.jurusan') }}">
                    <span class="material-symbols-outlined text-lg">account_balance</span>
                    <span>Jurusan</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('admin.mapel') ? 'bg-secondary-container text-on-secondary-container rounded-lg' : 'text-on-primary-fixed-variant hover:bg-primary-container hover:text-white' }} transition-colors duration-200 text-sm" href="{{ route('admin.mapel') }}">
                    <span class="material-symbols-outlined text-lg">book</span>
                    <span>Mata Pelajaran</span>
                </a>
            </div>
        </div>

        {{-- Akademik --}}
        <div>
            <button @click="toggleGroup('akademik')" class="w-full flex items-center gap-3 px-4 py-3 text-on-primary-fixed-variant hover:bg-primary-container hover:text-white transition-colors duration-200 rounded-lg">
                <span class="material-symbols-outlined">school</span>
                <span class="font-label-md text-label-md flex-1 text-left">Akademik</span>
                <span class="material-symbols-outlined text-sm transition-transform duration-200" :class="openGroup.akademik ? 'rotate-180' : ''">expand_more</span>
            </button>
            <div x-show="openGroup.akademik" x-collapse.duration.200ms class="ml-4 space-y-0.5">
                <a class="flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('admin.akademik') ? 'bg-secondary-container text-on-secondary-container rounded-lg' : 'text-on-primary-fixed-variant hover:bg-primary-container hover:text-white' }} transition-colors duration-200 text-sm" href="{{ route('admin.akademik') }}">
                    <span class="material-symbols-outlined text-lg">menu_book</span>
                    <span>Jadwal Akademik</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('admin.nilai') ? 'bg-secondary-container text-on-secondary-container rounded-lg' : 'text-on-primary-fixed-variant hover:bg-primary-container hover:text-white' }} transition-colors duration-200 text-sm" href="{{ route('admin.nilai') }}">
                    <span class="material-symbols-outlined text-lg">grade</span>
                    <span>Nilai</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('admin.absensi') ? 'bg-secondary-container text-on-secondary-container rounded-lg' : 'text-on-primary-fixed-variant hover:bg-primary-container hover:text-white' }} transition-colors duration-200 text-sm" href="{{ route('admin.absensi') }}">
                    <span class="material-symbols-outlined text-lg">fact_check</span>
                    <span>Absensi</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('admin.bank-soal') ? 'bg-secondary-container text-on-secondary-container rounded-lg' : 'text-on-primary-fixed-variant hover:bg-primary-container hover:text-white' }} transition-colors duration-200 text-sm" href="{{ route('admin.bank-soal') }}">
                    <span class="material-symbols-outlined text-lg">quiz</span>
                    <span>Bank Soal</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('admin.ujian-online') ? 'bg-secondary-container text-on-secondary-container rounded-lg' : 'text-on-primary-fixed-variant hover:bg-primary-container hover:text-white' }} transition-colors duration-200 text-sm" href="{{ route('admin.ujian-online') }}">
                    <span class="material-symbols-outlined text-lg">laptop_mac</span>
                    <span>Ujian Online</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('admin.kurikulum') ? 'bg-secondary-container text-on-secondary-container rounded-lg' : 'text-on-primary-fixed-variant hover:bg-primary-container hover:text-white' }} transition-colors duration-200 text-sm" href="{{ route('admin.kurikulum') }}">
                    <span class="material-symbols-outlined text-lg">menu_book</span>
                    <span>Kurikulum</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('admin.jadwal-piket') ? 'bg-secondary-container text-on-secondary-container rounded-lg' : 'text-on-primary-fixed-variant hover:bg-primary-container hover:text-white' }} transition-colors duration-200 text-sm" href="{{ route('admin.jadwal-piket') }}">
                    <span class="material-symbols-outlined text-lg">assignment_ind</span>
                    <span>Jadwal Piket</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('admin.presensi-guru') ? 'bg-secondary-container text-on-secondary-container rounded-lg' : 'text-on-primary-fixed-variant hover:bg-primary-container hover:text-white' }} transition-colors duration-200 text-sm" href="{{ route('admin.presensi-guru') }}">
                    <span class="material-symbols-outlined text-lg">qr_code_scanner</span>
                    <span>Presensi Guru</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('admin.legger') ? 'bg-secondary-container text-on-secondary-container rounded-lg' : 'text-on-primary-fixed-variant hover:bg-primary-container hover:text-white' }} transition-colors duration-200 text-sm" href="{{ route('admin.legger') }}">
                    <span class="material-symbols-outlined text-lg">assignment</span>
                    <span>Legger Nilai</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('admin.remedial') ? 'bg-secondary-container text-on-secondary-container rounded-lg' : 'text-on-primary-fixed-variant hover:bg-primary-container hover:text-white' }} transition-colors duration-200 text-sm" href="{{ route('admin.remedial') }}">
                    <span class="material-symbols-outlined text-lg">refresh</span>
                    <span>Remedial & Pengayaan</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('admin.rapor') ? 'bg-secondary-container text-on-secondary-container rounded-lg' : 'text-on-primary-fixed-variant hover:bg-primary-container hover:text-white' }} transition-colors duration-200 text-sm" href="{{ route('admin.rapor') }}">
                    <span class="material-symbols-outlined text-lg">description</span>
                    <span>Rapor</span>
                </a>
            </div>
        </div>

        {{-- Kepegawaian --}}
        <div>
            <button @click="toggleGroup('kepegawaian')" class="w-full flex items-center gap-3 px-4 py-3 text-on-primary-fixed-variant hover:bg-primary-container hover:text-white transition-colors duration-200 rounded-lg">
                <span class="material-symbols-outlined">badge</span>
                <span class="font-label-md text-label-md flex-1 text-left">Kepegawaian</span>
                <span class="material-symbols-outlined text-sm transition-transform duration-200" :class="openGroup.kepegawaian ? 'rotate-180' : ''">expand_more</span>
            </button>
            <div x-show="openGroup.kepegawaian" x-collapse.duration.200ms class="ml-4 space-y-0.5">
                <a class="flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('admin.kepegawaian') ? 'bg-secondary-container text-on-secondary-container rounded-lg' : 'text-on-primary-fixed-variant hover:bg-primary-container hover:text-white' }} transition-colors duration-200 text-sm" href="{{ route('admin.kepegawaian') }}">
                    <span class="material-symbols-outlined text-lg">badge</span>
                    <span>Kepegawaian</span>
                </a>
            </div>
        </div>

        {{-- Konten --}}
        <div>
            <button @click="toggleGroup('konten')" class="w-full flex items-center gap-3 px-4 py-3 text-on-primary-fixed-variant hover:bg-primary-container hover:text-white transition-colors duration-200 rounded-lg">
                <span class="material-symbols-outlined">article</span>
                <span class="font-label-md text-label-md flex-1 text-left">Konten</span>
                <span class="material-symbols-outlined text-sm transition-transform duration-200" :class="openGroup.konten ? 'rotate-180' : ''">expand_more</span>
            </button>
            <div x-show="openGroup.konten" x-collapse.duration.200ms class="ml-4 space-y-0.5">
                <a class="flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('admin.berita') ? 'bg-secondary-container text-on-secondary-container rounded-lg' : 'text-on-primary-fixed-variant hover:bg-primary-container hover:text-white' }} transition-colors duration-200 text-sm" href="{{ route('admin.berita') }}">
                    <span class="material-symbols-outlined text-lg">newspaper</span>
                    <span>Berita</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('admin.pengumuman') ? 'bg-secondary-container text-on-secondary-container rounded-lg' : 'text-on-primary-fixed-variant hover:bg-primary-container hover:text-white' }} transition-colors duration-200 text-sm" href="{{ route('admin.pengumuman') }}">
                    <span class="material-symbols-outlined text-lg">campaign</span>
                    <span>Pengumuman</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('admin.prestasi') ? 'bg-secondary-container text-on-secondary-container rounded-lg' : 'text-on-primary-fixed-variant hover:bg-primary-container hover:text-white' }} transition-colors duration-200 text-sm" href="{{ route('admin.prestasi') }}">
                    <span class="material-symbols-outlined text-lg">military_tech</span>
                    <span>Prestasi</span>
                </a>
            </div>
        </div>

        {{-- Kesiswaan --}}
        <div>
            <button @click="toggleGroup('kesiswaan')" class="w-full flex items-center gap-3 px-4 py-3 text-on-primary-fixed-variant hover:bg-primary-container hover:text-white transition-colors duration-200 rounded-lg">
                <span class="material-symbols-outlined">diversity_3</span>
                <span class="font-label-md text-label-md flex-1 text-left">Kesiswaan</span>
                <span class="material-symbols-outlined text-sm transition-transform duration-200" :class="openGroup.kesiswaan ? 'rotate-180' : ''">expand_more</span>
            </button>
            <div x-show="openGroup.kesiswaan" x-collapse.duration.200ms class="ml-4 space-y-0.5">
                <a class="flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('admin.izin-siswa') ? 'bg-secondary-container text-on-secondary-container rounded-lg' : 'text-on-primary-fixed-variant hover:bg-primary-container hover:text-white' }} transition-colors duration-200 text-sm" href="{{ route('admin.izin-siswa') }}">
                    <span class="material-symbols-outlined text-lg">event_note</span>
                    <span>Izin Siswa</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('admin.pelanggaran-siswa') ? 'bg-secondary-container text-on-secondary-container rounded-lg' : 'text-on-primary-fixed-variant hover:bg-primary-container hover:text-white' }} transition-colors duration-200 text-sm" href="{{ route('admin.pelanggaran-siswa') }}">
                    <span class="material-symbols-outlined text-lg">gavel</span>
                    <span>Pelanggaran</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('admin.mutasi-siswa') ? 'bg-secondary-container text-on-secondary-container rounded-lg' : 'text-on-primary-fixed-variant hover:bg-primary-container hover:text-white' }} transition-colors duration-200 text-sm" href="{{ route('admin.mutasi-siswa') }}">
                    <span class="material-symbols-outlined text-lg">swap_horiz</span>
                    <span>Mutasi Siswa</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('admin.beasiswa') ? 'bg-secondary-container text-on-secondary-container rounded-lg' : 'text-on-primary-fixed-variant hover:bg-primary-container hover:text-white' }} transition-colors duration-200 text-sm" href="{{ route('admin.beasiswa') }}">
                    <span class="material-symbols-outlined text-lg">workspace_premium</span>
                    <span>Beasiswa</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('admin.konseling') ? 'bg-secondary-container text-on-secondary-container rounded-lg' : 'text-on-primary-fixed-variant hover:bg-primary-container hover:text-white' }} transition-colors duration-200 text-sm" href="{{ route('admin.konseling') }}">
                    <span class="material-symbols-outlined text-lg">psychology</span>
                    <span>BK (Konseling)</span>
                </a>
            </div>
        </div>

        {{-- Manajemen --}}
        <div>
            <button @click="toggleGroup('manajemen')" class="w-full flex items-center gap-3 px-4 py-3 text-on-primary-fixed-variant hover:bg-primary-container hover:text-white transition-colors duration-200 rounded-lg">
                <span class="material-symbols-outlined">settings</span>
                <span class="font-label-md text-label-md flex-1 text-left">Manajemen</span>
                <span class="material-symbols-outlined text-sm transition-transform duration-200" :class="openGroup.manajemen ? 'rotate-180' : ''">expand_more</span>
            </button>
            <div x-show="openGroup.manajemen" x-collapse.duration.200ms class="ml-4 space-y-0.5">
                <a class="flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('admin.ekskul') ? 'bg-secondary-container text-on-secondary-container rounded-lg' : 'text-on-primary-fixed-variant hover:bg-primary-container hover:text-white' }} transition-colors duration-200 text-sm" href="{{ route('admin.ekskul') }}">
                    <span class="material-symbols-outlined text-lg">sports</span>
                    <span>Ekstrakurikuler</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('admin.semester') ? 'bg-secondary-container text-on-secondary-container rounded-lg' : 'text-on-primary-fixed-variant hover:bg-primary-container hover:text-white' }} transition-colors duration-200 text-sm" href="{{ route('admin.semester') }}">
                    <span class="material-symbols-outlined text-lg">calendar_month</span>
                    <span>Kalender Akademik</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('admin.keuangan') ? 'bg-secondary-container text-on-secondary-container rounded-lg' : 'text-on-primary-fixed-variant hover:bg-primary-container hover:text-white' }} transition-colors duration-200 text-sm" href="{{ route('admin.keuangan') }}">
                    <span class="material-symbols-outlined text-lg">payments</span>
                    <span>Keuangan</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('admin.notifications') ? 'bg-secondary-container text-on-secondary-container rounded-lg' : 'text-on-primary-fixed-variant hover:bg-primary-container hover:text-white' }} transition-colors duration-200 text-sm" href="{{ route('admin.notifications') }}">
                    <span class="material-symbols-outlined text-lg">notifications</span>
                    <span>Notifikasi</span>
                    @php $unreadCount = Auth::user()->unreadNotifications->count(); @endphp
                    @if($unreadCount > 0)
                    <span class="ml-auto bg-error text-on-error text-[10px] font-bold min-w-[20px] h-5 rounded-full flex items-center justify-center px-1">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
                    @endif
                </a>
            </div>
        </div>

        {{-- PPDB --}}
        <div>
            <a class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.ppdb') ? 'bg-secondary-container text-on-secondary-container rounded-lg scale-95' : 'text-on-primary-fixed-variant hover:bg-primary-container hover:text-white' }} transition-colors duration-200" href="{{ route('admin.ppdb') }}">
                <span class="material-symbols-outlined">app_registration</span>
                <span class="font-label-md text-label-md flex-1">PPDB</span>
            </a>
        </div>

        {{-- Aset & Inventaris --}}
        <div>
            <button @click="toggleGroup('aset')" class="w-full flex items-center gap-3 px-4 py-3 text-on-primary-fixed-variant hover:bg-primary-container hover:text-white transition-colors duration-200 rounded-lg">
                <span class="material-symbols-outlined">inventory_2</span>
                <span class="font-label-md text-label-md flex-1 text-left">Aset & Inventaris</span>
                <span class="material-symbols-outlined text-sm transition-transform duration-200" :class="openGroup.aset ? 'rotate-180' : ''">expand_more</span>
            </button>
            <div x-show="openGroup.aset" x-collapse.duration.200ms class="ml-4 space-y-0.5">
                <a class="flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('admin.ruang') ? 'bg-secondary-container text-on-secondary-container rounded-lg' : 'text-on-primary-fixed-variant hover:bg-primary-container hover:text-white' }} transition-colors duration-200 text-sm" href="{{ route('admin.ruang') }}">
                    <span class="material-symbols-outlined text-lg">meeting_room</span>
                    <span>Ruang</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('admin.barang') ? 'bg-secondary-container text-on-secondary-container rounded-lg' : 'text-on-primary-fixed-variant hover:bg-primary-container hover:text-white' }} transition-colors duration-200 text-sm" href="{{ route('admin.barang') }}">
                    <span class="material-symbols-outlined text-lg">inventory_2</span>
                    <span>Barang Inventaris</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('admin.peminjaman-aset') ? 'bg-secondary-container text-on-secondary-container rounded-lg' : 'text-on-primary-fixed-variant hover:bg-primary-container hover:text-white' }} transition-colors duration-200 text-sm" href="{{ route('admin.peminjaman-aset') }}">
                    <span class="material-symbols-outlined text-lg">assignment</span>
                    <span>Peminjaman</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('admin.maintenance-aset') ? 'bg-secondary-container text-on-secondary-container rounded-lg' : 'text-on-primary-fixed-variant hover:bg-primary-container hover:text-white' }} transition-colors duration-200 text-sm" href="{{ route('admin.maintenance-aset') }}">
                    <span class="material-symbols-outlined text-lg">build</span>
                    <span>Maintenance</span>
                </a>
            </div>
        </div>

        {{-- Laporan & Analitik --}}
        <div>
            <a class="flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('admin.laporan') ? 'bg-secondary-container text-on-secondary-container rounded-lg' : 'text-on-primary-fixed-variant hover:bg-primary-container hover:text-white' }} transition-colors duration-200 text-sm" href="{{ route('admin.laporan') }}">
                <span class="material-symbols-outlined text-lg">analytics</span>
                <span class="font-label-md text-label-md">Laporan & Analitik</span>
            </a>
        </div>

        {{-- Lainnya --}}
        <div>
            <button @click="toggleGroup('lainnya')" class="w-full flex items-center gap-3 px-4 py-3 text-on-primary-fixed-variant hover:bg-primary-container hover:text-white transition-colors duration-200 rounded-lg">
                <span class="material-symbols-outlined">more_horiz</span>
                <span class="font-label-md text-label-md flex-1 text-left">Lainnya</span>
                <span class="material-symbols-outlined text-sm transition-transform duration-200" :class="openGroup.lainnya ? 'rotate-180' : ''">expand_more</span>
            </button>
            <div x-show="openGroup.lainnya" x-collapse.duration.200ms class="ml-4 space-y-0.5">
                @php $unreadPesan = \App\Models\Message::whereHas('conversation.participants', fn($q) => $q->where('user_id', Auth::id()))->where('sender_id', '!=', Auth::id())->whereNull('read_at')->count(); @endphp
                <a class="flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('admin.kontak') ? 'bg-secondary-container text-on-secondary-container rounded-lg' : 'text-on-primary-fixed-variant hover:bg-primary-container hover:text-white' }} transition-colors duration-200 text-sm" href="{{ route('admin.kontak') }}">
                    <span class="material-symbols-outlined text-lg">mail</span>
                    <span>Pesan Masuk</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('admin.pesan*') ? 'bg-secondary-container text-on-secondary-container rounded-lg' : 'text-on-primary-fixed-variant hover:bg-primary-container hover:text-white' }} transition-colors duration-200 text-sm" href="{{ route('admin.pesan') }}">
                    <span class="material-symbols-outlined text-lg">chat</span>
                    <span>Pesan Internal</span>
                    @if($unreadPesan > 0)
                    <span class="ml-auto bg-error text-on-error text-[10px] font-bold min-w-[20px] h-5 rounded-full flex items-center justify-center px-1">{{ $unreadPesan > 99 ? '99+' : $unreadPesan }}</span>
                    @endif
                </a>
                <a class="flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('admin.tracer-study') ? 'bg-secondary-container text-on-secondary-container rounded-lg' : 'text-on-primary-fixed-variant hover:bg-primary-container hover:text-white' }} transition-colors duration-200 text-sm" href="{{ route('admin.tracer-study') }}">
                    <span class="material-symbols-outlined text-lg">assignment_return</span>
                    <span>Tracer Study</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('admin.activity-log') ? 'bg-secondary-container text-on-secondary-container rounded-lg' : 'text-on-primary-fixed-variant hover:bg-primary-container hover:text-white' }} transition-colors duration-200 text-sm" href="{{ route('admin.activity-log') }}">
                    <span class="material-symbols-outlined text-lg">history</span>
                    <span>Aktivitas Sistem</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('admin.pengaturan-website') ? 'bg-secondary-container text-on-secondary-container rounded-lg' : 'text-on-primary-fixed-variant hover:bg-primary-container hover:text-white' }} transition-colors duration-200 text-sm" href="{{ route('admin.pengaturan-website') }}">
                    <span class="material-symbols-outlined text-lg">public</span>
                    <span>Pengaturan Website</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('admin.backup') ? 'bg-secondary-container text-on-secondary-container rounded-lg' : 'text-on-primary-fixed-variant hover:bg-primary-container hover:text-white' }} transition-colors duration-200 text-sm" href="{{ route('admin.backup') }}">
                    <span class="material-symbols-outlined text-lg">backup</span>
                    <span>Backup Database</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('admin.file-manager') ? 'bg-secondary-container text-on-secondary-container rounded-lg' : 'text-on-primary-fixed-variant hover:bg-primary-container hover:text-white' }} transition-colors duration-200 text-sm" href="{{ route('admin.file-manager') }}">
                    <span class="material-symbols-outlined text-lg">folder</span>
                    <span>File Manager</span>
                </a>
            </div>
        </div>

    </nav>
    <div class="p-4 border-t border-on-primary-fixed-variant mt-auto">
        <div class="flex items-center gap-3">
            @php $user = auth()->user(); @endphp
            @if($user->profile_photo_path)
            <img src="{{ Storage::url($user->profile_photo_path) }}" alt="{{ $user->name }}" class="w-10 h-10 rounded-full object-cover">
            @else
            <div class="w-10 h-10 rounded-full bg-primary-fixed text-primary flex items-center justify-center font-bold text-xs">{{ substr($user->name, 0, 1) }}</div>
            @endif
            <div>
                <p class="font-label-md text-label-md text-on-primary">{{ $user->name }}</p>
                <p class="text-xs text-on-primary-fixed-variant">{{ $user->email }}</p>
            </div>
        </div>
    </div>
</aside>