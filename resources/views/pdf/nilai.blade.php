<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Data Nilai</title>
    <style>
        @page { margin: 12mm; }
        body { font-family: 'Source Sans 3', 'DejaVu Sans', sans-serif; font-size: 8px; color: #000421; }
        h1 { font-size: 16px; text-align: center; margin-bottom: 4px; color: #000421; }
        .subtitle { text-align: center; font-size: 10px; color: #666; margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #000421; color: #fff; padding: 4px 5px; text-align: left; font-size: 7px; text-transform: uppercase; }
        td { padding: 3px 5px; border: 1px solid #e0e0e0; }
        tr:nth-child(even) { background: #f8f8fc; }
        .footer { text-align: center; color: #999; font-size: 7px; margin-top: 15px; border-top: 1px solid #e0e0e0; padding-top: 6px; }
    </style>
</head>
<body>
    <h1>DATA NILAI</h1>
    <p class="subtitle">SMA Nusantara</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>Mata Pelajaran</th>
                <th>Jenis</th>
                <th>Semester</th>
                <th>Skor</th>
            </tr>
        </thead>
        <tbody>
            @forelse($semuaNilai as $i => $nilai)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $nilai->siswa?->user?->name ?? '-' }}</td>
                <td>{{ $nilai->mapel?->nama ?? '-' }}</td>
                <td>{{ strtoupper($nilai->jenis ?? '-') }}</td>
                <td>{{ $nilai->semester ?? '-' }}</td>
                <td>{{ $nilai->skor ?? '-' }}</td>
            </tr>
            @empty
            <tr><td colspan="6" style="text-align:center;color:#999;">Belum ada data nilai.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada {{ now()->isoFormat('dddd, D MMMM YYYY') }} • Total {{ $semuaNilai->count() }} record
    </div>
</body>
</html>
