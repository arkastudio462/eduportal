<x-layouts.guest :title="$berita->judul . ' | SMA Nusantara'">
<x-guest-navbar />

<main class="max-w-max-width mx-auto px-margin-mobile md:px-margin-desktop py-8 md:py-12">

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-gutter">

        {{-- Left Column: Detail --}}
        <div data-gsap="fade-up" class="lg:col-span-8">

            <a href="{{ route('berita') }}" class="inline-flex items-center gap-2 text-primary font-label-md mb-6 hover:underline">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                Kembali ke Berita
            </a>

            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden shadow-sm">
                <div class="h-64 md:h-80 bg-gradient-to-br from-primary-fixed to-primary-fixed-dim flex items-center justify-center">
                    @if($berita->gambar)
                    <img src="{{ $berita->gambar }}" alt="{{ $berita->judul }}" class="w-full h-full object-cover">
                    @else
                    <span class="material-symbols-outlined text-6xl text-primary/40" style="font-variation-settings: 'FILL' 1;">newspaper</span>
                    @endif
                </div>
                <div class="p-6 md:p-10">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="bg-secondary-container text-on-secondary-container px-3 py-1 rounded-full font-label-md text-label-md">#{{ $berita->kategori }}</span>
                        <span class="text-outline font-body-sm text-body-sm">{{ $berita->tanggal->isoFormat('dddd, D MMMM YYYY') }}</span>
                    </div>

                    <h1 class="font-headline-lg text-headline-lg md:text-display-sm text-primary mb-6">{{ $berita->judul }}</h1>

                    <div class="text-on-surface font-body-lg text-body-lg leading-relaxed space-y-4">
                        {!! nl2br(e($berita->konten)) !!}
                    </div>
                </div>
            </div>

        </div>

        {{-- Right Column: Sidebar --}}
        <aside class="lg:col-span-4 space-y-8">

            {{-- Berita Lainnya --}}
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 shadow-sm">
                <h3 class="font-headline-sm text-headline-sm mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-secondary">article</span>
                    Berita Lainnya
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
                    <p class="text-on-surface-variant text-sm">Belum ada berita lain.</p>
                    @endforelse
                </div>
            </div>

            {{-- Kategori --}}
            <div class="bg-primary text-on-primary rounded-xl p-6 shadow-sm">
                <h3 class="font-headline-sm text-headline-sm mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-secondary-fixed">category</span>
                    Kategori
                </h3>
                <div class="space-y-3">
                    <a href="{{ route('berita') }}" class="flex items-center justify-between py-2 border-b border-on-primary/10 hover:text-secondary-fixed transition-colors">
                        <span>Semua</span>
                        <span class="material-symbols-outlined">chevron_right</span>
                    </a>
                    @foreach($kategoriList as $kat)
                    <a href="{{ route('berita', ['kategori' => $kat]) }}" class="flex items-center justify-between py-2 border-b border-on-primary/10 hover:text-secondary-fixed transition-colors">
                        <span>#{{ $kat }}</span>
                        <span class="material-symbols-outlined">chevron_right</span>
                    </a>
                    @endforeach
                </div>
            </div>

        </aside>

    </div>
</main>

<x-guest-footer />
</x-layouts.guest>
