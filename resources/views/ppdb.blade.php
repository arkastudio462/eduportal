<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PPDB Online | {{ setting('nama_sekolah', 'SMA Nusantara') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@400;600;700&family=Source+Sans+3:wght@400;600&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .card-shadow { box-shadow: 0 4px 6px -1px rgba(13, 27, 75, 0.04), 0 2px 4px -1px rgba(13, 27, 75, 0.02); }
        .form-input:focus { outline: none; border-color: #0d1b4b; box-shadow: 0 0 0 2px rgba(13, 27, 75, 0.1); }
    </style>
</head>
<body class="bg-surface text-on-surface font-body-md min-h-screen flex flex-col">
    <x-guest-navbar />

    {{-- Hero Section --}}
    <section class="relative h-[360px] flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img alt="SMA Nusantara Campus" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida/AP1WRLt8jNQHYEVUfpn6NCtFS16u6fFUgMswOhvREKTTARPAYjJ_9jedp9OWKNNpz26s7Yt5N6Xv14r3G-UtiOzvgk8pRIQbXVtecRS6kmSaNLQdOgC9yFp2jhMCOPO4jvzMlm3firAxbjLeq2rik_w_rkNfVR2gJnTNlU27PevulKYHDhge5pZMN0UZlyd4GrWrGoPu6gUOF3flQffcBnzC8Aex-aWlBS1YOmsgouONNjx8oTacDyVk0vlIgjQ8">
            <div class="absolute inset-0 bg-primary/60 backdrop-blur-[2px]"></div>
        </div>
        <div class="relative z-10 text-center px-margin-mobile max-w-3xl">
            <h1 class="font-headline-xl text-headline-xl text-white mb-4">Pendaftaran Peserta Didik Baru</h1>
            <p class="font-body-lg text-body-lg text-white opacity-90">{{ setting('nama_sekolah', 'SMA Nusantara') }} | Tahun Ajaran {{ date('Y') }}/{{ date('Y')+1 }}</p>
        </div>
    </section>

    <main class="flex-1 max-w-max-width mx-auto w-full px-margin-mobile md:px-margin-desktop py-12">
        @if (session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-300 text-green-700 rounded-xl flex items-center gap-3">
            <span class="material-symbols-outlined text-green-600">check_circle</span>
            <span class="font-body-md font-semibold">{{ session('success') }}</span>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-gutter">
            <div class="lg:col-span-8 space-y-gutter">
                @if (session('success'))
                <div class="bg-green-50 border border-green-200 rounded-2xl p-8 text-center">
                    <span class="material-symbols-outlined text-6xl text-green-500" style="font-variation-settings:'FILL'1">check_circle</span>
                    <h2 class="font-headline-md text-headline-md text-green-700 mt-4">Pendaftaran Berhasil!</h2>
                    <p class="text-green-600 mt-2">{{ session('success') }}</p>
                </div>
                @else

                <div x-data="ppbdStep()">
                    {{-- Stepper --}}
                    <div class="bg-white p-6 rounded-xl border border-outline-variant card-shadow overflow-x-auto mb-6">
                        <div class="flex items-center justify-between min-w-[500px]">
                            <div class="flex flex-col items-center gap-2 flex-1">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold transition-all duration-300"
                                    :class="currentStep === 1 ? 'bg-secondary-container text-primary scale-110' : currentStep > 1 ? 'bg-secondary text-on-secondary' : 'bg-surface-container-high text-on-surface-variant'">
                                    <span x-show="currentStep > 1" class="material-symbols-outlined" style="font-variation-settings:'FILL'1;font-size:20px">check</span>
                                    <span x-show="currentStep <= 1">1</span>
                                </div>
                                <span class="font-label-md transition-colors duration-300 text-center"
                                    :class="currentStep === 1 ? 'text-primary font-bold' : currentStep > 1 ? 'text-secondary' : 'text-on-surface-variant'">Data Diri</span>
                            </div>
                            <div class="h-px flex-1 mb-6" :class="currentStep > 1 ? 'bg-secondary' : 'bg-outline-variant'"></div>
                            <div class="flex flex-col items-center gap-2 flex-1">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold transition-all duration-300"
                                    :class="currentStep === 2 ? 'bg-secondary-container text-primary scale-110' : currentStep > 2 ? 'bg-secondary text-on-secondary' : 'bg-surface-container-high text-on-surface-variant'">
                                    <span x-show="currentStep > 2" class="material-symbols-outlined" style="font-variation-settings:'FILL'1;font-size:20px">check</span>
                                    <span x-show="currentStep <= 2">2</span>
                                </div>
                                <span class="font-label-md transition-colors duration-300 text-center"
                                    :class="currentStep === 2 ? 'text-primary font-bold' : currentStep > 2 ? 'text-secondary' : 'text-on-surface-variant'">Pendidikan &amp; Orang Tua</span>
                            </div>
                            <div class="h-px flex-1 mb-6" :class="currentStep > 2 ? 'bg-secondary' : 'bg-outline-variant'"></div>
                            <div class="flex flex-col items-center gap-2 flex-1">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold transition-all duration-300"
                                    :class="currentStep === 3 ? 'bg-secondary-container text-primary scale-110' : currentStep > 3 ? 'bg-secondary text-on-secondary' : 'bg-surface-container-high text-on-surface-variant'">
                                    <span x-show="currentStep > 3" class="material-symbols-outlined" style="font-variation-settings:'FILL'1;font-size:20px">check</span>
                                    <span x-show="currentStep <= 3">3</span>
                                </div>
                                <span class="font-label-md transition-colors duration-300 text-center"
                                    :class="currentStep === 3 ? 'text-primary font-bold' : currentStep > 3 ? 'text-secondary' : 'text-on-surface-variant'">Dokumen</span>
                            </div>
                            <div class="h-px flex-1 mb-6" :class="currentStep > 3 ? 'bg-secondary' : 'bg-outline-variant'"></div>
                            <div class="flex flex-col items-center gap-2 flex-1">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold transition-all duration-300"
                                    :class="currentStep === 4 ? 'bg-secondary-container text-primary scale-110' : currentStep > 4 ? 'bg-secondary text-on-secondary' : 'bg-surface-container-high text-on-surface-variant'">
                                    <span x-show="currentStep > 4" class="material-symbols-outlined" style="font-variation-settings:'FILL'1;font-size:20px">check</span>
                                    <span x-show="currentStep <= 4">4</span>
                                </div>
                                <span class="font-label-md transition-colors duration-300 text-center"
                                    :class="currentStep === 4 ? 'text-primary font-bold' : currentStep > 4 ? 'text-secondary' : 'text-on-surface-variant'">Konfirmasi</span>
                            </div>
                        </div>
                    </div>

                    {{-- Form --}}
                    <div class="bg-white p-6 md:p-8 rounded-xl border border-outline-variant card-shadow">
                        <form method="POST" action="{{ route('ppdb.store') }}" enctype="multipart/form-data"
                            x-ref="form">
                            @csrf

                            {{-- Step 1: Data Diri --}}
                            <div x-show="currentStep === 1" x-cloak x-transition:enter.duration.300ms>
                                <h2 class="font-headline-md text-headline-md text-primary mb-6 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-secondary">person</span>
                                    Data Diri Calon Siswa
                                </h2>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div class="md:col-span-2">
                                        <label class="block font-label-md text-on-surface-variant mb-1.5">Nama Lengkap <span class="text-error">*</span></label>
                                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required
                                            class="form-input w-full px-4 py-3 rounded-xl border border-outline-variant bg-white font-body-md"
                                            placeholder="Nama lengkap sesuai ijazah">
                                        @error('nama_lengkap') <p class="text-error font-body-sm text-sm mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label class="block font-label-md text-on-surface-variant mb-1.5">Tempat Lahir</label>
                                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}"
                                            class="form-input w-full px-4 py-3 rounded-xl border border-outline-variant bg-white font-body-md"
                                            placeholder="Kota lahir">
                                    </div>
                                    <div>
                                        <label class="block font-label-md text-on-surface-variant mb-1.5">Tanggal Lahir</label>
                                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
                                            class="form-input w-full px-4 py-3 rounded-xl border border-outline-variant bg-white font-body-md">
                                    </div>
                                    <div>
                                        <label class="block font-label-md text-on-surface-variant mb-1.5">Jenis Kelamin</label>
                                        <select name="jenis_kelamin"
                                            class="form-input w-full px-4 py-3 rounded-xl border border-outline-variant bg-white font-body-md">
                                            <option value="">Pilih</option>
                                            <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block font-label-md text-on-surface-variant mb-1.5">NISN <span class="text-error">*</span></label>
                                        <input type="text" name="nisn" value="{{ old('nisn') }}" required
                                            class="form-input w-full px-4 py-3 rounded-xl border border-outline-variant bg-white font-body-md"
                                            placeholder="10 digit nomor NISN">
                                        @error('nisn') <p class="text-error font-body-sm text-sm mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label class="block font-label-md text-on-surface-variant mb-1.5">Agama</label>
                                        <select name="agama"
                                            class="form-input w-full px-4 py-3 rounded-xl border border-outline-variant bg-white font-body-md">
                                            <option value="">Pilih</option>
                                            @foreach(['Islam','Kristen','Katolik','Hindu','Buddha','Khonghucu'] as $a)
                                            <option value="{{ $a }}" {{ old('agama') == $a ? 'selected' : '' }}>{{ $a }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block font-label-md text-on-surface-variant mb-1.5">No. HP</label>
                                        <input type="text" name="no_hp" value="{{ old('no_hp') }}"
                                            class="form-input w-full px-4 py-3 rounded-xl border border-outline-variant bg-white font-body-md"
                                            placeholder="08xxxxxxxxxx">
                                    </div>
                                    <div>
                                        <label class="block font-label-md text-on-surface-variant mb-1.5">Email</label>
                                        <input type="email" name="email" value="{{ old('email') }}"
                                            class="form-input w-full px-4 py-3 rounded-xl border border-outline-variant bg-white font-body-md"
                                            placeholder="email@example.com">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block font-label-md text-on-surface-variant mb-1.5">Alamat</label>
                                        <textarea name="alamat" rows="3"
                                            class="form-input w-full px-4 py-3 rounded-xl border border-outline-variant bg-white font-body-md resize-none"
                                            placeholder="Alamat lengkap">{{ old('alamat') }}</textarea>
                                    </div>
                                </div>
                                <div class="flex justify-end pt-6 border-t border-outline-variant mt-8">
                                    <button type="button" @click="goNext(1)"
                                        class="bg-secondary-container text-on-secondary-container px-8 py-3 rounded-xl font-headline-sm font-bold hover:brightness-105 transition-all active:scale-[0.98] flex items-center gap-2 shadow-sm">
                                        <span class="material-symbols-outlined">arrow_forward</span>
                                        Lanjut ke Data Pendidikan
                                    </button>
                                </div>
                            </div>

                            {{-- Step 2: Pendidikan & Orang Tua --}}
                            <div x-show="currentStep === 2" x-cloak x-transition:enter.duration.300ms>
                                <h2 class="font-headline-md text-headline-md text-primary mb-6 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-secondary">school</span>
                                    Data Pendidikan &amp; Pilihan
                                </h2>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                                    <div class="md:col-span-2">
                                        <label class="block font-label-md text-on-surface-variant mb-1.5">Asal Sekolah (SMP/MTs)</label>
                                        <input type="text" name="asal_sekolah" value="{{ old('asal_sekolah') }}"
                                            class="form-input w-full px-4 py-3 rounded-xl border border-outline-variant bg-white font-body-md"
                                            placeholder="Nama sekolah asal">
                                    </div>
                                    <div>
                                        <label class="block font-label-md text-on-surface-variant mb-1.5">Jurusan Dipilih</label>
                                        <select name="jurusan_daftar"
                                            class="form-input w-full px-4 py-3 rounded-xl border border-outline-variant bg-white font-body-md">
                                            <option value="">Pilih</option>
                                            <option value="IPA" {{ old('jurusan_daftar') == 'IPA' ? 'selected' : '' }}>IPA</option>
                                            <option value="IPS" {{ old('jurusan_daftar') == 'IPS' ? 'selected' : '' }}>IPS</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block font-label-md text-on-surface-variant mb-1.5">Nilai Rata-rata</label>
                                        <input type="text" name="nilai_rata_rata" value="{{ old('nilai_rata_rata') }}"
                                            class="form-input w-full px-4 py-3 rounded-xl border border-outline-variant bg-white font-body-md"
                                            placeholder="Contoh: 85.5">
                                    </div>
                                </div>

                                <h2 class="font-headline-md text-headline-md text-primary mb-6 mt-8 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-secondary">family_restroom</span>
                                    Data Orang Tua / Wali
                                </h2>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div class="p-5 bg-surface-container-low rounded-xl space-y-4">
                                        <h3 class="font-label-md text-label-md text-primary flex items-center gap-2">
                                            <span class="material-symbols-outlined text-secondary">face</span>
                                            Ayah
                                        </h3>
                                        <div class="space-y-3">
                                            <div>
                                                <label class="block font-body-sm text-on-surface-variant mb-1">Nama Ayah</label>
                                                <input type="text" name="nama_ayah" value="{{ old('nama_ayah') }}"
                                                    class="form-input w-full px-4 py-3 rounded-xl border border-outline-variant bg-white font-body-md">
                                            </div>
                                            <div>
                                                <label class="block font-body-sm text-on-surface-variant mb-1">No. HP Ayah</label>
                                                <input type="text" name="no_hp_ayah" value="{{ old('no_hp_ayah') }}"
                                                    class="form-input w-full px-4 py-3 rounded-xl border border-outline-variant bg-white font-body-md">
                                            </div>
                                            <div>
                                                <label class="block font-body-sm text-on-surface-variant mb-1">Pekerjaan Ayah</label>
                                                <input type="text" name="pekerjaan_ayah" value="{{ old('pekerjaan_ayah') }}"
                                                    class="form-input w-full px-4 py-3 rounded-xl border border-outline-variant bg-white font-body-md">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-5 bg-surface-container-low rounded-xl space-y-4">
                                        <h3 class="font-label-md text-label-md text-primary flex items-center gap-2">
                                            <span class="material-symbols-outlined text-secondary">face</span>
                                            Ibu
                                        </h3>
                                        <div class="space-y-3">
                                            <div>
                                                <label class="block font-body-sm text-on-surface-variant mb-1">Nama Ibu</label>
                                                <input type="text" name="nama_ibu" value="{{ old('nama_ibu') }}"
                                                    class="form-input w-full px-4 py-3 rounded-xl border border-outline-variant bg-white font-body-md">
                                            </div>
                                            <div>
                                                <label class="block font-body-sm text-on-surface-variant mb-1">No. HP Ibu</label>
                                                <input type="text" name="no_hp_ibu" value="{{ old('no_hp_ibu') }}"
                                                    class="form-input w-full px-4 py-3 rounded-xl border border-outline-variant bg-white font-body-md">
                                            </div>
                                            <div>
                                                <label class="block font-body-sm text-on-surface-variant mb-1">Pekerjaan Ibu</label>
                                                <input type="text" name="pekerjaan_ibu" value="{{ old('pekerjaan_ibu') }}"
                                                    class="form-input w-full px-4 py-3 rounded-xl border border-outline-variant bg-white font-body-md">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex justify-between pt-6 border-t border-outline-variant mt-8">
                                    <button type="button" @click="currentStep = 1"
                                        class="py-3 px-6 border border-outline-variant rounded-xl font-label-md text-on-surface-variant hover:bg-surface-container transition-all active:scale-[0.98] flex items-center gap-2">
                                        <span class="material-symbols-outlined">arrow_back</span>
                                        Kembali
                                    </button>
                                    <button type="button" @click="goNext(2)"
                                        class="bg-secondary-container text-on-secondary-container px-8 py-3 rounded-xl font-headline-sm font-bold hover:brightness-105 transition-all active:scale-[0.98] flex items-center gap-2 shadow-sm">
                                        <span class="material-symbols-outlined">arrow_forward</span>
                                        Lanjut ke Dokumen
                                    </button>
                                </div>
                            </div>

                            {{-- Step 3: Dokumen --}}
                            <div x-show="currentStep === 3" x-cloak x-transition:enter.duration.300ms>
                                <h2 class="font-headline-md text-headline-md text-primary mb-6 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-secondary">upload_file</span>
                                    Upload Dokumen
                                </h2>
                                <p class="text-on-surface-variant font-body-sm mb-4">Format: PDF, JPG, PNG. Maks. 2MB per file.</p>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block font-label-md text-on-surface-variant mb-1.5">Ijazah <span class="text-error">*</span></label>
                                        <input type="file" name="berkas[ijazah]" accept=".pdf,.jpg,.jpeg,.png"
                                            class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-primary file:text-on-primary file:font-label-md file:cursor-pointer hover:file:bg-primary-container transition-all">
                                        @error('berkas.ijazah') <p class="text-error font-body-sm text-sm mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label class="block font-label-md text-on-surface-variant mb-1.5">Kartu Keluarga (KK) <span class="text-error">*</span></label>
                                        <input type="file" name="berkas[kk]" accept=".pdf,.jpg,.jpeg,.png"
                                            class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-primary file:text-on-primary file:font-label-md file:cursor-pointer hover:file:bg-primary-container transition-all">
                                        @error('berkas.kk') <p class="text-error font-body-sm text-sm mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label class="block font-label-md text-on-surface-variant mb-1.5">Akta Kelahiran</label>
                                        <input type="file" name="berkas[akta]" accept=".pdf,.jpg,.jpeg,.png"
                                            class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-primary file:text-on-primary file:font-label-md file:cursor-pointer hover:file:bg-primary-container transition-all">
                                        @error('berkas.akta') <p class="text-error font-body-sm text-sm mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label class="block font-label-md text-on-surface-variant mb-1.5">Pas Foto</label>
                                        <input type="file" name="berkas[foto]" accept=".jpg,.jpeg,.png"
                                            class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-primary file:text-on-primary file:font-label-md file:cursor-pointer hover:file:bg-primary-container transition-all">
                                        @error('berkas.foto') <p class="text-error font-body-sm text-sm mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label class="block font-label-md text-on-surface-variant mb-1.5">SKHUN</label>
                                        <input type="file" name="berkas[skhun]" accept=".pdf,.jpg,.jpeg,.png"
                                            class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-primary file:text-on-primary file:font-label-md file:cursor-pointer hover:file:bg-primary-container transition-all">
                                        @error('berkas.skhun') <p class="text-error font-body-sm text-sm mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label class="block font-label-md text-on-surface-variant mb-1.5">Piagam Prestasi <span class="text-on-surface-variant font-body-sm">(opsional)</span></label>
                                        <input type="file" name="berkas[prestasi]" accept=".pdf,.jpg,.jpeg,.png"
                                            class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-primary file:text-on-primary file:font-label-md file:cursor-pointer hover:file:bg-primary-container transition-all">
                                        @error('berkas.prestasi') <p class="text-error font-body-sm text-sm mt-1">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                                <div class="flex justify-between pt-6 border-t border-outline-variant mt-8">
                                    <button type="button" @click="currentStep = 2"
                                        class="py-3 px-6 border border-outline-variant rounded-xl font-label-md text-on-surface-variant hover:bg-surface-container transition-all active:scale-[0.98] flex items-center gap-2">
                                        <span class="material-symbols-outlined">arrow_back</span>
                                        Kembali
                                    </button>
                                    <button type="button" @click="goNext(3)"
                                        class="bg-secondary-container text-on-secondary-container px-8 py-3 rounded-xl font-headline-sm font-bold hover:brightness-105 transition-all active:scale-[0.98] flex items-center gap-2 shadow-sm">
                                        <span class="material-symbols-outlined">arrow_forward</span>
                                        Lanjut ke Konfirmasi
                                    </button>
                                </div>
                            </div>

                            {{-- Step 4: Konfirmasi --}}
                            <div x-show="currentStep === 4" x-cloak x-transition:enter.duration.300ms>
                                <h2 class="font-headline-md text-headline-md text-primary mb-6 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-secondary">fact_check</span>
                                    Konfirmasi Data
                                </h2>
                                <p class="font-body-md text-on-surface-variant mb-6">Periksa kembali data Anda sebelum mengirimkan pendaftaran.</p>

                                <div class="space-y-4">
                                    <div class="p-5 bg-surface-container-low rounded-xl">
                                        <h3 class="font-label-md text-label-md text-secondary mb-3 flex items-center gap-2">
                                            <span class="material-symbols-outlined">person</span>
                                            Data Diri
                                        </h3>
                                        <dl class="grid grid-cols-2 gap-2 text-body-sm">
                                            <div>
                                                <dt class="text-on-surface-variant">Nama Lengkap</dt>
                                                <dd class="font-semibold" x-text="confirmData.nama_lengkap || '-'"></dd>
                                            </div>
                                            <div>
                                                <dt class="text-on-surface-variant">NISN</dt>
                                                <dd class="font-semibold" x-text="confirmData.nisn || '-'"></dd>
                                            </div>
                                            <div>
                                                <dt class="text-on-surface-variant">Tempat Lahir</dt>
                                                <dd class="font-semibold" x-text="confirmData.tempat_lahir || '-'"></dd>
                                            </div>
                                            <div>
                                                <dt class="text-on-surface-variant">Jenis Kelamin</dt>
                                                <dd class="font-semibold" x-text="confirmData.jenis_kelamin || '-'"></dd>
                                            </div>
                                            <div>
                                                <dt class="text-on-surface-variant">Agama</dt>
                                                <dd class="font-semibold" x-text="confirmData.agama || '-'"></dd>
                                            </div>
                                            <div>
                                                <dt class="text-on-surface-variant">No. HP</dt>
                                                <dd class="font-semibold" x-text="confirmData.no_hp || '-'"></dd>
                                            </div>
                                            <div>
                                                <dt class="text-on-surface-variant">Email</dt>
                                                <dd class="font-semibold" x-text="confirmData.email || '-'"></dd>
                                            </div>
                                        </dl>
                                    </div>
                                    <div class="flex justify-between pt-6 border-t border-outline-variant">
                                        <button type="button" @click="currentStep = 3"
                                            class="py-3 px-6 border border-outline-variant rounded-xl font-label-md text-on-surface-variant hover:bg-surface-container transition-all active:scale-[0.98] flex items-center gap-2">
                                            <span class="material-symbols-outlined">arrow_back</span>
                                            Kembali
                                        </button>
                                        <button type="submit"
                                            class="flex-1 max-w-xs py-3.5 bg-secondary-container text-on-secondary-container rounded-xl font-headline-sm font-bold hover:brightness-105 transition-all active:scale-[0.98] flex items-center justify-center gap-2 shadow-sm ml-auto">
                                            <span class="material-symbols-outlined">send</span>
                                            Daftar Sekarang
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>

                <script>
                    function ppbdStep() {
                        return {
                            currentStep: 1,
                            confirmData: {},
                            goNext(step) {
                                const form = this.$refs.form;
                                if (!form) return;
                                if (step === 1) {
                                    const required = ['nama_lengkap', 'nisn'];
                                    for (const name of required) {
                                        const el = form.querySelector(`[name="${name}"]`);
                                        if (el && !el.value.trim()) {
                                            el.reportValidity();
                                            return;
                                        }
                                    }
                                }
                                if (step === 3) {
                                    const required = ['berkas[ijazah]', 'berkas[kk]'];
                                    for (const name of required) {
                                        const el = form.querySelector(`[name="${name}"]`);
                                        if (el && el.files.length === 0) {
                                            el.reportValidity();
                                            return;
                                        }
                                    }
                                    this.confirmData = {
                                        nama_lengkap: form.querySelector('[name="nama_lengkap"]')?.value || '',
                                        nisn: form.querySelector('[name="nisn"]')?.value || '',
                                        tempat_lahir: form.querySelector('[name="tempat_lahir"]')?.value || '',
                                        jenis_kelamin: form.querySelector('[name="jenis_kelamin"]')?.value || '',
                                        agama: form.querySelector('[name="agama"]')?.value || '',
                                        no_hp: form.querySelector('[name="no_hp"]')?.value || '',
                                        email: form.querySelector('[name="email"]')?.value || '',
                                    };
                                }
                                this.currentStep = step + 1;
                            }
                        };
                    }
                </script>
                @endif
            </div>

            {{-- Right Sidebar --}}
            <aside class="lg:col-span-4 space-y-gutter">
                {{-- Alur Pendaftaran --}}
                <div class="bg-primary-container p-6 rounded-xl border border-outline-variant shadow-sm text-on-primary-container">
                    <h3 class="font-headline-sm text-headline-sm mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined">account_tree</span>
                        Alur Pendaftaran
                    </h3>
                    <div class="space-y-4">
                        <div class="flex gap-4">
                            <div class="flex-none w-8 h-8 rounded-full bg-secondary-container flex items-center justify-center text-on-secondary-container font-bold text-sm">1</div>
                            <div>
                                <p class="font-label-md">Isi Formulir</p>
                                <p class="text-body-sm opacity-80">Lengkapi data diri, pendidikan, dan orang tua.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="flex-none w-8 h-8 rounded-full bg-white/20 flex items-center justify-center font-bold text-sm">2</div>
                            <div>
                                <p class="font-label-md">Upload Berkas</p>
                                <p class="text-body-sm opacity-80">Unggah dokumen persyaratan yang diminta.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="flex-none w-8 h-8 rounded-full bg-white/20 flex items-center justify-center font-bold text-sm">3</div>
                            <div>
                                <p class="font-label-md">Verifikasi</p>
                                <p class="text-body-sm opacity-80">Data dan berkas diperiksa oleh panitia.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="flex-none w-8 h-8 rounded-full bg-white/20 flex items-center justify-center font-bold text-sm">4</div>
                            <div>
                                <p class="font-label-md">Pengumuman</p>
                                <p class="text-body-sm opacity-80">Hasil seleksi dapat dicek secara online.</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Cek Status --}}
                <div class="bg-white p-6 rounded-xl border border-outline-variant card-shadow">
                    <h3 class="font-headline-sm text-headline-sm text-primary mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-secondary">search</span>
                        Cek Status Pendaftaran
                    </h3>
                    <p class="font-body-md text-on-surface-variant mb-4">Sudah mendaftar? Masukkan nomor pendaftaran untuk mengecek status.</p>
                    <form method="GET" action="{{ route('ppdb.cek-status') }}" class="space-y-3">
                        <input type="text" name="no_pendaftaran"
                            class="form-input w-full px-4 py-3 rounded-xl border border-outline-variant bg-white font-body-md"
                            placeholder="Masukkan No. Pendaftaran" required>
                        <button type="submit"
                            class="w-full py-3 bg-secondary-container text-on-secondary-container rounded-xl font-label-md hover:brightness-105 transition-all flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined">search</span>
                            Cek Status
                        </button>
                    </form>
                    @error('no_pendaftaran') <p class="text-error font-body-sm text-sm mt-1">{{ $message }}</p> @enderror
                    @if(session('error')) <p class="text-error font-body-sm text-sm mt-1">{{ session('error') }}</p> @endif
                </div>

                {{-- Bantuan --}}
                <div class="bg-white p-6 rounded-xl border border-outline-variant card-shadow">
                    <h3 class="font-headline-sm text-headline-sm text-primary mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-secondary">help</span>
                        Bantuan
                    </h3>
                    <p class="font-body-md text-on-surface-variant mb-4">Membutuhkan bantuan dalam pengisian formulir?</p>
                    <div class="space-y-2">
                        <a class="flex items-center gap-3 p-3 rounded-lg hover:bg-surface-container transition-colors group" href="#">
                            <span class="material-symbols-outlined text-secondary group-hover:scale-110 transition-transform">description</span>
                            <span class="font-label-md text-primary">Lihat Panduan (FAQ)</span>
                        </a>
                        <a class="flex items-center gap-3 p-3 rounded-lg hover:bg-surface-container transition-colors group" href="tel:{{ setting('kontak_telepon', '0215550123') }}">
                            <span class="material-symbols-outlined text-secondary group-hover:scale-110 transition-transform">call</span>
                            <span class="font-label-md text-primary">{{ setting('kontak_telepon', '(021) 555-0123') }}</span>
                        </a>
                        <a class="flex items-center gap-3 p-3 rounded-lg hover:bg-surface-container transition-colors group" href="mailto:{{ setting('kontak_email', 'info@smanusantara.sch.id') }}">
                            <span class="material-symbols-outlined text-secondary group-hover:scale-110 transition-transform">mail</span>
                            <span class="font-label-md text-primary">Email Panitia</span>
                        </a>
                    </div>
                </div>
            </aside>
        </div>
    </main>

    <x-guest-footer />
</body>
</html>
