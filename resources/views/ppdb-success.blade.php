<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pendaftaran Berhasil | EduPortal</title>
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
        <div class="max-w-lg mx-auto text-center">
            <div class="w-20 h-20 bg-success/10 rounded-full flex items-center justify-center mx-auto mb-6">
                <span class="material-symbols-outlined text-5xl text-success" style="font-variation-settings:'FILL'1">check_circle</span>
            </div>
            <h1 class="font-headline-lg text-headline-lg text-primary mb-2">Pendaftaran Berhasil!</h1>
            <p class="text-on-surface-variant mb-6">Terima kasih telah mendaftar di {{ setting('nama_sekolah', 'SMA Nusantara') }}.</p>

            <div class="bg-white rounded-2xl border border-outline-variant card-shadow p-8 mb-6">
                <p class="font-label-sm text-label-sm text-on-surface-variant mb-2">Nomor Pendaftaran Anda</p>
                <p class="text-2xl font-bold font-mono tracking-wider text-primary">{{ $pendaftaran->no_pendaftaran }}</p>
                <div class="mt-4 p-3 bg-surface-container-low rounded-xl text-sm text-on-surface-variant">
                    <p>Simpan nomor pendaftaran untuk mengecek status pendaftaran.</p>
                </div>
            </div>

            <div class="space-y-3">
                <a href="{{ route('ppdb.cek-status', ['no_pendaftaran' => $pendaftaran->no_pendaftaran]) }}"
                    class="block w-full py-3.5 bg-primary text-on-primary rounded-xl font-label-md hover:bg-primary-container transition-all">
                    Cek Status Pendaftaran
                </a>
                <a href="{{ route('ppdb.form') }}"
                    class="block w-full py-3.5 border border-outline-variant rounded-xl font-label-md text-on-surface-variant hover:bg-surface-container transition-all">
                    Kembali
                </a>
            </div>
        </div>
    </main>
    <x-guest-footer />
</body>
</html>
