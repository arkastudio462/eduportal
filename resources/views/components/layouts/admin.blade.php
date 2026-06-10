@props(['title' => null])
<!DOCTYPE html>
<html x-data="{ darkMode: localStorage.getItem('darkMode') === 'true', sidebarOpen: false }" x-init="$watch('darkMode', val => { document.documentElement.classList.toggle('dark', val); localStorage.setItem('darkMode', val); if (val) document.documentElement.classList.add('dark') })" :class="{ 'dark': darkMode }" lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin Dashboard' }}</title>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#0D1B4B">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="EduPortal">
    <link rel="apple-touch-icon" href="/favicon.ico">
    @if (setting('favicon'))
        <link rel="icon" type="image/x-icon" href="{{ setting('favicon') }}">
        <link rel="shortcut icon" href="{{ setting('favicon') }}">
    @endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@400;600;700&family=Source+Sans+3:wght@400;600&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{ $styles ?? '' }}
    <style>
        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: #384475; border-radius: 10px; }
        .card-shadow { box-shadow: 0 4px 6px -1px rgba(13, 27, 75, 0.04), 0 2px 4px -1px rgba(13, 27, 75, 0.02); }
        .dark .card-shadow { box-shadow: 0 4px 6px -1px rgba(0,0,0,0.3), 0 2px 4px -1px rgba(0,0,0,0.2); }
        .dark .sidebar-scroll::-webkit-scrollbar-thumb { background: #7884ba; }
        .dark ::-webkit-scrollbar { width: 6px; }
        .dark ::-webkit-scrollbar-track { background: transparent; }
        .dark ::-webkit-scrollbar-thumb { background: #44474f; border-radius: 10px; }
        .dark, .dark body { background-color: #111318; color: #e0e0e8; }
        .dark .bg-background, .dark .bg-surface, .dark .bg-surface-bright { background-color: #111318; }
        .dark .bg-surface-container-lowest { background-color: #181a1f; }
        .dark .bg-surface-container-low { background-color: #1e2025; }
        .dark .bg-surface-container { background-color: #23252b; }
        .dark .bg-surface-container-high { background-color: #2e3036; }
        .dark .bg-surface-container-highest { background-color: #393b42; }
        .dark .text-on-surface { color: #e0e0e8; }
        .dark .text-on-surface-variant { color: #b0b3bb; }
        .dark .text-outline { color: #8e9199; }
        .dark .text-on-background { color: #e0e0e8; }
        .dark .border-outline-variant { border-color: #2e3036; }
        .dark .divide-outline-variant > * { border-color: #2e3036; }
        .dark .divide-outline-variant { border-color: #2e3036; }
        .dark .bg-white { background-color: #1e2025; }
        .dark .text-gray-500, .dark .text-gray-400, .dark .text-gray-300 { color: #9ca3af; }
        .dark .text-gray-600 { color: #d1d5db; }
        .dark .bg-gray-50 { background-color: #23252b; }
        .dark .bg-gray-100 { background-color: #2e3036; }
        .dark .hover\:bg-surface-container:hover { background-color: #2e3036; }
        .dark .hover\:bg-surface-container-high:hover { background-color: #393b42; }
        .dark .hover\:text-primary:hover { color: #aac7ff; }
        .dark .text-primary { color: #aac7ff; }
        .dark .text-secondary { color: #f9ba67; }
        .dark .bg-secondary { background-color: #835500; }
        .dark .text-on-secondary { color: #ffffff; }
        .dark .bg-secondary-container { background-color: #633f00; }
        .dark .text-on-secondary-container { color: #ffddb4; }
        .dark .hover\:bg-secondary\/10:hover { background-color: rgba(249, 186, 103, 0.1); }
        .dark .bg-primary { background-color: #0a1330; }
        .dark .text-on-primary { color: #dde1ff; }
        .dark .bg-primary-container { background-color: #1a2a5e; }
        .dark .hover\:bg-primary-container:hover { background-color: #253a75 !important; }
        .dark .text-on-primary-container { color: #b8c4fe; }
        .dark .text-primary-fixed { color: #dde1ff; }
        .dark .text-primary-fixed-dim { color: #7884ba; }
        .dark .text-secondary-fixed { color: #ffddb4; }
        .dark .text-secondary-fixed-dim { color: #f9ba67; }
        .dark .bg-primary-fixed { background-color: #384475; }
        .dark .text-primary-fixed-variant { color: #8894c9; }
        .dark .text-on-primary-fixed-variant { color: #8894c9; }
        .dark .hover\:text-white:hover { color: #e8ecff !important; }
        .dark .bg-surface-container-high { background-color: #2e3036; }
        .dark .bg-error { background-color: #ba1a1a; }
        .dark .text-error { color: #ffb4ab; }
        .dark .bg-green-100 { background-color: rgba(0,200,80,0.15); }
        .dark .text-green-700 { color: #86efac; }
        .dark .bg-red-100 { background-color: rgba(255,80,80,0.15); }
        .dark .text-red-600, .dark .text-red-700 { color: #ffb4ab; }
        .dark .bg-red-500 { background-color: #dc2626; }
        .dark .bg-red-600 { background-color: #b91c1c; }
        .dark .hover\:bg-red-700:hover { background-color: #991b1b; }
        .dark input, .dark textarea, .dark select { background-color: #1e2025; color: #e0e0e8; border-color: #2e3036; }
        .dark input:focus, .dark textarea:focus, .dark select:focus { border-color: #aac7ff; outline: none; box-shadow: 0 0 0 2px rgba(170, 199, 255, 0.2); }
        .dark select option { background-color: #1e2025; color: #e0e0e8; }
        .dark input::placeholder, .dark textarea::placeholder { color: #8e9199; }
        .dark input[type="file"]::file-selector-button { background-color: #0d1b4b; color: #dde1ff; border: none; border-radius: 8px; padding: 6px 12px; font-size: 0.875rem; cursor: pointer; }
        .dark input[type="file"]:hover::file-selector-button { background-color: #1a2a5e; }
        .dark .bg-white { background-color: #1e2025; }
        .dark thead.bg-primary, .dark .bg-primary th { background-color: #0d1b4b; }
        .dark .bg-amber-100 { background-color: rgba(251, 191, 36, 0.15); }
        .dark .text-amber-600 { color: #fcd34d; }
        .dark .bg-purple-100 { background-color: rgba(168, 85, 247, 0.15); }
        .dark .text-purple-700 { color: #d8b4fe; }
        .dark .bg-blue-100 { background-color: rgba(59, 130, 246, 0.15); }
        .dark .text-blue-700 { color: #93c5fd; }
        .dark .bg-orange-100 { background-color: rgba(251, 146, 60, 0.15); }
        .dark .text-orange-700 { color: #fdba74; }
        .dark .text-gray-700 { color: #d1d5db; }
        .dark .divide-y > *, .dark .divide-y { border-color: #2e3036; }
        .dark .border-outline-variant { border-color: #2e3036; }
        .dark .bg-black\/40 { background-color: rgba(0, 0, 0, 0.7); }
        .dark .ring-primary, .dark .focus\:ring-primary:focus { --tw-ring-color: #aac7ff; }
    </style>
</head>
<body class="bg-background text-on-surface font-body-md">
    <x-loading-screen />

    {{-- Mobile sidebar backdrop --}}
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-black/50 md:hidden"></div>

    {{-- Mobile sidebar toggle --}}
    <button @click="sidebarOpen = !sidebarOpen" x-cloak class="md:hidden fixed left-0 top-1/2 -translate-y-1/2 z-50 w-7 h-16 bg-primary rounded-r-xl flex items-center justify-center shadow-lg hover:bg-primary-container transition-colors">
        <span class="material-symbols-outlined text-on-primary !text-[18px]" x-text="sidebarOpen ? 'chevron_left' : 'chevron_right'"></span>
    </button>

    <x-admin-sidebar />
    <main :class="sidebarOpen ? 'overflow-hidden md:overflow-visible' : ''" class="md:ml-64 min-h-screen">
        <x-admin-topbar />
        <div class="p-margin-desktop space-y-gutter">
            @isset($header)
                <header class="mb-6">
                    {{ $header }}
                </header>
            @endisset
            {{ $slot }}
        </div>
    </main>
    @php
        $_flashSuccess = session('success');
        $_flashError = session('error');
        $_flashStatus = session('status');
    @endphp
    @if($_flashSuccess)
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
         class="fixed top-4 right-4 z-[9999] max-w-md bg-green-600 text-white px-5 py-4 rounded-xl shadow-2xl flex items-start gap-3">
        <span class="material-symbols-outlined text-white flex-none mt-0.5">check_circle</span>
        <p class="text-sm font-semibold flex-1">{{ $_flashSuccess }}</p>
        <button @click="show = false" class="text-white/70 hover:text-white flex-none"><span class="material-symbols-outlined">close</span></button>
    </div>
    @endif
    @if($_flashError)
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 7000)"
         class="fixed top-4 right-4 z-[9999] max-w-md bg-red-600 text-white px-5 py-4 rounded-xl shadow-2xl flex items-start gap-3">
        <span class="material-symbols-outlined text-white flex-none mt-0.5">error</span>
        <p class="text-sm font-semibold flex-1">{{ $_flashError }}</p>
        <button @click="show = false" class="text-white/70 hover:text-white flex-none"><span class="material-symbols-outlined">close</span></button>
    </div>
    @endif
    @if($_flashSuccess || $_flashError || $_flashStatus)
    <script>
        (function() {
            @if($_flashSuccess)
            if (typeof Swal !== 'undefined') Swal.fire({ icon: 'success', title: 'Berhasil', text: '{{ addslashes($_flashSuccess) }}', timer: 3000, showConfirmButton: false, position: 'top-end', toast: true });
            @endif
            @if($_flashError)
            if (typeof Swal !== 'undefined') Swal.fire({ icon: 'error', title: 'Gagal', text: '{{ addslashes($_flashError) }}', timer: 5000, showConfirmButton: false, position: 'top-end', toast: true });
            @endif
            @if($_flashStatus)
            (function() {
                var msg = '{{ addslashes($_flashStatus) }}';
                var titles = { 'profile-updated': 'Profil diperbarui', 'password-updated': 'Kata sandi diperbarui', 'verification-link-sent': 'Tautan verifikasi dikirim' };
                if (typeof Swal !== 'undefined') Swal.fire({ icon: 'success', title: titles[msg] || 'Berhasil', text: titles[msg] ? '' : msg, timer: 3000, showConfirmButton: false, position: 'top-end', toast: true });
            })();
            @endif
        })();
    </script>
    @endif
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/service-worker.js').catch(function(){});
        }
    </script>
    {{ $scripts ?? '' }}
</body>
</html>
