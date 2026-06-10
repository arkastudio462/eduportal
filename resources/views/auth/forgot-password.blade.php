<x-layouts.guest title="Lupa Password | EduPortal">
    <div class="min-h-screen flex items-center justify-center bg-login-hero px-margin-mobile md:px-margin-desktop">
        <div class="w-full max-w-[440px] bg-surface-container-lowest shadow-lg rounded-xl overflow-hidden">
            <div class="p-8 md:p-10 flex flex-col items-center">
                <div class="mb-8 text-center">
                    <h1 class="font-headline-md text-headline-md text-primary mb-2">Lupa Password</h1>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">Masukkan email Anda dan kami akan kirim tautan reset</p>
                </div>

                <x-auth-session-status class="mb-4 w-full" :status="session('status')" />

                <form class="w-full space-y-6" method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="space-y-2">
                        <label class="font-label-md text-label-md text-primary ml-1" for="email">Email</label>
                        <input class="w-full px-4 py-3 bg-surface border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all font-body-md text-body-md" id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="Masukkan email">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <button class="w-full bg-primary text-on-primary font-label-md text-label-md py-4 rounded-lg hover:bg-primary-container transition-all" type="submit">
                        Kirim Tautan Reset
                    </button>
                </form>

                <div class="mt-8 text-center w-full">
                    <a class="text-primary font-bold hover:underline" href="{{ route('login') }}">Kembali ke Login</a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.guest>
