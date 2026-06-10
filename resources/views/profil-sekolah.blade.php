<x-layouts.guest title="Profil SMA Nusantara - Keunggulan Akademik & Karakter">
    <x-slot:styles>
        <style>
            .bento-grid {
                display: grid;
                grid-template-columns: repeat(12, 1fr);
                gap: 24px;
            }
        </style>
    </x-slot:styles>
    <x-guest-navbar />

    <main>
        <section data-gsap-hero class="relative h-[600px] flex items-center overflow-hidden">
            <div class="absolute inset-0 z-0">
                <img alt="School building" class="w-full h-full object-cover brightness-[0.4]"
                    fetchpriority="high" src="{{ setting('profil_hero_image', 'https://lh3.googleusercontent.com/aida-public/AB6AXuAnZP2g6rCfqqKPBmHoKagc4Lu5Utv5BOC1FouBe7GItw0aRuGjCGhOXevMHZ6lt678FS2UAaxpEPDdpWuyMCFSDiydiPGT2na-UxMeB3L0yU7ZDyxbigYlbA_kZ7hBGVWDAWZbMXbrvTSFkjPPCJeaq7Fs73LyEZ4LbYPz50HUUa98ScMdqpJ77CGdjHxhfizfBynw5VBcG_4jBUq4CmHSnLQxbnd_jPLbZcTkoUjwhx8thuzV7RhEe7Fwn7j3Re4nnkxyI611aGQ_') }}">
            </div>
            <div class="relative z-10 px-margin-desktop max-w-max-width mx-auto w-full text-white">
                <div class="max-w-2xl">
                    <h1 data-gsap-hero-item class="font-headline-xl text-headline-xl mb-4 leading-tight">{{ setting('profil_hero_title', 'Profil SMA Nusantara') }}</h1>
                    <p data-gsap-hero-item
                        class="font-body-lg text-body-lg text-surface-container-highest max-w-lg mb-8">{{ setting('profil_hero_subtitle', 'Membangun generasi pemimpin masa depan dengan integritas tinggi, wawasan global, dan penguasaan teknologi yang adaptif.') }}</p>
                    <div data-gsap-hero-item class="flex gap-4">
                        <button
                            class="px-8 py-3 rounded-lg bg-secondary-container text-on-secondary-container font-label-md flex items-center gap-2 hover:bg-secondary-fixed transition-all">Eksplorasi
                            Kampus <span class="material-symbols-outlined">arrow_forward</span></button>
                    </div>
                </div>
            </div>
            <div class="absolute bottom-0 left-0 w-full h-24 bg-gradient-to-t from-background to-transparent"></div>
        </section>

        <section data-gsap="fade-up" class="py-20 px-margin-desktop max-w-max-width mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div class="relative">
                    <div
                        class="absolute -top-6 -left-6 w-24 h-24 bg-secondary-fixed-dim opacity-20 rounded-full blur-2xl">
                    </div>
                    <div
                        class="p-10 bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-4 opacity-5">
                            <span class="material-symbols-outlined text-[120px]">visibility</span>
                        </div>
                        <h2 class="font-headline-lg text-headline-lg text-primary mb-6 flex items-center gap-3">
                            <span
                                class="p-2 bg-primary-container text-on-primary-container rounded-lg material-symbols-outlined">lightbulb</span>
                            Visi Kami
                        </h2>
                        <p class="font-body-lg text-body-lg text-on-surface-variant italic leading-relaxed">"{{ setting('visi', 'Menjadi lembaga pendidikan terkemuka yang menghasilkan lulusan unggul dalam prestasi, berkarakter mulia, dan berwawasan lingkungan menuju persaingan global.') }}"</p>
                    </div>
                </div>
                <div>
                    <h2 class="font-headline-lg text-headline-lg text-primary mb-8">Misi Sekolah</h2>
                    <ul class="space-y-6">
                        @php $misiList = explode("\n", setting('misi', "Menyelenggarakan proses pembelajaran yang inovatif, kreatif, dan berbasis teknologi informasi.\nMenumbuhkembangkan budi pekerti luhur dan nilai-nilai keagamaan dalam seluruh aspek kehidupan sekolah.\nMengembangkan potensi bakat dan minat siswa melalui kegiatan ekstrakurikuler yang beragam.")); @endphp
                        @foreach($misiList as $misiIndex => $misiItem)
                        @if(trim($misiItem))
                        <li class="flex gap-4 group">
                            <div
                                class="flex-shrink-0 w-12 h-12 rounded-full bg-secondary text-on-secondary flex items-center justify-center font-bold transition-transform group-hover:scale-110">
                                {{ $misiIndex + 1 }}</div>
                            <p class="font-body-md text-body-md text-on-surface-variant pt-2">{{ trim($misiItem) }}</p>
                        </li>
                        @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </section>

        <section data-gsap="fade-up" class="py-20 bg-surface-container-low overflow-hidden">
            <div class="px-margin-desktop max-w-max-width mx-auto">
                <div class="flex flex-col md:flex-row gap-16 items-start">
                    <div data-gsap="fade-left" class="md:w-1/3 sticky top-24">
                        <span class="text-secondary font-bold tracking-widest text-label-md">PERJALANAN KAMI</span>
                        <h2 class="font-headline-xl text-headline-xl text-primary mt-2 mb-6">Sejarah & Jejak Langkah
                        </h2>
                        <p class="font-body-md text-body-md text-on-surface-variant">Didirikan di atas mimpi besar untuk
                            mencerdaskan kehidupan bangsa, SMA Nusantara telah bertransformasi dari sebuah gedung
                            sederhana menjadi institusi pendidikan modern yang membanggakan.</p>
                    </div>
                    <div data-gsap="fade-right"
                        class="md:w-2/3 space-y-12 relative border-l-2 border-outline-variant pl-12 ml-6">
                        <div class="relative transition-all duration-300 hover:translate-x-2">
                            <div
                                class="absolute -left-[58px] top-0 w-4 h-4 rounded-full bg-secondary border-4 border-white">
                            </div>
                            <span class="font-headline-sm text-headline-sm text-primary">1995: Fondasi Awal</span>
                            <p class="mt-2 font-body-md text-body-md text-on-surface-variant">Berdiri sebagai inisiatif
                                komunitas lokal dengan 3 kelas awal dan semangat pengabdian yang tinggi.</p>
                        </div>
                        <div class="relative transition-all duration-300 hover:translate-x-2">
                            <div
                                class="absolute -left-[58px] top-0 w-4 h-4 rounded-full bg-secondary border-4 border-white">
                            </div>
                            <span class="font-headline-sm text-headline-sm text-primary">2008: Transformasi
                                Digital</span>
                            <p class="mt-2 font-body-md text-body-md text-on-surface-variant">Mulai mengintegrasikan
                                laboratorium komputer modern dan sistem manajemen sekolah berbasis web.</p>
                        </div>
                        <div class="relative transition-all duration-300 hover:translate-x-2">
                            <div
                                class="absolute -left-[58px] top-0 w-4 h-4 rounded-full bg-secondary border-4 border-white">
                            </div>
                            <span class="font-headline-sm text-headline-sm text-primary">2015: Akreditasi A &
                                Ekspansi</span>
                            <p class="mt-2 font-body-md text-body-md text-on-surface-variant">Menerima predikat
                                Akreditasi A secara berturut-turut dan membangun sayap gedung olahraga baru.</p>
                        </div>
                        <div class="relative transition-all duration-300 hover:translate-x-2">
                            <div
                                class="absolute -left-[58px] top-0 w-4 h-4 rounded-full bg-secondary border-4 border-white">
                            </div>
                            <span class="font-headline-sm text-headline-sm text-primary">2023: Sekolah Berbasis
                                Internasional</span>
                            <p class="mt-2 font-body-md text-body-md text-on-surface-variant">Mengadopsi kurikulum
                                hybrid dan bermitra dengan berbagai institusi pendidikan global ternama.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section data-gsap="fade-up" class="py-20 px-margin-desktop max-w-max-width mx-auto">
            <div class="text-center mb-16">
                <h2 class="font-headline-lg text-headline-lg text-primary">Struktur Organisasi</h2>
                <div class="w-24 h-1 bg-secondary mx-auto mt-4 rounded-full"></div>
            </div>
            <div
                class="bg-surface-container-lowest border border-outline-variant rounded-xl p-8 shadow-sm flex flex-col items-center">
                <div
                    class="w-full max-w-4xl aspect-[16/9] bg-surface-container-high rounded-lg flex flex-col items-center justify-center text-on-surface-variant overflow-hidden relative group cursor-zoom-in">
                    <img alt="Struktur Organisasi" loading="lazy" class="w-full h-full object-cover opacity-80"
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuCnXIEYTFGCRmOxfPjSnrDvqD5HP20SezHcx5pOkktaNGPSzE3TunzjSk2YWXnVRCTXtdgvemeJIGE20I7gBWR0wswXHELGSacNr81XOrS3-fjs0Vc36nKtA-arVIjjVRJeTceVAGSdFI1bUrxqv6v9VAccs1r7g1ezrTSYKTlErm24IBXfJ43xT6qYcQGQahqg-MtBj_CfHZUCaFwkWGzwD6bLIOEh_wg2rXEnWkMAdefQ2p3QqiabkNA7BX2hfPQ-S2EEIIMQ3jBO">
                    <div
                        class="absolute inset-0 bg-primary/20 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                        <div
                            class="px-6 py-3 bg-white text-primary rounded-full font-bold shadow-lg flex items-center gap-2">
                            <span class="material-symbols-outlined">zoom_in</span> Lihat Bagan Lengkap
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 w-full mt-12 text-center">
                    <div class="p-4">
                        <div
                            class="w-20 h-20 bg-primary-container text-on-primary-container rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="material-symbols-outlined text-4xl">person</span></div>
                        <p class="font-bold text-primary">Drs. H. Ahmad Fauzi</p>
                        <p class="text-body-sm text-on-surface-variant">Kepala Sekolah</p>
                    </div>
                    <div class="p-4">
                        <div
                            class="w-20 h-20 bg-secondary-container text-on-secondary-container rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="material-symbols-outlined text-4xl">person</span></div>
                        <p class="font-bold text-primary">Siti Aminah, M.Pd</p>
                        <p class="text-body-sm text-on-surface-variant">Wakil Akademik</p>
                    </div>
                    <div class="p-4">
                        <div
                            class="w-20 h-20 bg-secondary-container text-on-secondary-container rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="material-symbols-outlined text-4xl">person</span></div>
                        <p class="font-bold text-primary">Budi Santoso, S.T</p>
                        <p class="text-body-sm text-on-surface-variant">Wakil Sarpras</p>
                    </div>
                    <div class="p-4">
                        <div
                            class="w-20 h-20 bg-secondary-container text-on-secondary-container rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="material-symbols-outlined text-4xl">person</span></div>
                        <p class="font-bold text-primary">Maya Lestari, S.Psi</p>
                        <p class="text-body-sm text-on-surface-variant">Kesiswaan</p>
                    </div>
                </div>
            </div>
        </section>

        <section data-gsap="fade-up" class="py-20 px-margin-desktop max-w-max-width mx-auto">
            <h2 class="font-headline-lg text-headline-lg text-primary mb-12">Fasilitas Unggulan</h2>
            <div class="bento-grid h-[800px] md:h-[600px]">
                <div
                    class="col-span-12 md:col-span-8 row-span-1 md:row-span-2 relative rounded-xl overflow-hidden group">
                    <img alt="Laboratorium" loading="lazy"
                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuBOdsaIAmktjqLINalHx9v2PBKa017lpfSIWD1r63laToiEVMTr6RTVdAOnHuP9__4FXq0WEWFekMszzxKQLa3OECah93Wpl2Jm8rC6R2htZNFSU72Zl371Pxkgc1dbEMb-v7uMZTIWdLoQ5qkjM3m8nV13fSiPMKVV2u1ahyX7A6otFXuVCCRsWCdTCeUo6VmGICEmlbJb5OrW1RALq1g6jDlETHOYVnDIanvUMpXVGwW-YN_SUiFOMp34hHwUNq6Aq4TWDinpSXKL">
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent flex flex-col justify-end p-8">
                        <span class="text-secondary-fixed text-label-md font-bold uppercase tracking-wider mb-2">Sains &
                            Riset</span>
                        <h3 class="text-white font-headline-md text-headline-md">Laboratorium Terpadu</h3>
                        <p class="text-surface-variant font-body-md mt-2 max-w-lg">Dilengkapi dengan peralatan
                            penelitian standar internasional untuk Fisika, Kimia, dan Biologi.</p>
                    </div>
                </div>
                <div class="col-span-12 md:col-span-4 row-span-1 relative rounded-xl overflow-hidden group">
                    <img alt="Perpustakaan" loading="lazy"
                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuA2j5GCf_kpLWdJAA6OQsoJ7g6wXut7pXiutI6YQb06bnRONmivSCqUjlgJvAQBYsLrzmevivIRGkAHV3PSCIX03-g1UyBihyyq3qsi2iLq5sS1SD2TNlPClwnqdWc0NA3T9kXsD1TyYy-BRNinHfyT4CuM6zn2cRYXQLWDS-CUlgfDaTISilk1jgQ_uuR0WOfVNSLh1YdjkURP1ZFf1V1Lwibn3QeAesRPOCwHYkMtWDFf200Psx-sMy3FXT-zH-wBE_0K9ULNRQxx">
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent flex flex-col justify-end p-6">
                        <h3 class="text-white font-headline-sm text-headline-sm">Perpustakaan Digital</h3>
                    </div>
                </div>
                <div class="col-span-12 md:col-span-4 row-span-1 relative rounded-xl overflow-hidden group">
                    <img alt="Olahraga" loading="lazy"
                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuCEJf_la7ljUxmg9PUBqCuYSSShgts_xGmJBSbvzbZAs7O_OP8SgvFwfSCDnJGQOUXEkGrABDXlm_QYvntq6i6KVT0yHq1vGb1I53ONlRPJoxbv4rpJpa08P3SDRFC_KskRDpckBd-eycZJ57Zr7uqNQL4Ee-brDqjpHNZjvax33usAhcpKqAHYxgaCYOBYIWgbT4uHjVrphjVy8QQ5el_wceaqV7pn9eNegLiscT3j7EoOlQqTWcEgqgi2uzK1uVNsZO2eSWBYP8jH">
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent flex flex-col justify-end p-6">
                        <h3 class="text-white font-headline-sm text-headline-sm">Kompleks Olahraga</h3>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-16 bg-primary text-on-primary">
            <div class="px-margin-desktop max-w-max-width mx-auto">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                    <div>
                        <p class="font-headline-xl text-headline-xl text-secondary-fixed mb-1">28+</p>
                        <p class="font-label-md text-label-md uppercase tracking-widest opacity-80">Tahun Pengalaman</p>
                    </div>
                    <div>
                        <p class="font-headline-xl text-headline-xl text-secondary-fixed mb-1">1.2k+</p>
                        <p class="font-label-md text-label-md uppercase tracking-widest opacity-80">Siswa Aktif</p>
                    </div>
                    <div>
                        <p class="font-headline-xl text-headline-xl text-secondary-fixed mb-1">85+</p>
                        <p class="font-label-md text-label-md uppercase tracking-widest opacity-80">Tenaga Pengajar</p>
                    </div>
                    <div>
                        <p class="font-headline-xl text-headline-xl text-secondary-fixed mb-1">98%</p>
                        <p class="font-label-md text-label-md uppercase tracking-widest opacity-80">Lulus PTN</p>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <x-guest-footer />
</x-layouts.guest>