<aside class="fixed top-0 left-0 h-screen w-64 bg-primary text-on-primary flex flex-col py-base px-4 gap-2 overflow-y-auto hidden md:flex z-40">
    <div class="px-4 py-6 mb-4">
        <h2 class="font-headline-sm text-headline-sm text-secondary-fixed">Portal Akademik</h2>
        <p class="font-label-md text-label-md opacity-70">Sistem Informasi Sekolah</p>
    </div>
    <nav class="flex flex-col gap-1">
        <a class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('portal-siswa.dashboard') ? 'bg-secondary-container text-on-secondary-container' : 'text-on-primary/70 hover:bg-primary-container' }} rounded-lg font-label-md transition-all active:scale-95" href="{{ route('portal-siswa.dashboard') }}">
            <span class="material-symbols-outlined">dashboard</span>
            <span>Dashboard</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('portal-siswa.jadwal') ? 'bg-secondary-container text-on-secondary-container' : 'text-on-primary/70 hover:bg-primary-container' }} rounded-lg font-label-md transition-all active:scale-95" href="{{ route('portal-siswa.jadwal') }}">
            <span class="material-symbols-outlined">calendar_month</span>
            <span>Jadwal Pelajaran</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('portal-siswa.nilai') ? 'bg-secondary-container text-on-secondary-container' : 'text-on-primary/70 hover:bg-primary-container' }} rounded-lg font-label-md transition-all active:scale-95" href="{{ route('portal-siswa.nilai') }}">
            <span class="material-symbols-outlined">grade</span>
            <span>Nilai</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('portal-siswa.absensi') ? 'bg-secondary-container text-on-secondary-container' : 'text-on-primary/70 hover:bg-primary-container' }} rounded-lg font-label-md transition-all active:scale-95" href="{{ route('portal-siswa.absensi') }}">
            <span class="material-symbols-outlined">person_check</span>
            <span>Absensi</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('portal-siswa.tugas') ? 'bg-secondary-container text-on-secondary-container' : 'text-on-primary/70 hover:bg-primary-container' }} rounded-lg font-label-md transition-all active:scale-95" href="{{ route('portal-siswa.tugas') }}">
            <span class="material-symbols-outlined">assignment</span>
            <span>Tugas</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('portal-siswa.ujian-online') ? 'bg-secondary-container text-on-secondary-container' : 'text-on-primary/70 hover:bg-primary-container' }} rounded-lg font-label-md transition-all active:scale-95" href="{{ route('portal-siswa.ujian-online') }}">
            <span class="material-symbols-outlined">quiz</span>
            <span>Ujian Online</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('portal-siswa.modul') ? 'bg-secondary-container text-on-secondary-container' : 'text-on-primary/70 hover:bg-primary-container' }} rounded-lg font-label-md transition-all active:scale-95" href="{{ route('portal-siswa.modul') }}">
            <span class="material-symbols-outlined">folder</span>
            <span>Modul Ajar</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('portal-siswa.ekskul') ? 'bg-secondary-container text-on-secondary-container' : 'text-on-primary/70 hover:bg-primary-container' }} rounded-lg font-label-md transition-all active:scale-95" href="{{ route('portal-siswa.ekskul') }}">
            <span class="material-symbols-outlined">sports</span>
            <span>Ekstrakurikuler</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('portal-siswa.perpustakaan') ? 'bg-secondary-container text-on-secondary-container' : 'text-on-primary/70 hover:bg-primary-container' }} rounded-lg font-label-md transition-all active:scale-95" href="{{ route('portal-siswa.perpustakaan') }}">
            <span class="material-symbols-outlined">library_books</span>
            <span>Perpustakaan</span>
        </a>
        @php $unreadPesan = \App\Models\Message::whereHas('conversation.participants', fn($q) => $q->where('user_id', Auth::id()))->where('sender_id', '!=', Auth::id())->whereNull('read_at')->count(); @endphp
        <a class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('portal-siswa.pesan*') ? 'bg-secondary-container text-on-secondary-container' : 'text-on-primary/70 hover:bg-primary-container' }} rounded-lg font-label-md transition-all active:scale-95" href="{{ route('portal-siswa.pesan') }}">
            <span class="material-symbols-outlined">chat</span>
            <span>Pesan</span>
            @if($unreadPesan > 0)
            <span class="ml-auto bg-error text-on-error text-[10px] font-bold min-w-[20px] h-5 rounded-full flex items-center justify-center px-1">{{ $unreadPesan > 99 ? '99+' : $unreadPesan }}</span>
            @endif
        </a>
        <a class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('portal-siswa.profil') ? 'bg-secondary-container text-on-secondary-container' : 'text-on-primary/70 hover:bg-primary-container' }} rounded-lg font-label-md transition-all active:scale-95" href="{{ route('portal-siswa.profil') }}">
            <span class="material-symbols-outlined">account_circle</span>
            <span>Profil Saya</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('portal-siswa.spp') ? 'bg-secondary-container text-on-secondary-container' : 'text-on-primary/70 hover:bg-primary-container' }} rounded-lg font-label-md transition-all active:scale-95" href="{{ route('portal-siswa.spp') }}">
            <span class="material-symbols-outlined">payments</span>
            <span>SPP</span>
        </a>
    </nav>
    @if(auth()->user()->siswa && auth()->user()->siswa->kelas && auth()->user()->siswa->kelas->waliKelas)
    <div class="px-4 py-3 mt-2 bg-primary-container/30 rounded-xl">
        <p class="text-body-xs text-on-primary/60 mb-1">Wali Kelas</p>
        <p class="font-label-md text-label-md text-secondary-fixed">{{ auth()->user()->siswa->kelas->waliKelas->user->name }}</p>
        <p class="text-body-xs text-on-primary/60">{{ auth()->user()->siswa->kelas->waliKelas->mata_pelajaran }}</p>
    </div>
    @endif
    <div class="mt-auto pt-6 border-t border-on-primary/10">
        <a href="{{ route('kontak') }}" class="w-full flex items-center justify-center gap-2 bg-secondary text-on-secondary py-3 rounded-xl font-label-md hover:bg-on-secondary-fixed-variant transition-colors">
            <span class="material-symbols-outlined text-[18px]">help</span>
            Help Center
        </a>
        <form method="POST" action="{{ route('logout') }}" class="mt-2">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-4 py-4 text-error cursor-pointer hover:bg-error/10 rounded-lg transition-colors">
                <span class="material-symbols-outlined">logout</span>
                <span class="font-label-md">Logout</span>
            </button>
        </form>
    </div>
</aside>
