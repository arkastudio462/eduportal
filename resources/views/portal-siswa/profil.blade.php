<x-layouts.portal-siswa title="Profil Saya - Portal Siswa">
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-primary">Profil Saya</h2>
        <p class="text-on-surface-variant font-body-md">Kelola informasi profil Anda</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        {{-- Profile Information --}}
        <div class="bg-white rounded-xl border border-outline-variant card-shadow p-6">
            <h3 class="font-headline-sm text-headline-sm text-on-surface mb-1">Informasi Profil</h3>
            <p class="text-body-sm text-on-surface-variant mb-6">Perbarui informasi nama dan email Anda</p>
            <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
                @csrf
                @method('patch')
                <div>
                    <x-input-label for="name" :value="__('Nama Lengkap')" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
                    <x-input-error class="mt-2" :messages="$errors->get('email')" />
                </div>
                <div class="flex items-center gap-4">
                    <button type="submit" class="bg-primary text-on-primary px-6 py-2 rounded-lg font-label-md hover:bg-primary/90 transition-colors">Simpan Perubahan</button>
                </div>
            </form>
        </div>

        {{-- Update Password --}}
        <div class="bg-white rounded-xl border border-outline-variant card-shadow p-6">
            <h3 class="font-headline-sm text-headline-sm text-on-surface mb-1">Perbarui Kata Sandi</h3>
            <p class="text-body-sm text-on-surface-variant mb-6">Gunakan kata sandi yang panjang dan acak untuk keamanan akun</p>
            <form method="post" action="{{ route('password.update') }}" class="space-y-5">
                @csrf
                @method('put')
                <div>
                    <x-input-label for="update_password_current_password" :value="__('Kata Sandi Saat Ini')" />
                    <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
                    <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="update_password_password" :value="__('Kata Sandi Baru')" />
                    <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                    <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="update_password_password_confirmation" :value="__('Konfirmasi Kata Sandi Baru')" />
                    <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                    <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                </div>
                <div class="flex items-center gap-4">
                    <button type="submit" class="bg-primary text-on-primary px-6 py-2 rounded-lg font-label-md hover:bg-primary/90 transition-colors">Simpan Kata Sandi</button>
                </div>
            </form>
        </div>

        {{-- Biometric Settings --}}
        @include('profile.partials.biometric-settings')
    </div>

    {{-- Sidebar --}}
    <div class="space-y-6">
        <div class="bg-white rounded-xl border border-outline-variant card-shadow p-6 text-center">
            @if($user->profile_photo_path)
            <img src="{{ Storage::url($user->profile_photo_path) }}" alt="{{ $user->name }}" class="w-24 h-24 rounded-full object-cover mx-auto mb-4 border-4 border-primary-container">
            @else
            <div class="w-24 h-24 rounded-full bg-primary-container flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-4xl text-primary">school</span>
            </div>
            @endif
            <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mb-4">
                @csrf
                @method('patch')
                <input type="hidden" name="name" value="{{ $user->name }}">
                <input type="hidden" name="email" value="{{ $user->email }}">
                <label class="inline-flex items-center gap-1.5 px-4 py-2 bg-surface-container-low rounded-lg text-sm font-label-md text-primary cursor-pointer hover:bg-surface-container transition-all">
                    <span class="material-symbols-outlined text-lg">photo_camera</span>
                    <span>Ganti Foto</span>
                    <input type="file" name="profile_photo" accept="image/jpeg,image/png,image/webp" class="hidden" onchange="this.form.submit()">
                </label>
            </form>
            <h4 class="font-label-md text-label-md text-on-surface">{{ $user->name }}</h4>
            <p class="text-body-sm text-on-surface-variant">{{ $user->email }}</p>
            <div class="mt-4 inline-block px-3 py-1 bg-secondary-container text-secondary rounded-full text-body-sm font-medium">Siswa</div>
            @if($siswa)
            <div class="mt-4 flex justify-center gap-2">
                <a href="{{ route('portal-siswa.kartu') }}" target="_blank" class="inline-flex items-center gap-1 px-4 py-2 border border-outline-variant rounded-lg text-sm font-label-md text-on-surface-variant hover:bg-surface-container transition-all">
                    <span class="material-symbols-outlined text-lg">badge</span>
                    Cetak Kartu
                </a>
            </div>
            @endif
        </div>

        @if ($siswa)
        <div class="bg-white rounded-xl border border-outline-variant card-shadow p-6">
            <h4 class="font-label-md text-label-md text-on-surface mb-4">Data Siswa</h4>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-body-sm text-on-surface-variant">NISN</span>
                    <span class="text-body-sm font-medium text-on-surface">{{ $siswa->nisn ?: '-' }}</span>
                </div>
                <hr class="border-outline-variant/50">
                <div class="flex items-center justify-between">
                    <span class="text-body-sm text-on-surface-variant">NIS</span>
                    <span class="text-body-sm font-medium text-on-surface">{{ $siswa->nis ?: '-' }}</span>
                </div>
                <hr class="border-outline-variant/50">
                <div class="flex items-center justify-between">
                    <span class="text-body-sm text-on-surface-variant">Kelas</span>
                    <span class="text-body-sm font-medium text-on-surface">{{ $siswa->kelas?->nama ?: '-' }}</span>
                </div>
                <hr class="border-outline-variant/50">
                <div class="flex items-center justify-between">
                    <span class="text-body-sm text-on-surface-variant">Tingkat</span>
                    <span class="text-body-sm font-medium text-on-surface">{{ $siswa->kelas?->tingkat ?: '-' }}</span>
                </div>
                <hr class="border-outline-variant/50">
                <div class="flex items-center justify-between">
                    <span class="text-body-sm text-on-surface-variant">Jurusan</span>
                    <span class="text-body-sm font-medium text-on-surface">{{ $siswa->kelas?->jurusanRel?->nama ?: '-' }}</span>
                </div>
                <hr class="border-outline-variant/50">
                <div class="flex items-center justify-between">
                    <span class="text-body-sm text-on-surface-variant">Status</span>
                    <span class="text-body-sm font-medium {{ $siswa->status === 'aktif' ? 'text-green-600' : 'text-orange-600' }}">
                        {{ ucfirst($siswa->status ?: '-') }}
                    </span>
                </div>
                <hr class="border-outline-variant/50">
                <div class="flex items-center justify-between">
                    <span class="text-body-sm text-on-surface-variant">Bergabung</span>
                    <span class="text-body-sm font-medium text-on-surface">{{ $siswa->created_at ? $siswa->created_at->format('d M Y') : '-' }}</span>
                </div>
            </div>
        </div>
        @endif

        @if ($siswa && $siswa->kelas && $siswa->kelas->waliKelas)
        <div class="bg-white rounded-xl border border-outline-variant card-shadow p-6">
            <h4 class="font-label-md text-label-md text-on-surface mb-4">Wali Kelas</h4>
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-secondary-container flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-secondary">supervisor_account</span>
                </div>
                <div>
                    <p class="font-label-md text-label-md text-on-surface">{{ $siswa->kelas->waliKelas->user->name }}</p>
                    <p class="text-body-sm text-on-surface-variant">{{ $siswa->kelas->waliKelas->mata_pelajaran }}</p>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
</x-layouts.portal-siswa>
