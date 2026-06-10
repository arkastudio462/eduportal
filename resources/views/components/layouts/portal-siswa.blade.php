@props(['title' => null])
<!DOCTYPE html>
<html x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" x-init="$watch('darkMode', val => { document.documentElement.classList.toggle('dark', val); localStorage.setItem('darkMode', val); if (val) document.documentElement.classList.add('dark') })" :class="{ 'dark': darkMode }" lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Portal Siswa' }}</title>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#0D1B4B">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="EduPortal">
    <link rel="apple-touch-icon" href="/favicon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@400;600;700&family=Source+Sans+3:wght@400;600&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="vapid-public-key" content="{{ config('services.webpush.vapid_public_key') }}">
    {{ $styles ?? '' }}
    <style>
        .dark body, .dark { background-color: #111318; color: #e0e0e8; }
        .dark .bg-background, .dark .bg-surface, .dark .bg-surface-bright { background-color: #111318; }
        .dark .bg-surface-container-low { background-color: #1e2025; }
        .dark .bg-surface-container { background-color: #23252b; }
        .dark .bg-surface-container-high { background-color: #2e3036; }
        .dark .bg-surface-container-highest { background-color: #393b42; }
        .dark .text-on-surface { color: #e0e0e8; }
        .dark .text-on-surface-variant { color: #b0b3bb; }
        .dark .text-outline { border-color: #8e9199; color: #8e9199; }
        .dark .border-outline-variant { border-color: #2e3036; }
        .dark .text-primary { color: #aac7ff; }
        .dark .text-secondary { color: #f9ba67; }
        .dark .text-error { color: #ffb4ab; }
        .dark .hover\:bg-surface-container-high:hover { background-color: #393b42; }
        .dark .bg-surface { background-color: #111318; }
        .dark .text-on-background { color: #e0e0e8; }
        .dark .bg-white { background-color: #1e2025; }
        .dark .gray-50, .dark .bg-gray-50 { background-color: #23252b; }
        .dark .text-gray-500 { color: #9ca3af; }
        .dark ::-webkit-scrollbar { width: 6px; }
        .dark ::-webkit-scrollbar-track { background: transparent; }
        .dark ::-webkit-scrollbar-thumb { background: #44474f; border-radius: 10px; }
        .dark input, .dark textarea, .dark select { background-color: #1e2025; color: #e0e0e8; border-color: #2e3036; }
        .dark input:focus, .dark textarea:focus, .dark select:focus { border-color: #aac7ff; outline: none; box-shadow: 0 0 0 2px rgba(170, 199, 255, 0.2); }
        .dark select option { background-color: #1e2025; color: #e0e0e8; }
        .dark input::placeholder, .dark textarea::placeholder { color: #8e9199; }
        .dark input[type="file"]::file-selector-button { background-color: #0d1b4b; color: #dde1ff; border: none; border-radius: 8px; padding: 6px 12px; font-size: 0.875rem; cursor: pointer; }
        .dark input[type="file"]:hover::file-selector-button { background-color: #1a2a5e; }
        .dark .bg-surface-container-lowest { background-color: #181a1f; }
        .dark .text-gray-500, .dark .text-gray-400 { color: #9ca3af; }
        .dark .text-gray-600, .dark .text-gray-700 { color: #d1d5db; }
        .dark .text-gray-300 { color: #9ca3af; }
        .dark .bg-gray-100 { background-color: #2e3036; }
        .dark .hover\:bg-surface-container:hover { background-color: #2e3036; }
        .dark .hover\:text-primary:hover { color: #aac7ff; }
        .dark .hover\:text-white:hover { color: #e8ecff !important; }
        .dark .text-secondary { color: #f9ba67; }
        .dark .bg-secondary-container { background-color: #633f00; }
        .dark .text-on-secondary-container { color: #ffddb4; }
        .dark .bg-primary { background-color: #0a1330; }
        .dark .bg-primary th { background-color: #0d1b4b; }
        .dark .text-on-primary { color: #dde1ff; }
        .dark .bg-primary-container { background-color: #1a2a5e; }
        .dark .hover\:bg-primary-container:hover { background-color: #253a75 !important; }
        .dark .text-on-primary-container { color: #b8c4fe; }
        .dark .text-primary-fixed { color: #dde1ff; }
        .dark .text-primary-fixed-dim { color: #7884ba; }
        .dark .text-secondary-fixed { color: #ffddb4; }
        .dark .text-secondary-fixed-dim { color: #f9ba67; }
        .dark .bg-primary-fixed { background-color: #384475; }
        .dark .bg-error { background-color: #ba1a1a; }
        .dark .text-error { color: #ffb4ab; }
        .dark .bg-green-100 { background-color: rgba(0,200,80,0.15); }
        .dark .text-green-600, .dark .text-green-700 { color: #86efac; }
        .dark .bg-red-100 { background-color: rgba(255,80,80,0.15); }
        .dark .text-red-600, .dark .text-red-700 { color: #ffb4ab; }
        .dark .bg-red-500 { background-color: #dc2626; }
        .dark .bg-amber-100 { background-color: rgba(251, 191, 36, 0.15); }
        .dark .text-amber-600 { color: #fcd34d; }
        .dark .bg-purple-100 { background-color: rgba(168, 85, 247, 0.15); }
        .dark .text-purple-700 { color: #d8b4fe; }
        .dark .bg-blue-100 { background-color: rgba(59, 130, 246, 0.15); }
        .dark .text-blue-600, .dark .text-blue-700 { color: #93c5fd; }
        .dark .bg-orange-100 { background-color: rgba(251, 146, 60, 0.15); }
        .dark .text-orange-600, .dark .text-orange-700 { color: #fdba74; }
        .dark .divide-y > *, .dark .divide-y { border-color: #2e3036; }
        .dark .bg-black\/40 { background-color: rgba(0, 0, 0, 0.7); }
        .dark .ring-primary, .dark .focus\:ring-primary:focus { --tw-ring-color: #aac7ff; }
    </style>
</head>
<body class="bg-background text-on-background font-body-md">
    <x-loading-screen />
    <x-portal-siswa-sidebar />
    <main class="md:ml-64 min-h-screen">
        <x-portal-topbar title="EduPortal" />
        <div class="p-margin-desktop max-md:p-margin-mobile pb-24 md:pb-0">
            {{ $slot }}
        </div>
    </main>
    <!-- Mobile Bottom Navigation -->
    <nav class="md:hidden fixed bottom-0 left-0 right-0 bg-surface border-t border-outline-variant px-6 py-3 flex justify-between items-center z-50">
        <a class="flex flex-col items-center gap-1 {{ request()->routeIs('portal-siswa.dashboard') ? 'text-primary' : 'text-on-surface-variant' }}" href="{{ route('portal-siswa.dashboard') }}">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' {{ request()->routeIs('portal-siswa.dashboard') ? 1 : 0 }};">dashboard</span>
            <span class="text-[10px] font-bold">Dash</span>
        </a>
        <a class="flex flex-col items-center gap-1 {{ request()->routeIs('portal-siswa.jadwal') ? 'text-primary' : 'text-on-surface-variant' }}" href="{{ route('portal-siswa.jadwal') }}">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' {{ request()->routeIs('portal-siswa.jadwal') ? 1 : 0 }};">calendar_month</span>
            <span class="text-[10px] font-medium">Jadwal</span>
        </a>
        <a class="flex flex-col items-center gap-1 {{ request()->routeIs('portal-siswa.nilai') ? 'text-primary' : 'text-on-surface-variant' }}" href="{{ route('portal-siswa.nilai') }}">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' {{ request()->routeIs('portal-siswa.nilai') ? 1 : 0 }};">grade</span>
            <span class="text-[10px] font-medium">Nilai</span>
        </a>
        <a class="flex flex-col items-center gap-1 {{ request()->routeIs('portal-siswa.tugas') ? 'text-primary' : 'text-on-surface-variant' }}" href="{{ route('portal-siswa.tugas') }}">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' {{ request()->routeIs('portal-siswa.tugas') ? 1 : 0 }};">assignment</span>
            <span class="text-[10px] font-medium">Tugas</span>
        </a>
        <a class="flex flex-col items-center gap-1 {{ request()->routeIs('portal-siswa.profil') ? 'text-primary' : 'text-on-surface-variant' }}" href="{{ route('portal-siswa.profil') }}">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' {{ request()->routeIs('portal-siswa.profil') ? 1 : 0 }};">account_circle</span>
            <span class="text-[10px] font-medium">Profil</span>
        </a>
        <a class="flex flex-col items-center gap-1 {{ request()->routeIs('portal-siswa.spp') ? 'text-primary' : 'text-on-surface-variant' }}" href="{{ route('portal-siswa.spp') }}">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' {{ request()->routeIs('portal-siswa.spp') ? 1 : 0 }};">payments</span>
            <span class="text-[10px] font-medium">SPP</span>
        </a>
    </nav>
    @php
        $_flashSuccess = session('success');
        $_flashError = session('error');
        $_flashStatus = session('status');
    @endphp
    @if($_flashSuccess || $_flashError || $_flashStatus)
    <script>
        (function() {
            @if($_flashSuccess)
            Swal.fire({ icon: 'success', title: 'Berhasil', text: '{{ addslashes($_flashSuccess) }}', timer: 3000, showConfirmButton: false, position: 'top-end', toast: true });
            @endif
            @if($_flashError)
            Swal.fire({ icon: 'error', title: 'Gagal', text: '{{ addslashes($_flashError) }}', timer: 5000, showConfirmButton: false, position: 'top-end', toast: true });
            @endif
            @if($_flashStatus)
            (function() {
                var msg = '{{ addslashes($_flashStatus) }}';
                var titles = { 'profile-updated': 'Profil diperbarui', 'password-updated': 'Kata sandi diperbarui', 'verification-link-sent': 'Tautan verifikasi dikirim' };
                Swal.fire({ icon: 'success', title: titles[msg] || 'Berhasil', text: titles[msg] ? '' : msg, timer: 3000, showConfirmButton: false, position: 'top-end', toast: true });
            })();
            @endif
        })();
    </script>
    @endif
    <script>
        (function() {
            var vapidKey = document.querySelector('meta[name="vapid-public-key"]')?.content;
            if (!vapidKey || vapidKey.length < 10) return;

            function urlBase64ToUint8Array(base64String) {
                var padding = '='.repeat((4 - base64String.length % 4) % 4);
                var base64 = (base64String + padding).replace(/\-/g, '+').replace(/_/g, '/');
                var rawData = window.atob(base64);
                return Uint8Array.from([].map.call(rawData, function(ch) { return ch.charCodeAt(0); }));
            }

            if ('serviceWorker' in navigator && 'PushManager' in window) {
                navigator.serviceWorker.register('/service-worker.js').then(function(reg) {
                    reg.pushManager.subscribe({
                        userVisibleOnly: true,
                        applicationServerKey: urlBase64ToUint8Array(vapidKey),
                    }).then(function(sub) {
                        fetch('/api/webpush/subscribe', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                            },
                            body: JSON.stringify({ endpoint: sub.endpoint, keys: sub.toJSON().keys }),
                        }).catch(function(){});
                    }).catch(function(){});
                }).catch(function(){});
            }
        })();
    </script>
    {{ $scripts ?? '' }}
</body>
</html>
