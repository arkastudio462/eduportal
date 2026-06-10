@props(['title' => null])
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Mode Ujian' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@400;600;700;900&family=Source+Sans+3:wght@400;600&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{ $styles ?? '' }}
</head>
<body class="bg-surface text-on-surface font-body-md min-h-screen flex flex-col overflow-hidden">
    <x-exam-topbar :ujian="$ujian ?? null" />
    <main class="flex-1 pt-14 flex overflow-hidden">
        {{ $slot }}
    </main>
    {{ $scripts ?? '' }}
</body>
</html>
