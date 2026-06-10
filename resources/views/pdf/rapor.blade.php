<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rapor - {{ $siswa->user->name }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000421; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 18px; color: #000421; text-transform: uppercase; }
        .header p { margin: 3px 0; font-size: 12px; }
        .info-table { width: 100%; margin-bottom: 15px; }
        .info-table td { padding: 3px 5px; }
        .info-table td:first-child { width: 120px; font-weight: bold; }
        table.nilai { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        table.nilai th { background: #000421; color: white; padding: 6px 8px; text-align: left; font-size: 10px; }
        table.nilai td { padding: 5px 8px; border-bottom: 1px solid #ddd; }
        table.nilai tr:nth-child(even) { background: #f8f9fa; }
        .kehadiran { margin-bottom: 15px; }
        .kehadiran table { width: 100%; border-collapse: collapse; }
        .kehadiran th { background: #A6600C; color: white; padding: 5px 8px; font-size: 10px; }
        .kehadiran td { padding: 5px 8px; border-bottom: 1px solid #ddd; text-align: center; }
        .footer { text-align: center; margin-top: 30px; font-size: 10px; color: #666; border-top: 1px solid #ddd; padding-top: 10px; }
        .rata-rata { text-align: right; font-size: 12px; font-weight: bold; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Hasil Belajar</h1>
        <p>Sekolah EduPortal</p>
        <p>Tahun Ajaran {{ $tahunAjar }} - Semester {{ $semester }}</p>
    </div>

    <table class="info-table">
        <tr><td>Nama Siswa</td><td>: {{ $siswa->user->name }}</td></tr>
        <tr><td>NISN</td><td>: {{ $siswa->nisn }}</td></tr>
        <tr><td>NIS</td><td>: {{ $siswa->nis }}</td></tr>
        <tr><td>Kelas</td><td>: {{ $siswa->kelas?->nama ?? '-' }}</td></tr>
        <tr><td>Wali Kelas</td><td>: {{ $siswa->kelas?->waliKelas?->user?->name ?? '-' }}</td></tr>
    </table>

    <table class="nilai">
        <thead>
            <tr>
                <th>Mata Pelajaran</th>
                <th>Nilai</th>
            </tr>
        </thead>
        <tbody>
            @forelse($nilaiList as $mapelNama => $nilaiItems)
                @php $avg = round($nilaiItems->avg('skor'), 1); @endphp
                <tr>
                    <td>{{ $mapelNama }}</td>
                    <td>{{ $avg }}</td>
                </tr>
            @empty
                <tr><td colspan="2" style="text-align:center;color:#999;">Belum ada nilai</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="rata-rata">
        Rata-rata Nilai: <span style="color: #000421;">{{ number_format($rataRata ?? 0, 1) }}</span>
    </div>

    <div class="kehadiran">
        <table>
            <thead>
                <tr><th>Hadir</th><th>Sakit</th><th>Izin</th><th>Alpha</th></tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $kehadiran['hadir'] }}</td>
                    <td>{{ $kehadiran['sakit'] }}</td>
                    <td>{{ $kehadiran['izin'] }}</td>
                    <td>{{ $kehadiran['alpha'] }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="footer">
        Dicetak pada {{ now()->isoFormat('D MMMM YYYY') }} melalui Sistem Informasi Sekolah EduPortal
    </div>
</body>
</html>
