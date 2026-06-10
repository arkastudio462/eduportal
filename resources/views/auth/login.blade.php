<x-layouts.guest title="Login | EduPortal SMA Nusantara">
    <x-slot:styles>
        <style>
            .bg-login-hero {
                background-image: linear-gradient(rgba(0, 4, 33, 0.7), rgba(0, 4, 33, 0.7)), url('https://lh3.googleusercontent.com/aida-public/AB6AXuAEthKAcNc2yI8FY_k4tOzBZR_1adcSEiQ7VMvjuMa47TLYXjEP9EFXycTIm0dhHWRrpKEFHqhb2oDahSxEy4NpH9AMIjlZHlpw62Cu4Hif-yff895UAKypOVNxdn_bNi2NowM7zTRatNu6mo4-_NwyaiyOv8_MvPcREnSXZhOKU20708BCrpfWoynxFPqAo6qlQL_VdRGHymdd9x2vt-9OaUFsyyyjy08bt4Z1-KKMrW438TplT3weSNTH6OjDVTxcLLIiiBc-ksjR');
                background-size: cover;
                background-position: center;
            }
        </style>
    </x-slot:styles>

    <div class="min-h-screen flex items-center justify-center bg-login-hero px-margin-mobile md:px-margin-desktop">
        <div class="w-full max-w-[440px] bg-surface-container-lowest shadow-lg rounded-xl overflow-hidden">
            <div class="p-8 md:p-10 flex flex-col items-center">
                <div class="mb-8">
                    <img alt="SMA Nusantara Logo" class="h-12 md:h-14 w-auto object-contain" src="https://placehold.co/120x56/000421/feae2c?text=SMAN">
                </div>
                <div class="text-center mb-8">
                    <h1 class="font-headline-md text-headline-md text-primary mb-2">Selamat Datang</h1>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">Silakan masuk ke akun EduPortal Anda</p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4 w-full" :status="session('status')" />

                <form class="w-full space-y-6" method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email -->
                    <div class="space-y-2">
                        <label class="font-label-md text-label-md text-primary ml-1" for="email">Email / NISN</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline">person</span>
                            <input class="w-full pl-12 pr-4 py-3 bg-surface border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all font-body-md text-body-md" id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="Masukkan email">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="space-y-2">
                        <label class="font-label-md text-label-md text-primary ml-1" for="password">Kata Sandi</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline">lock</span>
                            <input class="w-full pl-12 pr-12 py-3 bg-surface border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all font-body-md text-body-md" id="password" type="password" name="password" required autocomplete="current-password" placeholder="Masukkan kata sandi">
                            <button class="absolute right-4 top-1/2 -translate-y-1/2 text-outline hover:text-primary transition-colors" type="button" onclick="togglePassword()">
                                <span class="material-symbols-outlined" id="password-icon">visibility</span>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center cursor-pointer group">
                            <input class="w-4 h-4 rounded border-outline-variant text-secondary-container focus:ring-secondary-container" type="checkbox" name="remember">
                            <span class="ml-2 font-body-sm text-body-sm text-on-surface-variant group-hover:text-primary transition-colors">Ingat Saya</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a class="font-body-sm text-body-sm text-secondary hover:underline transition-all" href="{{ route('password.request') }}">Lupa Password?</a>
                        @endif
                    </div>

                    <button class="w-full bg-[#F5A623] hover:bg-[#E0961F] text-primary font-label-md text-label-md py-4 rounded-lg shadow-sm hover:shadow-md active:scale-[0.98] transition-all flex items-center justify-center gap-2" type="submit">
                        MASUK
                        <span class="material-symbols-outlined text-[20px]">login</span>
                    </button>
                </form>

                <div class="mt-4 w-full relative flex items-center gap-3">
                    <div class="flex-1 h-px bg-outline-variant"></div>
                    <span class="font-body-sm text-body-sm text-on-surface-variant flex-none">atau</span>
                    <div class="flex-1 h-px bg-outline-variant"></div>
                </div>

                <button id="btn-biometric-login" class="mt-4 w-full bg-surface border border-outline-variant hover:bg-surface-container-low text-on-surface font-label-md text-label-md py-4 rounded-lg transition-all flex items-center justify-center gap-2" type="button" style="display:none">
                    <span class="material-symbols-outlined text-[20px]">fingerprint</span>
                    MASUK DENGAN BIOMETRIK
                </button>

                <div class="mt-8 text-center w-full">
                    <p class="font-body-sm text-body-sm text-on-surface-variant">
                        Belum punya akun?
                        <a class="text-primary font-bold hover:underline" href="{{ route('register') }}">Daftar</a>
                    </p>
                </div>

                <div class="mt-6 pt-6 border-t border-outline-variant w-full text-center">
                    <p class="font-body-sm text-body-sm text-on-surface-variant">
                        Kesulitan masuk? Hubungi <span class="text-primary font-bold">IT Helpdesk</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="fixed bottom-6 text-center w-full px-4 left-0">
        <p class="font-body-sm text-body-sm text-surface/80">© 2024 SMA Nusantara. Seluruh Hak Cipta Dilindungi.</p>
    </div>

    <x-slot:scripts>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var btn = document.getElementById('btn-biometric-login');
            if (!btn) return;

            if (typeof window.WebAuthn !== 'undefined' && window.WebAuthn.isWebAuthnSupported()) {
                btn.style.display = 'flex';
            }

            btn.addEventListener('click', async function() {
                var email = document.getElementById('email')?.value;
                if (!email) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({ icon: 'warning', title: 'Masukkan email terlebih dahulu', timer: 2000, showConfirmButton: false });
                    }
                    return;
                }

                btn.disabled = true;
                btn.innerHTML = '<span class="material-symbols-outlined animate-spin text-[20px]">progress_activity</span> Memverifikasi...';

                try {
                    var result = await window.WebAuthn.authenticateBiometric(email);
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({ icon: 'success', title: 'Login berhasil', timer: 1500, showConfirmButton: false });
                    }
                    window.location.href = result.redirect;
                } catch (err) {
                    btn.disabled = false;
                    btn.innerHTML = '<span class="material-symbols-outlined text-[20px]">fingerprint</span> MASUK DENGAN BIOMETRIK';
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: err.message, timer: 3000, showConfirmButton: false });
                    }
                }
            });
        });
    </script>
    </x-slot:scripts>

</x-layouts.guest>
