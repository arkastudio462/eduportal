<x-layouts.guest title="Kontak | SMA Nusantara">
<x-slot:styles>
<style>
    .material-symbols-outlined {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }
    .contact-card-shadow {
        box-shadow: 0 4px 20px -2px rgba(0, 4, 33, 0.04);
    }
</style>
</x-slot:styles>
<x-guest-navbar />

<main class="max-w-max-width mx-auto px-margin-mobile md:px-margin-desktop py-12">
    <header data-gsap="fade-up" class="mb-16">
        <h1 class="font-headline-xl text-headline-xl mb-4 text-primary">Hubungi Kami</h1>
        <p class="font-body-lg text-body-lg text-on-surface-variant max-w-2xl">Kami siap membantu Anda. Silakan hubungi kami melalui formulir di bawah ini atau datang langsung ke kampus kami untuk informasi lebih lanjut mengenai pendaftaran dan program akademik.</p>
    </header>

    <div data-gsap="stagger" class="grid grid-cols-1 lg:grid-cols-12 gap-gutter">
        <section class="lg:col-span-7 bg-surface-container-lowest border border-outline-variant p-8 md:p-10 rounded-xl contact-card-shadow">
            <h2 class="font-headline-md text-headline-md mb-8 text-primary">Kirim Pesan</h2>

            <form class="space-y-6" method="POST" action="{{ route('kontak.kirim') }}">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="font-label-md text-label-md text-on-surface-variant" for="nama">Nama Lengkap</label>
                        <input class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all @error('nama') border-error @enderror" placeholder="Masukkan nama Anda" type="text" id="nama" name="nama" value="{{ old('nama') }}" required>
                        @error('nama') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="font-label-md text-label-md text-on-surface-variant" for="email">Alamat Email</label>
                        <input class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all @error('email') border-error @enderror" placeholder="email@contoh.com" type="email" id="email" name="email" value="{{ old('email') }}" required>
                        @error('email') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant" for="subjek">Subjek</label>
                    <select class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary transition-all @error('subjek') border-error @enderror" id="subjek" name="subjek" required>
                        <option value="">Pilih subjek</option>
                        <option value="Informasi Pendaftaran" {{ old('subjek') == 'Informasi Pendaftaran' ? 'selected' : '' }}>Informasi Pendaftaran</option>
                        <option value="Pertanyaan Akademik" {{ old('subjek') == 'Pertanyaan Akademik' ? 'selected' : '' }}>Pertanyaan Akademik</option>
                        <option value="Kerja Sama" {{ old('subjek') == 'Kerja Sama' ? 'selected' : '' }}>Kerja Sama</option>
                        <option value="Lainnya" {{ old('subjek') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    @error('subjek') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant" for="pesan">Pesan</label>
                    <textarea class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all @error('pesan') border-error @enderror" placeholder="Tuliskan pesan Anda di sini..." rows="5" id="pesan" name="pesan" required>{{ old('pesan') }}</textarea>
                    @error('pesan') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <button class="w-full md:w-auto bg-secondary-container text-on-secondary-container px-10 py-4 rounded-xl font-label-md hover:opacity-90 active:scale-95 transition-all flex items-center justify-center gap-2" type="submit">
                    <span class="material-symbols-outlined">send</span>
                    Kirim Pesan
                </button>
            </form>
        </section>

        <aside class="lg:col-span-5 flex flex-col gap-gutter">
            <div class="bg-primary text-on-primary p-8 rounded-xl contact-card-shadow flex-grow">
                <h3 class="font-headline-sm text-headline-sm mb-6">Informasi Kontak</h3>
                <ul class="space-y-6">
                    <li class="flex items-start gap-4">
                        <span class="material-symbols-outlined text-secondary-container mt-1">location_on</span>
                        <div>
                            <p class="font-label-md text-primary-fixed">Alamat</p>
                            <p class="text-body-md opacity-90">{{ setting('kontak_alamat', 'Jl. Pendidikan No. 123, Jakarta Selatan, DKI Jakarta 12345') }}</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-4">
                        <span class="material-symbols-outlined text-secondary-container mt-1">call</span>
                        <div>
                            <p class="font-label-md text-primary-fixed">Telepon</p>
                            <p class="text-body-md opacity-90">{{ setting('kontak_telepon', '(021) 555-0123') }}</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-4">
                        <span class="material-symbols-outlined text-secondary-container mt-1">mail</span>
                        <div>
                            <p class="font-label-md text-primary-fixed">Email</p>
                            <p class="text-body-md opacity-90">{{ setting('kontak_email', 'info@smanusantara.sch.id') }}</p>
                        </div>
                    </li>
                </ul>
                <div class="mt-10 pt-8 border-t border-primary-container">
                    <p class="font-label-md text-primary-fixed mb-4">Ikuti Kami</p>
                    <div class="flex gap-4">
                        <a class="w-10 h-10 rounded-full bg-primary-container flex items-center justify-center hover:bg-secondary-container transition-colors" href="https://instagram.com" target="_blank"><span class="material-symbols-outlined text-[20px]">social_leaderboard</span></a>
                        <a class="w-10 h-10 rounded-full bg-primary-container flex items-center justify-center hover:bg-secondary-container transition-colors" href="https://youtube.com" target="_blank"><span class="material-symbols-outlined text-[20px]">camera</span></a>
                        <a class="w-10 h-10 rounded-full bg-primary-container flex items-center justify-center hover:bg-secondary-container transition-colors" href="https://facebook.com" target="_blank"><span class="material-symbols-outlined text-[20px]">smart_display</span></a>
                    </div>
                </div>
            </div>

            <div class="bg-surface-container-high border border-outline-variant p-8 rounded-xl">
                <h3 class="font-headline-sm text-headline-sm mb-4 text-primary flex items-center gap-2">
                    <span class="material-symbols-outlined">schedule</span>
                    Jam Operasional
                </h3>
                <div class="space-y-3">
                    <div class="flex justify-between border-b border-outline-variant pb-2">
                        <span class="text-on-surface-variant">Senin - Kamis</span>
                        <span class="font-semibold text-primary">{{ setting('kontak_jam_senin_kamis', '07:00 - 15:30') }}</span>
                    </div>
                    <div class="flex justify-between border-b border-outline-variant pb-2">
                        <span class="text-on-surface-variant">Jumat</span>
                        <span class="font-semibold text-primary">{{ setting('kontak_jam_jumat', '07:00 - 11:30') }}</span>
                    </div>
                    <div class="flex justify-between border-b border-outline-variant pb-2">
                        <span class="text-on-surface-variant">Sabtu</span>
                        <span class="font-semibold text-primary">{{ setting('kontak_jam_sabtu', '08:00 - 12:00') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-on-surface-variant">Minggu</span>
                        <span class="text-secondary font-semibold italic">Tutup</span>
                    </div>
                </div>
            </div>
        </aside>
    </div>

    <section class="mt-12 rounded-xl overflow-hidden border border-outline-variant contact-card-shadow">
        <div class="bg-surface-container-lowest p-6 flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h2 class="font-headline-sm text-headline-sm text-primary">Lokasi Kampus</h2>
                <p class="text-on-surface-variant text-body-sm">Kunjungi kami untuk melihat fasilitas pendidikan terbaik di Jakarta Selatan.</p>
            </div>
            <a class="bg-primary text-on-primary px-6 py-2 rounded-lg font-label-md flex items-center gap-2 hover:opacity-90" href="{{ setting('kontak_maps_url', 'https://maps.google.com') }}" target="_blank">
                <span class="material-symbols-outlined">map</span>
                Buka di Maps
            </a>
        </div>
        <div class="relative w-full aspect-video md:aspect-[21/9]">
            <img alt="Lokasi SMA Nusantara" loading="lazy" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuC4SpkTHafP2O_Z41lXaBApC7Eczq0_Pr1gOjIq27flWy-m195Hg1HctZzy3gKM8XJZhKM_vZDVewhxdd41RSwAYSeIijAWq2V3dKobTcbS7k1CGRco66NaS-PFIBdXGKwBMCO42NE7-W_M-DG8LbAu1CTWrknrI4JXiSeKwg5z4BGpPOYRlQFTdddz8LN-2mFV68XJjV1GesUBvR62K1perlTtaTqVVvnOPKdUmfJgH-Oc93Vz7jTu-1pVJbf1b06nYGa72k__Wsbz">
            <div class="absolute bottom-6 left-6 md:bottom-12 md:left-12 bg-white/90 backdrop-blur-md p-4 rounded-xl border border-white shadow-lg hidden sm:block">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-primary rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-white" style="font-variation-settings: 'FILL' 1;">school</span>
                    </div>
                    <div>
                        <p class="font-headline-sm text-sm text-primary">SMA Nusantara Main Campus</p>
                        <p class="text-[12px] text-on-surface-variant">Jl. Pendidikan No. 123</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<x-guest-footer />
</x-layouts.guest>
