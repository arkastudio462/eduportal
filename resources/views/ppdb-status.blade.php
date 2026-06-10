<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cek Status PPDB | EduPortal</title>
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
        <div class="max-w-2xl mx-auto">
            <div class="text-center mb-8">
                <h1 class="font-headline-lg text-headline-lg text-primary mb-2">Status Pendaftaran</h1>
                <p class="text-on-surface-variant">No. Pendaftaran: <strong>{{ $pendaftaran->no_pendaftaran }}</strong></p>
            </div>

            @php
            $statusConfig = [
                'menunggu' => ['icon' => 'hourglass_empty', 'color' => 'text-warning', 'bg' => 'bg-warning/10', 'label' => 'Menunggu Verifikasi'],
                'diverifikasi' => ['icon' => 'visibility', 'color' => 'text-info', 'bg' => 'bg-info/10', 'label' => 'Terverifikasi'],
                'diterima' => ['icon' => 'check_circle', 'color' => 'text-success', 'bg' => 'bg-success/10', 'label' => 'Diterima'],
                'ditolak' => ['icon' => 'cancel', 'color' => 'text-error', 'bg' => 'bg-error/10', 'label' => 'Ditolak'],
            ];
            $sc = $statusConfig[$pendaftaran->status] ?? $statusConfig['menunggu'];
            @endphp

            <div class="bg-white rounded-2xl border border-outline-variant card-shadow p-8 mb-6 text-center">
                <div class="w-20 h-20 {{ $sc['bg'] }} rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-5xl {{ $sc['color'] }}" style="font-variation-settings:'FILL'1">{{ $sc['icon'] }}</span>
                </div>
                <p class="font-headline-sm text-headline-sm {{ $sc['color'] }}">{{ $sc['label'] }}</p>
                @if($pendaftaran->catatan)
                <div class="mt-4 p-4 bg-surface-container-low rounded-xl text-left">
                    <p class="font-label-sm text-label-sm text-on-surface-variant mb-1">Catatan:</p>
                    <p class="text-on-surface">{{ $pendaftaran->catatan }}</p>
                </div>
                @endif
            </div>

            <div class="bg-white rounded-2xl border border-outline-variant card-shadow p-6">
                <h2 class="font-headline-sm text-headline-sm text-primary mb-4">Data Pendaftar</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div><span class="text-on-surface-variant">Nama Lengkap:</span><br><strong>{{ $pendaftaran->nama_lengkap }}</strong></div>
                    <div><span class="text-on-surface-variant">NISN:</span><br><strong>{{ $pendaftaran->nisn }}</strong></div>
                    <div><span class="text-on-surface-variant">Asal Sekolah:</span><br><strong>{{ $pendaftaran->asal_sekolah ?? '-' }}</strong></div>
                    <div><span class="text-on-surface-variant">Jurusan Dipilih:</span><br><strong>{{ $pendaftaran->jurusan_daftar ?? '-' }}</strong></div>
                </div>
            </div>

            <div class="mt-6 text-center">
                <a href="{{ route('ppdb.form') }}" class="text-primary hover:underline font-label-md">Kembali ke halaman pendaftaran</a>
            </div>
        </div>
    </main>
    <x-guest-footer />
</body>
</html>
