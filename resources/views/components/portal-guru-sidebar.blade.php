<aside class="fixed top-0 left-0 h-screen w-64 bg-primary text-on-primary flex flex-col py-base px-4 gap-2 z-40 hidden md:flex">
    <div class="flex flex-col gap-1 mb-6 px-2">
        <span class="font-headline-sm text-headline-sm text-secondary-fixed">Portal Akademik</span>
        <span class="font-label-md text-label-md opacity-70">Sistem Informasi Sekolah</span>
    </div>
    <nav class="flex flex-col gap-1 flex-1">
        <a class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('portal-guru.dashboard') ? 'bg-secondary-container text-on-secondary-container scale-95' : 'text-on-primary/70 hover:bg-primary-container' }} transition-all rounded-xl duration-150" href="{{ route('portal-guru.dashboard') }}">
            <span class="material-symbols-outlined">dashboard</span>
            <span class="font-label-md text-label-md">Dashboard</span>
        </a>
        <a class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('portal-guru.jadwal') ? 'bg-secondary-container text-on-secondary-container scale-95' : 'text-on-primary/70 hover:bg-primary-container' }} transition-all rounded-xl duration-150" href="{{ route('portal-guru.jadwal') }}">
            <span class="material-symbols-outlined">calendar_month</span>
            <span class="font-label-md text-label-md">Jadwal Pelajaran</span>
        </a>
        <a class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('portal-guru.nilai') ? 'bg-secondary-container text-on-secondary-container scale-95' : 'text-on-primary/70 hover:bg-primary-container' }} transition-all rounded-xl duration-150" href="{{ route('portal-guru.nilai') }}">
            <span class="material-symbols-outlined">grade</span>
            <span class="font-label-md text-label-md">Nilai</span>
        </a>
        <a class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('portal-guru.absensi') ? 'bg-secondary-container text-on-secondary-container scale-95' : 'text-on-primary/70 hover:bg-primary-container' }} transition-all rounded-xl duration-150" href="{{ route('portal-guru.absensi') }}">
            <span class="material-symbols-outlined">person_check</span>
            <span class="font-label-md text-label-md">Absensi</span>
        </a>
        <a class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('portal-guru.tugas') ? 'bg-secondary-container text-on-secondary-container scale-95' : 'text-on-primary/70 hover:bg-primary-container' }} transition-all rounded-xl duration-150" href="{{ route('portal-guru.tugas') }}">
            <span class="material-symbols-outlined">assignment</span>
            <span class="font-label-md text-label-md">Tugas</span>
        </a>
        <a class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('portal-guru.ujian-online') ? 'bg-secondary-container text-on-secondary-container scale-95' : 'text-on-primary/70 hover:bg-primary-container' }} transition-all rounded-xl duration-150" href="{{ route('portal-guru.ujian-online') }}">
            <span class="material-symbols-outlined">quiz</span>
            <span class="font-label-md text-label-md">Ujian Online</span>
        </a>
        <a class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('portal-guru.bank-soal') ? 'bg-secondary-container text-on-secondary-container scale-95' : 'text-on-primary/70 hover:bg-primary-container' }} transition-all rounded-xl duration-150" href="{{ route('portal-guru.bank-soal') }}">
            <span class="material-symbols-outlined">library_books</span>
            <span class="font-label-md text-label-md">Bank Soal</span>
        </a>
        <a class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('portal-guru.rapor') ? 'bg-secondary-container text-on-secondary-container scale-95' : 'text-on-primary/70 hover:bg-primary-container' }} transition-all rounded-xl duration-150" href="{{ route('portal-guru.rapor') }}">
            <span class="material-symbols-outlined">description</span>
            <span class="font-label-md text-label-md">Cetak Rapor</span>
        </a>
        <a class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('portal-guru.wali-kelas') ? 'bg-secondary-container text-on-secondary-container scale-95' : 'text-on-primary/70 hover:bg-primary-container' }} transition-all rounded-xl duration-150" href="{{ route('portal-guru.wali-kelas') }}">
            <span class="material-symbols-outlined">supervisor_account</span>
            <span class="font-label-md text-label-md">Wali Kelas</span>
        </a>
        <a class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('portal-guru.modul') ? 'bg-secondary-container text-on-secondary-container scale-95' : 'text-on-primary/70 hover:bg-primary-container' }} transition-all rounded-xl duration-150" href="{{ route('portal-guru.modul') }}">
            <span class="material-symbols-outlined">folder</span>
            <span class="font-label-md text-label-md">Modul Ajar</span>
        </a>
        <a class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('portal-guru.perpustakaan') ? 'bg-secondary-container text-on-secondary-container scale-95' : 'text-on-primary/70 hover:bg-primary-container' }} transition-all rounded-xl duration-150" href="{{ route('portal-guru.perpustakaan') }}">
            <span class="material-symbols-outlined">library_books</span>
            <span class="font-label-md text-label-md">Perpustakaan</span>
        </a>
        @php $unreadPesan = \App\Models\Message::whereHas('conversation.participants', fn($q) => $q->where('user_id', Auth::id()))->where('sender_id', '!=', Auth::id())->whereNull('read_at')->count(); @endphp
        <a class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('portal-guru.pesan*') ? 'bg-secondary-container text-on-secondary-container scale-95' : 'text-on-primary/70 hover:bg-primary-container' }} transition-all rounded-xl duration-150" href="{{ route('portal-guru.pesan') }}">
            <span class="material-symbols-outlined">chat</span>
            <span class="font-label-md text-label-md">Pesan</span>
            @if($unreadPesan > 0)
            <span class="ml-auto bg-error text-on-error text-[10px] font-bold min-w-[20px] h-5 rounded-full flex items-center justify-center px-1">{{ $unreadPesan > 99 ? '99+' : $unreadPesan }}</span>
            @endif
        </a>
        <a class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('portal-guru.presensi-guru') ? 'bg-secondary-container text-on-secondary-container scale-95' : 'text-on-primary/70 hover:bg-primary-container' }} transition-all rounded-xl duration-150" href="{{ route('portal-guru.presensi-guru') }}">
            <span class="material-symbols-outlined">qr_code_scanner</span>
            <span class="font-label-md text-label-md">Presensi</span>
        </a>
        <a class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('portal-guru.profil') ? 'bg-secondary-container text-on-secondary-container scale-95' : 'text-on-primary/70 hover:bg-primary-container' }} transition-all rounded-xl duration-150" href="{{ route('portal-guru.profil') }}">
            <span class="material-symbols-outlined">account_circle</span>
            <span class="font-label-md text-label-md">Profil Saya</span>
        </a>
    </nav>
    <div class="mt-auto border-t border-on-primary/10 pt-4">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 text-error hover:bg-error/10 transition-colors rounded-xl">
                <span class="material-symbols-outlined">logout</span>
                <span class="font-label-md text-label-md">Logout</span>
            </button>
        </form>
    </div>
</aside>
