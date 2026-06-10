@props(['title' => null])
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Hasil Ujian' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@400;600;700;900&family=Source+Sans+3:wght@400;600&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{ $styles ?? '' }}
</head>
<body class="bg-background font-body-md text-on-surface">
    <header class="flex justify-between items-center h-14 px-margin-mobile md:px-margin-desktop w-full bg-primary-container text-on-primary-container fixed top-0 z-50 shadow-md">
        <div class="flex items-center gap-4">
            <h1 class="font-headline-sm text-headline-sm font-semibold text-on-primary-container">Hasil Ujian</h1>
        </div>
    </header>
    <main class="p-margin-mobile md:p-margin-desktop pt-20 pb-12">
        {{ $slot }}
    </main>
    {{ $scripts ?? '' }}
</body>
</html>
