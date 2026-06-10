<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Form Izin Siswa | EduPortal</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@400;600;700&family=Source+Sans+3:wght@400;600&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
        .card-shadow { box-shadow: 0 4px 6px -1px rgba(13, 27, 75, 0.04), 0 2px 4px -1px rgba(13, 27, 75, 0.02); }
    </style>
</head>
<body class="bg-background text-on-surface font-body-md min-h-screen flex flex-col">
    <x-guest-navbar />

    <main class="flex-1 px-margin-mobile md:px-margin-desktop max-w-max-width mx-auto w-full py-8 md:py-12">
        @if (session('status'))
        <div class="mb-6 p-4 bg-green-100 border border-green-300 text-green-700 rounded-xl flex items-center gap-3">
            <span class="material-symbols-outlined text-green-600">check_circle</span>
            <span class="font-body-md font-semibold">{{ session('status') }}</span>
        </div>
        @endif

        <div class="max-w-2xl mx-auto">
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-primary-fixed text-primary rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-3xl" style="font-variation-settings:'FILL'1">assignment</span>
                </div>
                <h1 class="font-headline-lg text-headline-lg text-primary mb-2">Form Izin Siswa</h1>
                <p class="text-on-surface-variant font-body-md">Isi formulir di bawah untuk mengajukan izin ketidakhadiran</p>
            </div>

            <div class="bg-white rounded-2xl border border-outline-variant card-shadow p-6 md:p-8">
                <form method="POST" action="{{ route('izin-siswa.submit') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div class="space-y-2">
                        <label for="nisn" class="font-label-md text-label-md text-on-surface-variant">NISN <span class="text-error">*</span></label>
                        <input id="nisn" type="text" name="nisn" value="{{ old('nisn') }}" required
                            class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all"
                            placeholder="Masukkan NISN siswa">
                        @error('nisn')
                            <p class="text-error font-body-sm text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label for="tanggal_mulai" class="font-label-md text-label-md text-on-surface-variant">Tanggal Mulai <span class="text-error">*</span></label>
                            <input id="tanggal_mulai" type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required
                                class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all">
                            @error('tanggal_mulai')
                                <p class="text-error font-body-sm text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label for="tanggal_selesai" class="font-label-md text-label-md text-on-surface-variant">Tanggal Selesai <span class="text-error">*</span></label>
                            <input id="tanggal_selesai" type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" required
                                class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all">
                            @error('tanggal_selesai')
                                <p class="text-error font-body-sm text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="alasan" class="font-label-md text-label-md text-on-surface-variant">Alasan Izin <span class="text-error">*</span></label>
                        <select id="alasan" name="alasan" required
                            class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all">
                            <option value="">-- Pilih Alasan --</option>
                            <option value="Sakit" {{ old('alasan') == 'Sakit' ? 'selected' : '' }}>Sakit</option>
                            <option value="Keperluan Keluarga" {{ old('alasan') == 'Keperluan Keluarga' ? 'selected' : '' }}>Keperluan Keluarga</option>
                            <option value="Acara" {{ old('alasan') == 'Acara' ? 'selected' : '' }}>Acara</option>
                            <option value="Lainnya" {{ old('alasan') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('alasan')
                            <p class="text-error font-body-sm text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="keterangan" class="font-label-md text-label-md text-on-surface-variant">Keterangan <span class="text-on-surface-variant font-body-sm">(opsional)</span></label>
                        <textarea id="keterangan" name="keterangan" rows="4"
                            class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all resize-none"
                            placeholder="Jelaskan alasan izin Anda secara detail...">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <p class="text-error font-body-sm text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="file" class="font-label-md text-label-md text-on-surface-variant">Dokumen Pendukung <span class="text-on-surface-variant font-body-sm">(opsional, maks. 2MB)</span></label>
                        <div class="relative">
                            <input id="file" type="file" name="file" accept=".pdf,.jpg,.jpeg,.png"
                                class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-primary file:text-on-primary file:font-label-md file:cursor-pointer hover:file:bg-primary-container">
                        </div>
                        <p class="font-body-sm text-outline flex items-center gap-1 mt-1">
                            <span class="material-symbols-outlined text-sm">info</span>
                            Upload surat dokter, surat keterangan, atau dokumen lainnya (PDF, JPG, PNG)
                        </p>
                        @error('file')
                            <p class="text-error font-body-sm text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex flex-col md:flex-row gap-3 pt-4">
                        <button type="submit"
                            class="flex-1 py-3.5 bg-primary text-on-primary rounded-xl font-label-md text-label-md hover:bg-primary-container hover:text-on-primary-container transition-all active:scale-[0.98] flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined">send</span>
                            Ajukan Izin
                        </button>
                        <a href="{{ url('/') }}"
                            class="py-3.5 border border-outline-variant rounded-xl font-label-md text-label-md text-on-surface-variant hover:bg-surface-container transition-all active:scale-[0.98] text-center">
                            Kembali ke Beranda
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <x-guest-footer />
</body>
</html>
