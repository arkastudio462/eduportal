<x-layouts.admin title="Pengaturan | EduPortal">
    <x-slot:styles>
    <style>
        [x-cloak] { display: none !important; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
    </x-slot:styles>

    <div class="mb-8">
        <h1 class="font-headline-lg text-headline-lg text-primary mb-2">Pengaturan Sistem</h1>
        <p class="font-body-md text-body-md text-on-surface-variant">Kelola profil dan keamanan akun Anda.</p>
    </div>

    <div x-data="{ activeTab: 'profil' }">
        <div class="flex border-b border-outline-variant mb-6 overflow-x-auto whitespace-nowrap scrollbar-hide">
            <button @click="activeTab = 'profil'" class="px-6 py-4 font-label-md text-label-md transition-colors shrink-0" :class="activeTab === 'profil' ? 'border-b-2 border-secondary text-secondary' : 'border-b-2 border-transparent text-on-surface-variant hover:text-primary hover:border-b-2 hover:border-outline-variant'">
                Profil Pengguna
            </button>
            <button @click="activeTab = 'keamanan'" class="px-6 py-4 font-label-md text-label-md transition-colors shrink-0" :class="activeTab === 'keamanan' ? 'border-b-2 border-secondary text-secondary' : 'border-b-2 border-transparent text-on-surface-variant hover:text-primary hover:border-b-2 hover:border-outline-variant'">
                Keamanan
            </button>
        </div>

        {{-- Tab: Profil Pengguna --}}
        <div x-show="activeTab === 'profil'" x-cloak class="space-y-6">
            <div class="bg-surface-container-lowest p-6 rounded-xl shadow-sm border border-outline-variant flex flex-col md:flex-row items-center md:items-start gap-8">
                <div class="relative group shrink-0" x-data="{ preview: null }" @avatar-preview.window="preview = $event.detail">
                    <img id="avatar_preview_img" :src="preview || '{{ $user->profile_photo_path ? Storage::url($user->profile_photo_path) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=000421&color=fff&size=160' }}'" alt="{{ $user->name }}" class="w-32 h-32 md:w-40 md:h-40 rounded-full object-cover border-4 border-surface-container-high shadow-md">
                    <label class="absolute bottom-1 right-1 bg-secondary-container text-on-secondary-container p-2 rounded-full cursor-pointer shadow-lg hover:scale-105 active:scale-95 transition-transform flex items-center justify-center" for="profile_photo">
                        <span class="material-symbols-outlined text-sm">edit</span>
                    </label>
                </div>
                <div class="flex-1 text-center md:text-left">
                    <h3 class="font-headline-md text-headline-md text-primary">{{ $user->name }}</h3>
                    <p class="font-label-md text-label-md text-secondary mb-4">Administrator</p>
                    <p class="font-body-sm text-body-sm text-on-surface-variant max-w-lg">Pastikan profil Anda selalu diperbarui untuk memudahkan koordinasi antar departemen di lingkungan sekolah.</p>
                </div>
            </div>

            <div class="bg-surface-container-lowest p-6 rounded-xl shadow-sm border border-outline-variant">
                <h4 class="font-headline-sm text-headline-sm text-primary mb-6">Informasi Pribadi</h4>
                <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @csrf
                    @method('patch')
                    <input type="file" name="profile_photo" id="profile_photo" accept="image/*" class="hidden" @change="const f = $event.target.files[0]; if (f) { const r = new FileReader(); r.onload = e => window.dispatchEvent(new CustomEvent('avatar-preview', { detail: e.target.result })); r.readAsDataURL(f); }">
                    <div class="flex flex-col gap-2">
                        <label class="font-label-md text-label-md text-on-surface" for="name">Nama Lengkap</label>
                        <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" class="px-4 py-3 bg-surface rounded-lg border border-outline-variant focus:ring-2 focus:ring-primary-fixed focus:border-primary text-body-md transition-all @error('name') border-error @enderror">
                        @error('name')
                            <p class="text-body-sm text-error mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="font-label-md text-label-md text-on-surface" for="email">Email Institusi</label>
                        <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username" class="px-4 py-3 bg-surface rounded-lg border border-outline-variant focus:ring-2 focus:ring-primary-fixed focus:border-primary text-body-md transition-all @error('email') border-error @enderror">
                        @error('email')
                            <p class="text-body-sm text-error mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="md:col-span-2 pt-4 flex items-center justify-between flex-wrap gap-4">
                        <label class="flex items-center gap-2 text-sm text-primary cursor-pointer hover:underline" for="profile_photo">
                            <span class="material-symbols-outlined text-lg">photo_camera</span>
                            Ganti Foto Profil
                        </label>
                        <button type="submit" class="px-8 py-3 font-label-md text-label-md bg-secondary-container text-on-secondary-container rounded-lg shadow-md hover:shadow-lg active:scale-95 transition-all">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Tab: Keamanan --}}
        <div x-show="activeTab === 'keamanan'" x-cloak class="space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 bg-surface-container-lowest p-6 rounded-xl shadow-sm border border-outline-variant">
                    <h4 class="font-headline-sm text-headline-sm text-primary mb-6">Ubah Kata Sandi</h4>
                    <form method="post" action="{{ route('password.update') }}" class="space-y-4">
                        @csrf
                        @method('put')
                        <div class="flex flex-col gap-2">
                            <label class="font-label-md text-label-md text-on-surface" for="update_password_current_password">Kata Sandi Saat Ini</label>
                            <input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password" placeholder="Masukkan kata sandi saat ini" class="px-4 py-3 bg-surface rounded-lg border border-outline-variant focus:ring-2 focus:ring-primary-fixed focus:border-primary text-body-md transition-all @error('current_password', 'updatePassword') border-error @enderror">
                            @error('current_password', 'updatePassword')
                                <p class="text-body-sm text-error mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex flex-col gap-2">
                                <label class="font-label-md text-label-md text-on-surface" for="update_password_password">Kata Sandi Baru</label>
                                <input id="update_password_password" name="password" type="password" autocomplete="new-password" placeholder="Minimal 8 karakter" class="px-4 py-3 bg-surface rounded-lg border border-outline-variant focus:ring-2 focus:ring-primary-fixed focus:border-primary text-body-md transition-all @error('password', 'updatePassword') border-error @enderror">
                                @error('password', 'updatePassword')
                                    <p class="text-body-sm text-error mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="flex flex-col gap-2">
                                <label class="font-label-md text-label-md text-on-surface" for="update_password_password_confirmation">Konfirmasi Kata Sandi Baru</label>
                                <input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" placeholder="Ulangi kata sandi baru" class="px-4 py-3 bg-surface rounded-lg border border-outline-variant focus:ring-2 focus:ring-primary-fixed focus:border-primary text-body-md transition-all">
                            </div>
                        </div>
                        <div class="pt-4 flex justify-end">
                            <button type="submit" class="px-6 py-3 font-label-md text-label-md bg-secondary-container text-on-secondary-container rounded-lg shadow-md hover:shadow-lg hover:brightness-110 active:scale-95 transition-all">
                                Update Kata Sandi
                            </button>
                        </div>
                    </form>
                </div>

                <div class="bg-surface-container-lowest p-6 rounded-xl shadow-sm border border-outline-variant flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-3 text-error mb-4">
                            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">delete_forever</span>
                            <h4 class="font-headline-sm text-headline-sm">Hapus Akun</h4>
                        </div>
                        <p class="font-body-sm text-body-sm text-on-surface-variant mb-6">Setelah akun dihapus, semua data akan terhapus permanen. Tindakan ini tidak dapat dibatalkan.</p>
                    </div>
                    <form method="post" action="{{ route('profile.destroy') }}" id="delete-account-form">
                        @csrf
                        @method('delete')
                        <input type="hidden" name="password" id="delete-account-password">
                    </form>
                    <button type="button" onclick="confirmDeleteAccount()" class="w-full px-4 py-3 font-label-md text-label-md bg-error text-on-error rounded-lg shadow-md hover:shadow-lg active:scale-95 transition-all">
                        Hapus Akun
                    </button>
                </div>
            </div>

            {{-- Biometric Settings --}}
            @include('profile.partials.biometric-settings')
        </div>

    </div>

    <script>
    function confirmDeleteAccount() {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: 'Setelah akun dihapus, semua data akan terhapus permanen. Masukkan kata sandi untuk konfirmasi.',
            icon: 'warning',
            input: 'password',
            inputPlaceholder: 'Kata Sandi',
            inputAttributes: { autocomplete: 'current-password' },
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus akun!',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#dc2626',
            reverseButtons: true,
            preConfirm: (password) => {
                if (!password) {
                    Swal.showValidationMessage('Kata sandi wajib diisi');
                }
                return password;
            }
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                document.getElementById('delete-account-password').value = result.value;
                document.getElementById('delete-account-form').submit();
            }
        });
    }
    </script>
</x-layouts.admin>