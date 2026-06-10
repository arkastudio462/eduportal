<x-layouts.guest title="Daftar | EduPortal SMA Nusantara">
    <div class="min-h-screen flex items-center justify-center bg-login-hero px-margin-mobile md:px-margin-desktop">
        <div class="w-full max-w-[440px] bg-surface-container-lowest shadow-lg rounded-xl overflow-hidden">
            <div class="p-8 md:p-10 flex flex-col items-center">
                <div class="mb-8">
                    <img alt="SMA Nusantara Logo" class="h-12 md:h-14 w-auto object-contain" src="https://placehold.co/120x56/000421/feae2c?text=SMAN">
                </div>
                <div class="text-center mb-8">
                    <h1 class="font-headline-md text-headline-md text-primary mb-2">Buat Akun Baru</h1>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">Daftar untuk mengakses EduPortal</p>
                </div>

                <form class="w-full space-y-6" method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Nama -->
                    <div class="space-y-2">
                        <label class="font-label-md text-label-md text-primary ml-1" for="name">Nama Lengkap</label>
                        <input class="w-full px-4 py-3 bg-surface border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all font-body-md text-body-md" id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Masukkan nama lengkap">
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Email -->
                    <div class="space-y-2">
                        <label class="font-label-md text-label-md text-primary ml-1" for="email">Email</label>
                        <input class="w-full px-4 py-3 bg-surface border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all font-body-md text-body-md" id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="Masukkan email">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="space-y-2">
                        <label class="font-label-md text-label-md text-primary ml-1" for="password">Kata Sandi</label>
                        <input class="w-full px-4 py-3 bg-surface border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all font-body-md text-body-md" id="password" type="password" name="password" required autocomplete="new-password" placeholder="Buat kata sandi">
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Confirm Password -->
                    <div class="space-y-2">
                        <label class="font-label-md text-label-md text-primary ml-1" for="password_confirmation">Konfirmasi Kata Sandi</label>
                        <input class="w-full px-4 py-3 bg-surface border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all font-body-md text-body-md" id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi kata sandi">
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <button class="w-full bg-[#F5A623] hover:bg-[#E0961F] text-primary font-label-md text-label-md py-4 rounded-lg shadow-sm hover:shadow-md active:scale-[0.98] transition-all" type="submit">
                        DAFTAR
                    </button>
                </form>

                <div class="mt-8 text-center w-full">
                    <p class="font-body-sm text-body-sm text-on-surface-variant">
                        Sudah punya akun?
                        <a class="text-primary font-bold hover:underline" href="{{ route('login') }}">Masuk</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="fixed bottom-6 text-center w-full px-4 left-0">
        <p class="font-body-sm text-body-sm text-surface/80">© 2024 SMA Nusantara. Seluruh Hak Cipta Dilindungi.</p>
    </div>
</x-layouts.guest>
