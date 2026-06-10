<x-layouts.guest title="SMA Nusantara | Unggul & Berkarakter">
    <x-slot:styles>
        <style>
            .ticker-scroll { animation: ticker 30s linear infinite; }
            @keyframes ticker {
                0% { transform: translateX(100%); }
                100% { transform: translateX(-100%); }
            }
            .glass-card { background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(8px); border: 1px solid rgba(255, 255, 255, 0.3); }
        </style>
    </x-slot:styles>
<x-guest-navbar />
<!-- Ticker Section -->
<div class="bg-primary text-on-primary py-2 overflow-hidden relative border-b border-primary-container">
    <div class="ticker-scroll whitespace-nowrap flex gap-12 font-label-md italic uppercase tracking-wider">
        @forelse($pengumuman as $item)
        <span>{{ $item->judul }}</span>
        @empty
        <span>Selamat Datang di SMA Nusantara</span>
        @endforelse
    </div>
</div>
<!-- Hero Section -->
<header data-gsap-hero class="relative min-h-screen flex items-center overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img alt="Hero School" class="w-full h-full object-cover"
             fetchpriority="high" src="{{ setting('home_hero_image', 'https://lh3.googleusercontent.com/aida-public/AB6AXuC44WVMZMZa5QyEZUmx3plIEgBHpVBIT8C2C8fJSUumQs_wydQOFWmYpHgn6UvSkdAp5hw1lMyuQRHICVrXqLQi1LlSPJtWIifFmtB0f8Hc2lApRzYApxCg1mI7n5Dtk9S-YYeNmKWIkrY4oWDRpWvl4ROjsHhHU7gR1doJu8GzfGXhXJyxYRR5vo5uWHfAWCpcgFj2uvMp_awCuiqlvZfURBdrAKwYkIvdy1kYL7JuhpEjJUfjVTVCnWUrRqTcd66YOiUf_O24XKR5') }}">
        <div class="absolute inset-0 bg-gradient-to-r from-primary/90 via-primary/50 to-transparent"></div>
    </div>
    <div class="relative z-10 px-margin-desktop max-w-max-width mx-auto w-full text-on-primary">
        <div class="max-w-2xl">
            <h1 data-gsap-hero-item class="font-headline-xl text-headline-xl mb-4 leading-tight">{{ setting('home_hero_title', 'Selamat Datang di SMA Nusantara') }}</h1>
            <p data-gsap-hero-item class="font-body-lg text-body-lg mb-8 opacity-90">"{{ setting('home_hero_subtitle', 'Membentuk Generasi Unggul yang Cerdas, Berkarakter, dan Berdaya Saing Global') }}"</p>
            <div data-gsap-hero-item class="flex gap-4">
                <a href="{{ route('ppdb.form') }}" class="bg-secondary-container text-on-secondary-container px-8 py-3 rounded-lg font-label-md text-label-md font-bold hover:scale-105 transition-transform shadow-lg inline-block">
                    Daftar Sekarang
                </a>
                <a href="#program" class="border-2 border-on-primary text-on-primary px-8 py-3 rounded-lg font-label-md text-label-md font-bold hover:bg-on-primary hover:text-primary transition-all inline-block">
                    Eksplor Program
                </a>
            </div>
        </div>
    </div>
</header>
<!-- Stats Strip -->
<section data-gsap="fade-up" class="bg-surface-container -mt-12 relative z-20 mx-margin-desktop max-w-max-width lg:mx-auto rounded-xl shadow-xl border border-outline-variant">
    <div class="grid grid-cols-2 md:grid-cols-4 divide-x divide-outline-variant py-8">
        <div class="text-center px-4">
            <div data-gsap="count" data-target="{{ $totalSiswa }}" class="font-headline-lg text-headline-lg text-primary">{{ number_format($totalSiswa) }}</div>
            <div class="font-body-sm text-body-sm text-on-surface-variant uppercase tracking-widest font-bold">{{ setting('home_stats_siswa_label', 'Total Siswa') }}</div>
        </div>
        <div class="text-center px-4">
            <div data-gsap="count" data-target="{{ $totalGuru }}" class="font-headline-lg text-headline-lg text-primary">{{ number_format($totalGuru) }}</div>
            <div class="font-body-sm text-body-sm text-on-surface-variant uppercase tracking-widest font-bold">{{ setting('home_stats_guru_label', 'Guru Ahli') }}</div>
        </div>
        <div class="text-center px-4">
            <div data-gsap="count" data-target="24" class="font-headline-lg text-headline-lg text-primary">24</div>
            <div class="font-body-sm text-body-sm text-on-surface-variant uppercase tracking-widest font-bold">{{ setting('home_stats_ekskul_label', 'Ekstrakurikuler') }}</div>
        </div>
        <div class="text-center px-4">
            <div data-gsap="count" data-target="{{ $totalPrestasi }}" class="font-headline-lg text-headline-lg text-primary">{{ number_format($totalPrestasi) }}</div>
            <div class="font-body-sm text-body-sm text-on-surface-variant uppercase tracking-widest font-bold">{{ setting('home_stats_prestasi_label', 'Prestasi') }}</div>
        </div>
    </div>
</section>
<!-- Main Content Area -->
<main class="max-w-max-width mx-auto px-margin-desktop py-16 grid grid-cols-1 lg:grid-cols-12 gap-gutter">
    <section data-gsap="fade-up" class="lg:col-span-8">
        <div data-gsap="fade-up" class="flex items-center justify-between mb-8">
            <h2 class="font-headline-lg text-headline-lg text-primary flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary">newspaper</span>
                Berita Terkini
            </h2>
            <a class="text-primary font-label-md text-label-md hover:underline" href="{{ route('berita') }}">Lihat Semua Berita →</a>
        </div>
        <div data-gsap="stagger" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @if($beritaUtama)
            <a href="{{ route('berita.show', $beritaUtama->slug) }}" class="md:col-span-2 group relative h-[300px] rounded-xl overflow-hidden shadow-md border border-outline-variant block">
                <img loading="lazy" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDLlHk-E7ibl1pwwN49Y1GRPAj30k8zYBANIWUOsP15Wz-1WDqICI6uu2wmhsdjmdW3xn18TBtimngsghVQnLm4BtJZ2kwzQXZyDV1rJkndF2cFgTnuc41aVhFZAly7zA765wB3Tn7_oDt7XmIe9zpVEtJAJhEDbDA7Vd0XSRhdbC6gZYmu-XNGLNTbXUH5HDNsAiY8tFDXOCWAzH0An7325UGtAd5R7sXn1tOT8wfVs74yKRzwck2X8mcQD_DOqTBM5LEUglTO0a8W" alt="">
                <div class="absolute inset-0 bg-gradient-to-t from-primary/90 to-transparent"></div>
                <div class="absolute bottom-0 p-6 text-on-primary">
                    <span class="bg-secondary-container text-on-secondary-container px-3 py-1 rounded text-body-sm font-bold mb-2 inline-block">#{{ $beritaUtama->kategori }}</span>
                    <h3 class="font-headline-md text-headline-md mb-2">{{ $beritaUtama->judul }}</h3>
                    <p class="font-body-sm opacity-80">{{ \Illuminate\Support\Str::limit(strip_tags($beritaUtama->konten), 120) }}</p>
                </div>
            </a>
            @endif
            @forelse($beritaList as $item)
            <a href="{{ route('berita.show', $item->slug) }}" class="bg-surface-container-lowest rounded-xl overflow-hidden shadow-sm border border-outline-variant hover:shadow-md transition-shadow block group">
                <img loading="lazy" class="w-full h-48 object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuA-sVHYLzEAb6kvyj8U-aaxlphenYJJTupYIIsqYpn_AVOX62Cyu7XpqEZ2Uf5YCZWowoE3ae0mvk8Kk3jQ3ONuSml9Jlg72aDUT2gcB3ir-rErGSfacax5wYBBXetf0jrJY-_wiucRPHYGLMtC45K3yZiMkSpW76g0mC1DPMxZowtQUIMsDlDsnoYdAF-pmWN99NNz_OYq4PmOL2HmB5DbmlOdiUQmy4s4uBIooTuYIYlY-WrORk1KZgZlOzgDKVuzNPc588mEKM5W" alt="">
                <div class="p-6">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="material-symbols-outlined text-body-sm text-secondary">calendar_today</span>
                        <span class="font-body-sm text-body-sm text-on-surface-variant">{{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->format('d F Y') : '' }}</span>
                    </div>
                    <h3 class="font-headline-sm text-headline-sm text-primary mb-4 leading-tight">{{ $item->judul }}</h3>
                    <span class="text-primary font-bold font-body-sm flex items-center gap-1 group-hover:gap-2 transition-all">
                        Baca Selengkapnya <span class="material-symbols-outlined">chevron_right</span>
                    </span>
                </div>
            </a>
            @empty
            <div class="md:col-span-2 text-center py-12 text-on-surface-variant font-body-md">Belum ada berita.</div>
            @endforelse
        </div>
    </section>
    <aside data-gsap="fade-up" class="lg:col-span-4 space-y-gutter">
        <section class="bg-primary-container text-on-primary-container p-8 rounded-xl shadow-lg">
            <h2 class="font-headline-sm text-headline-sm mb-6 flex items-center gap-2 text-on-primary-container">
                <span class="material-symbols-outlined">event</span>
                Kalender Akademik
            </h2>
            <div class="space-y-6">
                <div class="flex gap-4 items-start">
                    <div class="bg-on-primary-container text-primary-container p-2 rounded-lg text-center min-w-[50px]">
                        <div class="font-bold text-headline-sm">20</div>
                        <div class="text-[10px] uppercase font-bold">Des</div>
                    </div>
                    <div>
                        <h4 class="font-bold text-body-md">Pembagian Rapor Ganjil</h4>
                        <p class="text-body-sm opacity-80">Siswa & Orang Tua hadir pukul 08.00</p>
                    </div>
                </div>
                <div class="flex gap-4 items-start opacity-70">
                    <div class="bg-on-primary-container text-primary-container p-2 rounded-lg text-center min-w-[50px]">
                        <div class="font-bold text-headline-sm">23</div>
                        <div class="text-[10px] uppercase font-bold">Des</div>
                    </div>
                    <div>
                        <h4 class="font-bold text-body-md">Cuti Bersama Natal</h4>
                        <p class="text-body-sm">Libur Nasional</p>
                    </div>
                </div>
                <div class="flex gap-4 items-start">
                    <div class="bg-on-primary-container text-primary-container p-2 rounded-lg text-center min-w-[50px]">
                        <div class="font-bold text-headline-sm">06</div>
                        <div class="text-[10px] uppercase font-bold">Jan</div>
                    </div>
                    <div>
                        <h4 class="font-bold text-body-md">Awal Semester Genap</h4>
                        <p class="text-body-sm opacity-80">Hari pertama masuk sekolah</p>
                    </div>
                </div>
            </div>
            <button class="w-full mt-8 border border-on-primary-container/30 py-3 rounded-lg text-body-sm font-bold hover:bg-on-primary-container hover:text-primary-container transition-colors">
                Download Kalender Lengkap
            </button>
        </section>
        <section class="bg-surface-container-high p-8 rounded-xl border border-outline-variant">
            <h3 class="font-headline-sm text-headline-sm mb-4">Butuh Bantuan?</h3>
            <p class="font-body-sm text-on-surface-variant mb-6">Hubungi layanan administrasi sekolah untuk informasi lebih lanjut mengenai pendaftaran dan administrasi.</p>
            <div class="space-y-3">
                <a class="flex items-center gap-3 p-3 bg-surface-container-lowest rounded-lg border border-outline-variant hover:border-secondary transition-colors" href="https://wa.me/62812345678">
                    <span class="material-symbols-outlined text-secondary">chat</span>
                    <span class="font-label-md">Chat via WhatsApp</span>
                </a>
                <a class="flex items-center gap-3 p-3 bg-surface-container-lowest rounded-lg border border-outline-variant hover:border-secondary transition-colors" href="mailto:info@smanusantara.sch.id">
                    <span class="material-symbols-outlined text-secondary">mail</span>
                    <span class="font-label-md">Email Akademik</span>
                </a>
            </div>
        </section>
    </aside>
</main>
<x-guest-footer />
<!-- FAB -->
<div class="fixed bottom-8 right-8 z-40">
    <a href="{{ route('ppdb.form') }}" class="bg-secondary-container text-on-secondary-container w-16 h-16 rounded-full shadow-2xl flex items-center justify-center hover:scale-110 active:scale-95 transition-all group">
        <span class="material-symbols-outlined scale-125 group-hover:rotate-12 transition-transform">edit_square</span>
        <div class="absolute right-full mr-4 bg-primary text-on-primary px-4 py-2 rounded-lg text-sm font-bold whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none shadow-lg">
            Daftar Sekarang
        </div>
    </a>
</div>
</x-layouts.guest>
