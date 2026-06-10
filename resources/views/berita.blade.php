<x-layouts.guest title="Berita | SMA Nusantara">
<x-guest-navbar />

<main class="max-w-max-width mx-auto px-margin-mobile md:px-margin-desktop py-8 md:py-12">

    {{-- Hero: Berita Terkini --}}
    <section data-gsap-hero class="mb-12">
        <h2 data-gsap-hero-item class="font-headline-lg text-headline-lg mb-6 flex items-center gap-2">
            <span class="material-symbols-outlined text-secondary" style="font-variation-settings: 'FILL' 1;">campaign</span>
            {{ setting('berita_hero_title', 'Berita Terkini') }}
        </h2>
        @if($beritaUtama)
        <div data-gsap-hero-item class="relative group overflow-hidden rounded-xl h-[450px] shadow-lg border border-outline-variant bg-gradient-to-br from-primary to-primary-fixed">
            <div class="absolute inset-0 bg-gradient-to-t from-primary/90 via-primary/40 to-transparent"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-primary/95 to-primary/40"></div>
            <div class="relative z-10 h-full flex flex-col justify-end p-8 md:p-12">
                <span class="bg-secondary-container text-on-secondary-container px-3 py-1 rounded-full font-label-md text-label-md mb-4 w-fit">#{{ $beritaUtama->kategori }}</span>
                <h1 class="text-on-primary font-headline-xl text-headline-xl mb-4 max-w-2xl leading-tight">{{ $beritaUtama->judul }}</h1>
                <p class="text-on-primary/80 font-body-lg text-body-lg mb-6 max-w-3xl line-clamp-2">{{ Str::limit(strip_tags($beritaUtama->konten), 200) }}</p>
                <div class="flex items-center gap-4">
                    <a href="{{ route('berita.show', $beritaUtama->slug) }}" class="bg-secondary text-on-secondary px-8 py-3 rounded-xl font-label-md text-label-md flex items-center gap-2 hover:opacity-90 transition-all">
                        Baca Selengkapnya
                        <span class="material-symbols-outlined">arrow_forward</span>
                    </a>
                    <span class="text-on-primary/60 font-body-sm text-body-sm">{{ $beritaUtama->tanggal->isoFormat('D MMMM YYYY') }}</span>
                </div>
            </div>
        </div>
        @endif
    </section>

    {{-- Main Content Area --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-gutter">

        {{-- Left Column --}}
        <div class="lg:col-span-8">

            {{-- Search & Filter --}}
            <div data-gsap="fade-up" class="flex flex-col md:flex-row gap-4 mb-8">
                <form method="GET" class="flex flex-col md:flex-row gap-4 w-full">
                    <div class="relative flex-grow">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 material-symbols-outlined text-outline">search</span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berita..." class="w-full pl-12 pr-4 py-3 bg-surface-container-lowest border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary outline-none text-body-md">
                    </div>
                    <div class="flex gap-2 overflow-x-auto pb-2 md:pb-0 scrollbar-hide">
                        <a href="{{ route('berita') }}" class="whitespace-nowrap px-6 py-3 {{ !request('kategori') || request('kategori') === 'semua' ? 'bg-primary text-on-primary' : 'bg-surface-container-high text-on-surface-variant hover:bg-outline-variant' }} rounded-xl font-label-md text-label-md transition-colors">Semua</a>
                        @foreach($kategoriList as $kat)
                        <a href="{{ route('berita', ['kategori' => $kat]) }}" class="whitespace-nowrap px-6 py-3 {{ request('kategori') === $kat ? 'bg-primary text-on-primary' : 'bg-surface-container-high text-on-surface-variant hover:bg-outline-variant' }} rounded-xl font-label-md text-label-md transition-colors">#{{ $kat }}</a>
                        @endforeach
                    </div>
                </form>
            </div>

            {{-- News Grid --}}
            <div data-gsap="stagger" class="grid grid-cols-1 md:grid-cols-2 gap-gutter mb-12">
                @forelse($berita as $item)
                <a href="{{ route('berita.show', $item->slug) }}" class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden shadow-sm group hover:shadow-md transition-shadow block">
                    <div class="h-48 overflow-hidden bg-gradient-to-br from-primary-fixed to-primary-fixed-dim flex items-center justify-center">
                        @if($item->gambar)
                        <img src="{{ $item->gambar }}" alt="{{ $item->judul }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        @else
                        <span class="material-symbols-outlined text-5xl text-primary/40" style="font-variation-settings: 'FILL' 1;">newspaper</span>
                        @endif
                    </div>
                    <div class="p-6">
                        <span class="text-secondary font-label-md text-label-md mb-2 block">#{{ $item->kategori }}</span>
                        <h3 class="font-headline-sm text-headline-sm mb-3 group-hover:text-secondary transition-colors line-clamp-2">{{ $item->judul }}</h3>
                        <p class="text-on-surface-variant font-body-md text-body-md mb-4 line-clamp-2">{{ Str::limit(strip_tags($item->konten), 150) }}</p>
                        <div class="flex justify-between items-center text-outline font-body-sm text-body-sm">
                            <span>{{ $item->tanggal->isoFormat('D MMM YYYY') }}</span>
                            <span class="text-primary font-label-md text-label-md flex items-center gap-1 group-hover:underline">
                                Baca <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                            </span>
                        </div>
                    </div>
                </a>
                @empty
                <div class="md:col-span-2 text-center py-12 text-on-surface-variant">
                    <span class="material-symbols-outlined text-5xl text-outline mb-4">newspaper</span>
                    <p>Belum ada berita.</p>
                </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if($berita->hasPages())
            <div class="flex justify-center items-center gap-2 mb-12">
                @if($berita->onFirstPage())
                <span class="w-10 h-10 flex items-center justify-center rounded-lg border border-outline-variant text-outline opacity-50">
                    <span class="material-symbols-outlined">chevron_left</span>
                </span>
                @else
                <a href="{{ $berita->previousPageUrl() }}" class="w-10 h-10 flex items-center justify-center rounded-lg border border-outline-variant hover:bg-surface-container-high transition-colors text-outline">
                    <span class="material-symbols-outlined">chevron_left</span>
                </a>
                @endif

                @foreach($berita->getUrlRange(1, $berita->lastPage()) as $page => $url)
                <a href="{{ $url }}" class="w-10 h-10 flex items-center justify-center rounded-lg {{ $page == $berita->currentPage() ? 'bg-primary text-on-primary font-bold' : 'border border-outline-variant hover:bg-surface-container-high transition-colors' }}">{{ $page }}</a>
                @endforeach

                @if($berita->hasMorePages())
                <a href="{{ $berita->nextPageUrl() }}" class="w-10 h-10 flex items-center justify-center rounded-lg border border-outline-variant hover:bg-surface-container-high transition-colors text-outline">
                    <span class="material-symbols-outlined">chevron_right</span>
                </a>
                @else
                <span class="w-10 h-10 flex items-center justify-center rounded-lg border border-outline-variant text-outline opacity-50">
                    <span class="material-symbols-outlined">chevron_right</span>
                </span>
                @endif
            </div>
            @endif

        </div>

        {{-- Right Column: Sidebar --}}
        <aside class="lg:col-span-4 space-y-8">

            {{-- Berita Populer --}}
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 shadow-sm">
                <h3 class="font-headline-sm text-headline-sm mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-secondary">trending_up</span>
                    Berita Populer
                </h3>
                <div class="space-y-6">
                    @forelse($beritaPopuler as $i => $pop)
                    <a href="{{ route('berita.show', $pop->slug) }}" class="group flex gap-4 items-start">
                        <span class="text-headline-md font-bold text-outline-variant group-hover:text-secondary transition-colors">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <div>
                            <h4 class="font-body-md text-body-md font-bold group-hover:text-secondary transition-colors mb-1 line-clamp-2">{{ $pop->judul }}</h4>
                            <span class="text-on-surface-variant font-body-sm text-body-sm">{{ $pop->tanggal->isoFormat('D MMM YYYY') }}</span>
                        </div>
                    </a>
                    @empty
                    <p class="text-on-surface-variant text-sm">Belum ada berita.</p>
                    @endforelse
                </div>
            </div>

            {{-- Arsip Berita + Newsletter --}}
            <div class="bg-primary text-on-primary rounded-xl p-6 shadow-sm">
                <h3 class="font-headline-sm text-headline-sm mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-secondary-fixed">calendar_month</span>
                    Arsip Berita
                </h3>
                <div class="space-y-4">
                    @php
                        $arsip = \App\Models\Berita::selectRaw("strftime('%Y', tanggal) as tahun")->distinct()->orderBy('tahun', 'desc')->take(5)->get();
                    @endphp
                    @foreach($arsip as $a)
                    <a href="{{ route('berita', ['search' => $a->tahun]) }}" class="w-full flex justify-between items-center py-2 border-b border-on-primary/10 hover:text-secondary-fixed transition-colors">
                        <span>Tahun {{ $a->tahun }}</span>
                        <span class="material-symbols-outlined">chevron_right</span>
                    </a>
                    @endforeach
                </div>
                <div class="mt-8">
                    <p class="font-body-sm text-body-sm text-on-primary/60 mb-4">Berlangganan newsletter untuk mendapatkan berita terbaru langsung di email Anda.</p>
                    <div class="flex gap-2">
                        <input class="w-full px-4 py-2 bg-on-primary/10 border border-on-primary/20 rounded-lg text-body-sm focus:outline-none focus:border-secondary-fixed" placeholder="Email Anda" type="email">
                        <button class="bg-secondary-container text-on-secondary-container px-4 py-2 rounded-lg font-label-md text-label-md whitespace-nowrap">Daftar</button>
                    </div>
                </div>
            </div>

        </aside>

    </div>
</main>

<x-guest-footer />
</x-layouts.guest>
