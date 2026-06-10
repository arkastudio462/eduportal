<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Kartu Guru - {{ $guru->user->name }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@400;600;700&family=Source+Sans+3:wght@400;600&display=swap" rel="stylesheet">
    <style>
        @page { margin: 0; size: 85.6mm 54mm; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Source Sans 3', 'Work Sans', sans-serif;
            width: 85.6mm;
            height: 54mm;
            background: #ffffff;
            overflow: hidden;
        }
        .card {
            width: 100%;
            height: 100%;
            display: flex;
            position: relative;
            background: linear-gradient(135deg, #000421 0%, #0a1a5e 100%);
            border-radius: 0;
        }
        .card-front {
            width: 100%;
            height: 100%;
            display: flex;
            padding: 4mm;
            gap: 4mm;
        }
        .photo-section {
            width: 28mm;
            height: 36mm;
            background: #ffffff;
            border-radius: 2mm;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            flex-shrink: 0;
            margin-top: 2mm;
        }
        .photo-section img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .photo-section .placeholder {
            color: #000421;
            font-size: 24px;
            font-weight: 700;
        }
        .info-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            color: #ffffff;
            padding-left: 2mm;
        }
        .school-name {
            font-family: 'Work Sans', sans-serif;
            font-size: 9px;
            font-weight: 700;
            color: #FEAF2C;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }
        .label-role {
            font-size: 7px;
            font-weight: 600;
            color: #A6600C;
            background: rgba(254, 175, 44, 0.15);
            display: inline-block;
            padding: 1px 6px;
            border-radius: 3px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 3px;
        }
        .name {
            font-family: 'Work Sans', sans-serif;
            font-size: 13px;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 1px;
            line-height: 1.2;
        }
        .detail {
            font-size: 7.5px;
            color: rgba(255,255,255,0.8);
            line-height: 1.6;
        }
        .detail span {
            color: rgba(255,255,255,0.5);
        }
        .nip {
            font-size: 8px;
            color: #FEAF2C;
            font-weight: 600;
            margin-top: 2px;
        }
        .footer {
            position: absolute;
            bottom: 2mm;
            right: 4mm;
            font-size: 5.5px;
            color: rgba(255,255,255,0.3);
        }
        @media print {
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-front">
            <div class="photo-section">
                @if($user->profile_photo_path)
                <img src="{{ Storage::url($user->profile_photo_path) }}" alt="{{ $user->name }}">
                @else
                <div class="placeholder">{{ substr($user->name, 0, 1) }}</div>
                @endif
            </div>
            <div class="info-section">
                <div class="school-name">{{ $sekolah ?? 'SMA NUSANTARA' }}</div>
                <div class="label-role">Guru</div>
                <div class="name">{{ $user->name }}</div>
                <div class="detail"><span>NUPTK</span> {{ $guru->nuptk }}</div>
                @if($guru->nip)
                <div class="detail"><span>NIP</span> {{ $guru->nip }}</div>
                @endif
                <div class="detail"><span>Mapel</span> {{ $guru->mata_pelajaran }}</div>
                @if($guru->nip)
                <div class="nip">NIP. {{ $guru->nip }}</div>
                @endif
            </div>
        </div>
        <div class="footer">Kartu Identitas Guru • {{ $sekolah ?? 'SMA Nusantara' }}</div>
    </div>
    <script>window.print();</script>
</body>
</html>
