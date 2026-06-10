@props(['title' => null])
<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? config('app.name', 'SMA Nusantara') }}</title>
    @if (setting('favicon'))
        <link rel="icon" type="image/x-icon" href="{{ setting('favicon') }}">
        <link rel="shortcut icon" href="{{ setting('favicon') }}">
    @endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@400;600;700&family=Source+Sans+3:wght@400;600&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/gsap.js'])
    {{ $styles ?? '' }}
    <style>[data-gsap-hero-item]{will-change:transform,opacity}</style>
</head>
<body class="bg-surface font-body-md text-on-surface overflow-x-hidden">
    <x-loading-screen />
    {{ $slot }}
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
    {{ $scripts ?? '' }}
</body>
</html>
