<footer class="w-full bg-primary mt-auto">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-gutter px-margin-desktop py-12 max-w-max-width mx-auto">
        <div class="space-y-4">
            @if (setting('logo_sekolah'))
                <img src="{{ setting('logo_sekolah') }}" alt="Logo {{ setting('nama_sekolah') }}" class="h-12 w-auto object-contain brightness-0 invert">
            @endif
            <div class="font-headline-sm text-headline-sm font-bold text-on-primary">{{ setting('nama_sekolah', 'SMA Nusantara') }}</div>
            <p class="font-body-sm text-primary-fixed-dim opacity-80">{{ setting('tentang', 'Terdepan dalam kualitas, unggul dalam prestasi, dan teguh dalam karakter.') }}</p>
            <div class="flex gap-4 pt-2">
                <a href="#" class="material-symbols-outlined text-on-primary cursor-pointer hover:text-secondary-fixed text-[24px]">public</a>
                <a href="#" class="material-symbols-outlined text-on-primary cursor-pointer hover:text-secondary-fixed text-[24px]">video_library</a>
                <a href="#" class="material-symbols-outlined text-on-primary cursor-pointer hover:text-secondary-fixed text-[24px]">groups</a>
            </div>
        </div>
        <div class="space-y-4">
            <h4 class="text-secondary-fixed font-bold uppercase tracking-widest text-[12px]">Alamat & Kontak</h4>
            <ul class="space-y-2">
                <li class="font-body-sm text-primary-fixed-dim opacity-80">{{ setting('alamat', 'Jl. Pendidikan No. 123, Jakarta Selatan') }}</li>
                <li class="font-body-sm text-primary-fixed-dim opacity-80">Tel: {{ setting('telepon', '(021) 555-0123') }}</li>
                <li class="font-body-sm text-primary-fixed-dim opacity-80">Email: {{ setting('email_sekolah', 'info@smanusantara.sch.id') }}</li>
            </ul>
        </div>
        <div class="space-y-4">
            <h4 class="text-secondary-fixed font-bold uppercase tracking-widest text-[12px]">Tautan Cepat</h4>
            <ul class="space-y-2">
                <li><a class="font-body-sm text-primary-fixed-dim opacity-80 hover:text-secondary-fixed-dim" href="{{ route('profil-sekolah') }}">Profil Sekolah</a></li>
                <li><a class="font-body-sm text-primary-fixed-dim opacity-80 hover:text-secondary-fixed-dim" href="{{ route('login') }}">Portal Siswa</a></li>
                <li><a class="font-body-sm text-primary-fixed-dim opacity-80 hover:text-secondary-fixed-dim" href="{{ route('login') }}">Portal Guru</a></li>
                    <li><a class="font-body-sm text-primary-fixed-dim opacity-80 hover:text-secondary-fixed-dim" href="{{ route('alumni') }}">Alumni</a></li>
            </ul>
        </div>
        <div class="space-y-4">
            <h4 class="text-secondary-fixed font-bold uppercase tracking-widest text-[12px]">Berlangganan</h4>
            <p class="font-body-sm text-primary-fixed-dim opacity-80">Dapatkan update berita sekolah langsung di email Anda.</p>
            <form class="flex" method="GET" action="{{ route('home') }}">
                <input name="email" class="bg-primary-container border border-outline-variant rounded-l-lg p-2 text-on-primary w-full outline-none focus:ring-1 focus:ring-secondary-fixed" placeholder="Email Anda" type="email">
                <button type="submit" class="bg-secondary text-on-primary px-4 rounded-r-lg font-bold">Kirim</button>
            </form>
        </div>
    </div>
    <div class="border-t border-primary-container px-margin-desktop py-6 max-w-max-width mx-auto flex flex-col md:flex-row justify-between items-center gap-4">
        <p class="font-body-sm text-primary-fixed-dim opacity-60">© {{ date('Y') }} {{ setting('nama_sekolah', 'SMA Nusantara') }}. All rights reserved.</p>
        <div class="flex gap-6">
            <a class="font-body-sm text-primary-fixed-dim opacity-60 hover:text-on-primary" href="#">Syarat & Ketentuan</a>
            <a class="font-body-sm text-primary-fixed-dim opacity-60 hover:text-on-primary" href="#">Kebijakan Privasi</a>
        </div>
    </div>
</footer>
