<x-layouts.guest title="Akademik | SMA Nusantara">
    <x-slot:styles>
        <style>
            .material-symbols-outlined {
                font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            }
        </style>
    </x-slot:styles>
    <x-guest-navbar />

    <main class="max-w-max-width mx-auto px-margin-mobile md:px-margin-desktop py-12">
        <div data-gsap-hero class="relative w-full h-[400px] rounded-xl overflow-hidden mb-16 shadow-lg">
            <img alt="Academic Excellence" class="w-full h-full object-cover"
                fetchpriority="high" src="{{ setting('akademik_hero_image', 'https://lh3.googleusercontent.com/aida-public/AB6AXuALk4l8l5bvTrlLWTb9uMUMNReQiQFIKzYdjdzVQlWzgFH9wqamfPMXR3pcx4zUZ-d-eAWRQtqmOzwl6EOuEoXwNLoCG0B8dU9-rTcdnbj8yDD5aU4kQegcBbNyqsKto-cjFH9a7kC_-s4_kzg0hoY42Hgs_IJB18B2kY5KPXgEN0Mp8_sNYatDGJe6oWEg0O8old-i2OjTito3XUI4ghoMsGJePHFgkRFM49QZbYokLcLx6krPQkBw24COsnPMySEd85eGxqfuuzud') }}">
            <div
                class="absolute inset-0 bg-gradient-to-r from-primary/80 to-transparent flex flex-col justify-center px-12 text-on-primary">
                <h1 data-gsap-hero-item class="font-headline-xl text-headline-xl mb-4">{{ setting('akademik_hero_title', 'Pusat Keunggulan Akademik') }}</h1>
                <p data-gsap-hero-item class="font-body-lg max-w-2xl opacity-90">{{ setting('akademik_hero_subtitle', 'Membentuk generasi emas Indonesia melalui kurikulum berbasis karakter, teknologi digital, dan standar internasional yang holistik.') }}</p>
            </div>
        </div>

        <section data-gsap="fade-up" class="grid grid-cols-1 md:grid-cols-3 gap-gutter mb-16">
            <div
                class="md:col-span-2 bg-surface-container-lowest p-8 rounded-xl border border-outline-variant shadow-sm">
                <div class="flex items-center gap-3 mb-6">
                    <span class="material-symbols-outlined text-secondary text-3xl">menu_book</span>
                    <h2 class="font-headline-md text-headline-md text-primary">Kurikulum Merdeka</h2>
                </div>
                <p class="font-body-md text-on-surface-variant mb-6 leading-relaxed">SMA Nusantara menerapkan Kurikulum
                    Merdeka yang memberikan keleluasaan bagi pendidik untuk menciptakan pembelajaran berkualitas yang
                    sesuai dengan kebutuhan dan lingkungan belajar peserta didik. Kami mengintegrasikan Projek Penguatan
                    Profil Pelajar Pancasila dalam setiap aspek akademik.</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="flex items-start gap-3 p-4 bg-surface rounded-lg">
                        <span class="material-symbols-outlined text-secondary">auto_awesome</span>
                        <div>
                            <p class="font-label-md">Pembelajaran Diferensiasi</p>
                            <p class="text-body-sm opacity-75">Menyesuaikan kecepatan belajar individu.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 p-4 bg-surface rounded-lg">
                        <span class="material-symbols-outlined text-secondary">psychology</span>
                        <div>
                            <p class="font-label-md">Berpikir Kritis</p>
                            <p class="text-body-sm opacity-75">Metode berbasis pemecahan masalah.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div
                class="bg-primary text-on-primary p-8 rounded-xl flex flex-col justify-between shadow-lg relative overflow-hidden group">
                <div class="relative z-10">
                    <div class="flex items-center gap-3 mb-6">
                        <span class="material-symbols-outlined text-secondary-fixed">calendar_month</span>
                        <h2 class="font-headline-sm text-headline-sm">Kalender Akademik</h2>
                    </div>
                    <p class="text-body-sm opacity-80 mb-8">Akses jadwal kegiatan belajar mengajar, ujian, dan hari
                        libur nasional untuk tahun ajaran 2024/2025.</p>
                </div>
                <button
                    class="relative z-10 w-full py-4 bg-secondary-container text-on-secondary-container rounded-lg font-label-md flex items-center justify-center gap-2 hover:bg-secondary-fixed transition-colors">
                    <span class="material-symbols-outlined">download</span> Download PDF
                </button>
                <div class="absolute -bottom-8 -right-8 opacity-10">
                    <span class="material-symbols-outlined text-[160px]">event_note</span>
                </div>
            </div>
        </section>

        <section data-gsap="fade-up" class="mb-16">
            <h2 class="font-headline-md text-headline-md text-primary mb-8 text-center">Mata Pelajaran Unggulan</h2>
            <div data-gsap="fade-up" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                <div
                    class="p-6 bg-surface-container-high rounded-xl text-center group hover:bg-primary hover:text-on-primary transition-all duration-300">
                    <span
                        class="material-symbols-outlined text-4xl mb-3 text-secondary group-hover:text-secondary-fixed">calculate</span>
                    <p class="font-label-md">Matematika</p>
                </div>
                <div
                    class="p-6 bg-surface-container-high rounded-xl text-center group hover:bg-primary hover:text-on-primary transition-all duration-300">
                    <span
                        class="material-symbols-outlined text-4xl mb-3 text-secondary group-hover:text-secondary-fixed">biotech</span>
                    <p class="font-label-md">Biologi</p>
                </div>
                <div
                    class="p-6 bg-surface-container-high rounded-xl text-center group hover:bg-primary hover:text-on-primary transition-all duration-300">
                    <span
                        class="material-symbols-outlined text-4xl mb-3 text-secondary group-hover:text-secondary-fixed">language</span>
                    <p class="font-label-md">Bhs. Inggris</p>
                </div>
                <div
                    class="p-6 bg-surface-container-high rounded-xl text-center group hover:bg-primary hover:text-on-primary transition-all duration-300">
                    <span
                        class="material-symbols-outlined text-4xl mb-3 text-secondary group-hover:text-secondary-fixed">computer</span>
                    <p class="font-label-md">Informatika</p>
                </div>
                <div
                    class="p-6 bg-surface-container-high rounded-xl text-center group hover:bg-primary hover:text-on-primary transition-all duration-300">
                    <span
                        class="material-symbols-outlined text-4xl mb-3 text-secondary group-hover:text-secondary-fixed">science</span>
                    <p class="font-label-md">Fisika</p>
                </div>
                <div
                    class="p-6 bg-surface-container-high rounded-xl text-center group hover:bg-primary hover:text-on-primary transition-all duration-300">
                    <span
                        class="material-symbols-outlined text-4xl mb-3 text-secondary group-hover:text-secondary-fixed">history_edu</span>
                    <p class="font-label-md">Sejarah</p>
                </div>
            </div>
        </section>

        <section data-gsap="fade-up" class="mb-16">
            <h2 class="font-headline-md text-headline-md text-primary mb-8">Program Unggulan</h2>
            <div class="grid grid-cols-1 md:grid-cols-12 gap-gutter">
                <div
                    class="md:col-span-7 bg-surface-container-lowest border border-outline-variant rounded-xl p-8 shadow-sm">
                    <h3 class="font-headline-sm text-headline-sm text-secondary mb-4">Digital Transformation Program
                    </h3>
                    <p class="text-body-md text-on-surface-variant mb-6">Program integrasi teknologi iPad 1:1 untuk
                        setiap siswa guna mendukung pembelajaran kolaboratif dan akses ke ribuan sumber daya edukatif
                        global.</p>
                    <img alt="Digital Lab" class="w-full h-48 object-cover rounded-lg"
                        loading="lazy" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBx-b7K2Dm_yAMjjJ2ahcu2FvOOpP8CPsJbP_pnc6Zpyg5-1vPPj_DGmXrLytwT3hogBHeCETxol1U67vA8VD9XuM3UjGSrHFfNAdcpWPOrfyoGTGjpyVlP-iLFZx1VmJo72lU0J64YIaRnYUZTqfUz1Dgbj_aiYJpPjXqH9Owan75ubSg2cv6uXr4f893kB_RYZGPKeCsXUKuQ3c9CrNUCNnvUhQ9IihmTzWlKxKaMJurHW7zeZaLxOHHMzkiiATU4lV37klEoZMrI">
                </div>
                <div class="md:col-span-5 flex flex-col gap-gutter">
                    <div class="flex-1 bg-tertiary-container text-on-tertiary-container p-6 rounded-xl">
                        <h3 class="font-bold mb-2">Global Exchange</h3>
                        <p class="text-body-sm opacity-80">Kemitraan internasional dengan sekolah di Singapura dan
                            Australia untuk pertukaran budaya.</p>
                    </div>
                    <div class="flex-1 bg-secondary-container text-on-secondary-container p-6 rounded-xl">
                        <h3 class="font-bold mb-2">Science Research Center</h3>
                        <p class="text-body-sm opacity-80">Fasilitas laboratorium canggih untuk penelitian mandiri siswa
                            dalam bidang STEM.</p>
                    </div>
                </div>
            </div>
        </section>

        <section data-gsap="fade-up" class="mb-16">
            <div class="text-center mb-12">
                <h2 class="font-headline-md text-headline-md text-primary">Ekstrakurikuler</h2>
                <p class="text-body-md text-on-surface-variant max-w-xl mx-auto mt-2">Mengembangkan minat dan bakat di
                    luar jam pelajaran akademik.</p>
            </div>
            <div data-gsap="stagger" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-gutter">
                <div
                    class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-secondary/10 rounded-lg flex items-center justify-center mb-4">
                        <span class="material-symbols-outlined text-secondary">sports_basketball</span>
                    </div>
                    <h4 class="font-headline-sm text-headline-sm mb-2">Olahraga</h4>
                    <p class="text-body-sm text-on-surface-variant">Basket, Futsal, Badminton, dan Bela Diri untuk
                        kesehatan fisik dan sportivitas.</p>
                </div>
                <div
                    class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-secondary/10 rounded-lg flex items-center justify-center mb-4">
                        <span class="material-symbols-outlined text-secondary">smart_toy</span>
                    </div>
                    <h4 class="font-headline-sm text-headline-sm mb-2">Sains & Robotik</h4>
                    <p class="text-body-sm text-on-surface-variant">Eksplorasi teknologi masa depan melalui pembuatan
                        robot dan kompetisi sains.</p>
                </div>
                <div
                    class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-secondary/10 rounded-lg flex items-center justify-center mb-4">
                        <span class="material-symbols-outlined text-secondary">palette</span>
                    </div>
                    <h4 class="font-headline-sm text-headline-sm mb-2">Seni & Budaya</h4>
                    <p class="text-body-sm text-on-surface-variant">Paduan suara, tari tradisional, seni lukis, dan
                        fotografi untuk kreativitas tanpa batas.</p>
                </div>
            </div>
        </section>

        <section data-gsap="fade-up" class="mb-16">
            <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4">
                <div>
                    <h2 class="font-headline-md text-headline-md text-primary">Tenaga Pengajar</h2>
                    <p class="text-body-md text-on-surface-variant">Didampingi oleh pendidik profesional dan
                        berpengalaman.</p>
                </div>
                <button class="text-secondary font-label-md flex items-center gap-1 hover:underline">Lihat Semua Guru
                    <span class="material-symbols-outlined">arrow_forward</span></button>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-gutter">
                <div class="group cursor-pointer">
                    <div
                        class="aspect-square rounded-xl overflow-hidden mb-4 border-2 border-transparent group-hover:border-secondary transition-all">
                        <img alt="Teacher" loading="lazy" class="w-full h-full object-cover"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuB8C-D_HxTEQZD7umMyEr-FHa1VmIQrEg3UVYi2NYyOZVc3c9l51TgW5UGmd85gJkNjD1rdTvPzNfWSOrBzsegvKFZ58480QLYV_1P70GmCik9UkiVr9e_XCxPVbbRGJGh9H2-FazSho4y5nH0XjjiyA5V3F1jkHf_MkOlEQYFfxxnSdDzcg22FYL0BaqdJqotBIDOhN4LyngsHCsxecoNOCTf1wkCR82xHs8EEr41SeiatnydrUiECYU9KZ0HXf55DYz6hUWtOvSoJ">
                    </div>
                    <h5 class="font-bold">Dr. Amanda Putri</h5>
                    <p class="text-body-sm opacity-70">Kepala Sekolah / Matematika</p>
                </div>
                <div class="group cursor-pointer">
                    <div
                        class="aspect-square rounded-xl overflow-hidden mb-4 border-2 border-transparent group-hover:border-secondary transition-all">
                        <img alt="Teacher" loading="lazy" class="w-full h-full object-cover"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuCb2XiPPIHx2JE2DoiDic41SdVIa-PNHiAz6ivV1UekpjrK7ceLAL-IXXcEGXvF6xm2QaXS0YuLJlUEvrXnJhkKogbBoF1SFeqkipWsh7HsLAFl3wshdLdw6uSzK6WfkGXcljSYcIQSHcO9sQI4dldCqsa6GjtqYsnPf1ZNeK_RaGjeX4Y2eTYmNp2d6WdOzF5nhWDIwUQEHKlsfbul-_Byyx67u65Vt7j5_XhK2QnJfBtztPz8wi3FRNtSrg03z3UIDCRlf9oHGV0O">
                    </div>
                    <h5 class="font-bold">Budi Santoso, M.Pd</h5>
                    <p class="text-body-sm opacity-70">Waka Kurikulum / Fisika</p>
                </div>
                <div class="group cursor-pointer">
                    <div
                        class="aspect-square rounded-xl overflow-hidden mb-4 border-2 border-transparent group-hover:border-secondary transition-all">
                        <img alt="Teacher" loading="lazy" class="w-full h-full object-cover"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuDB9MPxRRAASjoeMH28lQwWC85Lfhy40l2zLX2QmHcsp5xZ_aKW_hnIXUX71_Z63WEBTY7ComwfsCOsVWZo8DDbZ9zi8IM3FjrBk2i5UC6JAmF9NiMzoECl_xq4EFftZQFbc7yabq7ayMYn8TPPNaFlF_A4CtCEhYnQfCORFddTNAHskCDxEGB7Kyuh1lY1xd3vbRuqQFs5UUd_ZYSfT-jMOL3cLgpu0bu04jOLjAkFsImbWr3RudQqafZrqZSjjCm2lSZElqhSAcsO">
                    </div>
                    <h5 class="font-bold">Siti Rahma, S.S</h5>
                    <p class="text-body-sm opacity-70">Bahasa Inggris</p>
                </div>
                <div class="group cursor-pointer">
                    <div
                        class="aspect-square rounded-xl overflow-hidden mb-4 border-2 border-transparent group-hover:border-secondary transition-all">
                        <img alt="Teacher" loading="lazy" class="w-full h-full object-cover"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuDMnDvXIgksKxL2a8G45Q0cI16NyWDWTN0v2DVb6mRInLJjq6ArmaHfW7lFnvwUuGhMgZl85Ulq6EJBBKe3u0hE06Q3dx0j7Ufc37khUl5cGvkwcm-WClqgOTW7Negfp6y_eSG3zBN-xL2ne2d9Qf3Kwi7PZ5DdPiVO0DPaFayCmugzsNh9lSnWE1qgr39KHNOp1P1nCfx5pJczYJJIEJ7LvSuAzS0IQ3pRdAewz4k-3togTeAW-wTq5a7IwmQRqdv5gMBSUnzF9sIP">
                    </div>
                    <h5 class="font-bold">Eko Prasetyo, S.Kom</h5>
                    <p class="text-body-sm opacity-70">Kepala Lab / Informatika</p>
                </div>
            </div>
        </section>
    </main>

    <x-guest-footer />
</x-layouts.guest>