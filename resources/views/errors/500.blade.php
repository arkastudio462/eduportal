<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kesalahan Server | SMA Nusantara</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@400;600;700&family=Source+Sans+3:wght@400;600&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body class="bg-surface font-body-md text-on-surface min-h-screen flex items-center justify-center">
    <div class="text-center px-6 max-w-lg">
        <div class="w-20 h-20 bg-error-container rounded-full flex items-center justify-center mx-auto mb-6">
            <span class="material-symbols-outlined text-4xl text-error" style="font-variation-settings: 'FILL' 1;">error</span>
        </div>
        <h1 class="font-headline-xl text-headline-xl text-primary mb-2">500</h1>
        <h2 class="font-headline-sm text-headline-sm text-on-surface mb-3">Kesalahan Server</h2>
        <p class="text-on-surface-variant mb-8">Maaf, terjadi kesalahan pada server. Silakan coba lagi nanti.</p>
        <a href="{{ url('/') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-on-primary rounded-lg font-label-md hover:bg-primary-container transition-all active:scale-95">
            <span class="material-symbols-outlined">arrow_back</span>
            Kembali ke Beranda
        </a>
    </div>
</body>
</html>
