<nav x-data="{ mobileOpen: false }" class="w-full top-0 sticky z-50 bg-surface border-b border-outline-variant shadow-sm transition-all duration-300">
    <div class="flex justify-between items-center h-16 px-margin-mobile md:px-margin-desktop max-w-max-width mx-auto">
        <div class="flex items-center gap-4">
            @if (setting('logo_sekolah'))
                <img src="{{ setting('logo_sekolah') }}" alt="Logo {{ setting('nama_sekolah') }}" class="h-10 w-auto object-contain">
            @endif
            <span class="font-headline-md text-headline-md font-bold text-primary">{{ setting('nama_sekolah', 'SMA Nusantara') }}</span>
        </div>
        <div class="hidden md:flex items-center gap-8">
            <a class="font-body-md text-body-md {{ request()->routeIs('home') ? 'text-secondary font-bold border-b-2 border-secondary' : 'text-on-surface-variant hover:text-secondary' }} transition-colors duration-200" href="{{ route('home') }}">Beranda</a>
            <a class="font-body-md text-body-md {{ request()->routeIs('profil-sekolah') ? 'text-secondary font-bold border-b-2 border-secondary' : 'text-on-surface-variant hover:text-secondary' }} transition-colors duration-200" href="{{ route('profil-sekolah') }}">Profil</a>
            <a class="font-body-md text-body-md {{ request()->routeIs('akademik') ? 'text-secondary font-bold border-b-2 border-secondary' : 'text-on-surface-variant hover:text-secondary' }} transition-colors duration-200" href="{{ route('akademik') }}">Akademik</a>
            <a class="font-body-md text-body-md {{ request()->routeIs('berita') ? 'text-secondary font-bold border-b-2 border-secondary' : 'text-on-surface-variant hover:text-secondary' }} transition-colors duration-200" href="{{ route('berita') }}">Berita</a>
            <a class="font-body-md text-body-md {{ request()->routeIs('kontak') ? 'text-secondary font-bold border-b-2 border-secondary' : 'text-on-surface-variant hover:text-secondary' }} transition-colors duration-200" href="{{ route('kontak') }}">Kontak</a>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('login') }}" class="hidden md:inline-flex">
                <button class="bg-secondary-container text-on-secondary-container px-6 py-2 rounded-lg font-label-md text-label-md font-bold hover:opacity-90 active:opacity-80 transition-all">
                    Login
                </button>
            </a>
            <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2 rounded-lg hover:bg-surface-container transition-colors">
                <span x-show="!mobileOpen" class="material-symbols-outlined text-on-surface">menu</span>
                <span x-show="mobileOpen" class="material-symbols-outlined text-on-surface">close</span>
            </button>
        </div>
    </div>
    <div x-show="mobileOpen" x-cloak @click.outside="mobileOpen = false" class="md:hidden bg-surface border-t border-outline-variant px-margin-mobile pb-6 pt-2 space-y-1">
        <a class="block px-4 py-3 rounded-lg font-body-md {{ request()->routeIs('home') ? 'bg-secondary-container text-on-secondary-container font-bold' : 'text-on-surface-variant hover:bg-surface-container' }}" href="{{ route('home') }}">Beranda</a>
        <a class="block px-4 py-3 rounded-lg font-body-md {{ request()->routeIs('profil-sekolah') ? 'bg-secondary-container text-on-secondary-container font-bold' : 'text-on-surface-variant hover:bg-surface-container' }}" href="{{ route('profil-sekolah') }}">Profil</a>
        <a class="block px-4 py-3 rounded-lg font-body-md {{ request()->routeIs('akademik') ? 'bg-secondary-container text-on-secondary-container font-bold' : 'text-on-surface-variant hover:bg-surface-container' }}" href="{{ route('akademik') }}">Akademik</a>
        <a class="block px-4 py-3 rounded-lg font-body-md {{ request()->routeIs('berita') ? 'bg-secondary-container text-on-secondary-container font-bold' : 'text-on-surface-variant hover:bg-surface-container' }}" href="{{ route('berita') }}">Berita</a>
        <a class="block px-4 py-3 rounded-lg font-body-md {{ request()->routeIs('kontak') ? 'bg-secondary-container text-on-secondary-container font-bold' : 'text-on-surface-variant hover:bg-surface-container' }}" href="{{ route('kontak') }}">Kontak</a>
        <a href="{{ route('login') }}" class="block mt-3">
            <button class="w-full bg-secondary-container text-on-secondary-container px-6 py-3 rounded-lg font-label-md font-bold">Login</button>
        </a>
    </div>
</nav>
