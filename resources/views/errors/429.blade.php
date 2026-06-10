<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terlalu Banyak Permintaan | SMA Nusantara</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@400;600;700&family=Source+Sans+3:wght@400;600&family=Material+Symbols-outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body class="bg-surface font-body-md text-on-surface min-h-screen flex items-center justify-center">
    <div class="text-center px-6 max-w-lg">
        <div class="w-20 h-20 bg-tertiary-fixed rounded-full flex items-center justify-center mx-auto mb-6">
            <span class="material-symbols-outlined text-4xl text-tertiary" style="font-variation-settings: 'FILL' 1;">speed</span>
        </div>
        <h1 class="font-headline-xl text-headline-xl text-primary mb-2">429</h1>
        <h2 class="font-headline-sm text-headline-sm text-on-surface mb-3">Terlalu Banyak Permintaan</h2>
        <p class="text-on-surface-variant mb-8">Anda telah mengirim terlalu banyak permintaan dalam waktu singkat. Silakan tunggu beberapa saat lalu coba lagi.</p>
        <a href="{{ url('/') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-on-primary rounded-lg font-label-md hover:bg-primary-container transition-all active:scale-95">
            <span class="material-symbols-outlined">arrow_back</span>
            Kembali ke Beranda
        </a>
    </div>
</body>
</html>
