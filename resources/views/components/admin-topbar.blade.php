<header class="h-16 flex items-center justify-between px-margin-desktop bg-surface sticky top-0 z-40 border-b border-outline-variant">
    <div class="flex items-center gap-3 md:ml-8">
        <h1 class="font-headline-sm text-headline-sm text-on-surface font-semibold">{{ $title ?? 'Dashboard Sekolah' }}</h1>
    </div>
    <div class="flex items-center gap-6">
        <div class="hidden md:flex relative">
            <form method="GET" action="{{ route('admin.dashboard') }}">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">search</span>
                <input name="q" class="pl-10 pr-4 py-2 bg-surface-container-low border-none rounded-full w-64 focus:ring-2 focus:ring-primary-fixed text-body-md" placeholder="Cari data..." type="text">
            </form>
        </div>
        <div class="flex items-center gap-4">
            <button @click="darkMode = !darkMode" class="hover:bg-surface-container-high rounded-full p-2 transition-colors" title="Toggle Dark Mode">
                <span x-show="!darkMode" class="material-symbols-outlined text-primary">dark_mode</span>
                <span x-show="darkMode" class="material-symbols-outlined text-primary">light_mode</span>
            </button>
            <div x-data="{ openNotif: false }" class="relative">
                <button @click="openNotif = !openNotif" @click.outside="openNotif = false" class="hover:bg-surface-container-high rounded-full p-2 transition-colors relative">
                    <span class="material-symbols-outlined text-primary">notifications</span>
                    @php $unreadCount = Auth::user()->unreadNotifications->count(); @endphp
                    @if($unreadCount > 0)
                    <span class="absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] bg-error text-on-error text-[10px] font-bold rounded-full flex items-center justify-center px-1">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
                    @endif
                </button>
                <div x-show="openNotif" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute right-0 mt-2 w-80 bg-surface rounded-xl border border-outline-variant card-shadow py-2 z-50 max-h-96 overflow-y-auto">
                    @forelse(Auth::user()->unreadNotifications->take(5) as $n)
                    <a href="{{ route('admin.notifications.read', $n->id) }}" class="flex items-start gap-3 px-4 py-3 hover:bg-surface-container-high transition-colors border-b border-outline-variant/50">
                        <span class="material-symbols-outlined text-primary text-[20px] mt-0.5">{{ ($n->data['type'] ?? '') == 'tugas' ? 'assignment' : 'campaign' }}</span>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold truncate">{{ $n->data['judul'] ?? 'Notifikasi' }}</p>
                            <p class="text-xs text-on-surface-variant">{{ $n->created_at->diffForHumans() }}</p>
                        </div>
                    </a>
                    @empty
                    <div class="px-4 py-6 text-center text-sm text-on-surface-variant">Tidak ada notifikasi baru</div>
                    @endforelse
                    @if($unreadCount > 5)
                    <a href="{{ route('admin.notifications') }}" class="block px-4 py-2 text-center text-sm text-primary font-bold hover:bg-surface-container-high">Lihat Semua ({{ $unreadCount }})</a>
                    @endif
                    @if($unreadCount > 0)
                    <form method="POST" action="{{ route('admin.notifications.read-all') }}" class="px-4 py-2">
                        @csrf
                        <button type="submit" class="w-full text-center text-xs text-on-surface-variant hover:text-primary">Tandai Semua Dibaca</button>
                    </form>
                    @endif
                </div>
            </div>
            <a href="{{ route('kontak') }}" class="hover:bg-surface-container-high rounded-full p-2 transition-colors">
                <span class="material-symbols-outlined text-primary">help_outline</span>
            </a>
            <x-pwa-install-button />
            <div class="h-8 w-[1px] bg-outline-variant"></div>
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" @click.outside="open = false" class="flex items-center gap-2 cursor-pointer hover:bg-surface-container-high rounded-lg p-2 transition-colors">
                    @php $user = auth()->user(); @endphp
                    @if($user->profile_photo_path)
                    <img src="{{ Storage::url($user->profile_photo_path) }}" alt="{{ $user->name }}" class="w-8 h-8 rounded-full object-cover">
                    @endif
                    <div class="text-right hidden sm:block">
                        <p class="font-label-md text-label-md">{{ $user->name }}</p>
                        <p class="text-[10px] text-outline uppercase tracking-wider">Admin</p>
                    </div>
                    <span class="material-symbols-outlined text-outline">arrow_drop_down</span>
                </button>
                <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute right-0 mt-2 w-48 bg-surface rounded-xl border border-outline-variant card-shadow py-2 z-50">
                    <a href="{{ route('admin.pengaturan') }}" class="flex items-center gap-3 px-4 py-2 text-body-md text-on-surface hover:bg-surface-container-high transition-colors">
                        <span class="material-symbols-outlined text-outline text-[20px]">admin_panel_settings</span>
                        Pengaturan Admin
                    </a>
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
</header>
