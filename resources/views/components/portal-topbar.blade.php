<header class="w-full top-0 sticky z-50 bg-surface border-b border-outline-variant shadow-sm h-16">
    <div class="flex justify-between items-center px-margin-desktop max-md:px-margin-mobile h-full w-full">
        <div class="flex items-center gap-4">
            @if($withLogo ?? false)
                <img alt="SMA Nusantara Logo" class="h-10 w-10 object-contain rounded-full bg-surface-container" src="https://lh3.googleusercontent.com/aida-public/AB6AXuA5zt9MfePiw_t45KYmwXtje0d5hSx5-YJ3qG_RQhsURRNEiUM6c65rJeQJ1K9MsRler9kiJ7faU5EnjlN6MX5VeSUni6-TK6__e_YWFGIZccEUyLOn69MNMq5uT2W2gvZ9LXsaMJqKmkvM3CrAPRCHwKsiDknVn93bleqVy4KtCRAaAKSHqCwAYPUmLr11lh8so7c9UnPVJ0hEuQ0ZC7BPIBGGBwijksV-u_dayLnNu_UCxVLP7LpUxsowsGTgg9TKPZyG3QrGqOlk">
            @endif
            <span class="font-headline-sm text-headline-sm font-bold text-primary">{{ $title ?? 'EduPortal' }}</span>
        </div>
        <div class="flex items-center gap-6">
            <div class="flex items-center gap-4">
                <button @click="darkMode = !darkMode" class="hover:bg-surface-container-high rounded-full p-2 transition-colors" title="Toggle Dark Mode">
                    <span x-show="!darkMode" class="material-symbols-outlined text-on-surface-variant">dark_mode</span>
                    <span x-show="darkMode" class="material-symbols-outlined text-on-surface-variant">light_mode</span>
                </button>
                <a href="{{ route('kontak') }}" class="material-symbols-outlined text-on-surface-variant hover:bg-surface-container-high p-2 rounded-full transition-colors">contact_support</a>
                <x-pwa-install-button />
                <div class="h-8 w-[1px] bg-outline-variant mx-2"></div>
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" @click.outside="open = false" class="flex items-center gap-3 cursor-pointer group hover:bg-surface-container-high rounded-lg p-1 pr-2 transition-colors">
                        @php $user = auth()->user(); @endphp
                        @if($user->profile_photo_path)
                        <img src="{{ Storage::url($user->profile_photo_path) }}" alt="{{ $user->name }}" class="w-8 h-8 rounded-full object-cover">
                        @endif
                        <div class="text-right hidden sm:block">
                            <p class="font-label-md text-label-md text-on-surface leading-tight">{{ $userName ?? $user->name }}</p>
                            <p class="text-body-sm text-on-surface-variant leading-tight">{{ $userRole ?? 'User' }}</p>
                        </div>
                        <span class="material-symbols-outlined text-outline">arrow_drop_down</span>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute right-0 mt-2 w-48 bg-surface rounded-xl border border-outline-variant card-shadow py-2 z-50">
                        @if(request()->routeIs('portal-siswa*'))
                            <a href="{{ route('portal-siswa.profil') }}" class="flex items-center gap-3 px-4 py-2 text-body-md text-on-surface hover:bg-surface-container-high transition-colors">
                                <span class="material-symbols-outlined text-outline text-[20px]">account_circle</span>
                                Profil Saya
                            </a>
                        @elseif(request()->routeIs('portal-guru*'))
                            <a href="{{ route('portal-guru.profil') }}" class="flex items-center gap-3 px-4 py-2 text-body-md text-on-surface hover:bg-surface-container-high transition-colors">
                                <span class="material-symbols-outlined text-outline text-[20px]">account_circle</span>
                                Profil Saya
                            </a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 text-body-md text-error hover:bg-surface-container-high transition-colors">
                                <span class="material-symbols-outlined text-[20px]">logout</span>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
