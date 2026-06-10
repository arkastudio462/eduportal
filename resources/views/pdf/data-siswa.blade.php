<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Data Siswa</title>
    <style>
        @page { margin: 12mm; }
        body { font-family: 'Source Sans 3', 'DejaVu Sans', sans-serif; font-size: 9px; color: #000421; }
        h1 { font-size: 16px; text-align: center; margin-bottom: 4px; color: #000421; }
        .subtitle { text-align: center; font-size: 10px; color: #666; margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #000421; color: #fff; padding: 5px 6px; text-align: left; font-size: 8px; text-transform: uppercase; }
        td { padding: 4px 6px; border: 1px solid #e0e0e0; }
        tr:nth-child(even) { background: #f8f8fc; }
        .footer { text-align: center; color: #999; font-size: 7px; margin-top: 15px; border-top: 1px solid #e0e0e0; padding-top: 6px; }
    </style>
</head>
<body>
    <h1>DATA SISWA</h1>
    <p class="subtitle">SMA Nusantara • Tahun Ajaran {{ now()->year }}/{{ now()->year + 1 }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NISN</th>
                <th>NIS</th>
                <th>Nama</th>
                <th>Kelas</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($semuaSiswa as $i => $siswa)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $siswa->nisn }}</td>
                <td>{{ $siswa->nis ?? '-' }}</td>
                <td>{{ $siswa->user->name }}</td>
                <td>{{ $siswa->kelas?->nama ?? '-' }}</td>
                <td>{{ ucfirst($siswa->status) }}</td>
            </tr>
            @empty
            <tr><td colspan="6" style="text-align:center;color:#999;">Belum ada data siswa.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada {{ now()->isoFormat('dddd, D MMMM YYYY') }} • Total {{ $semuaSiswa->count() }} siswa
    </div>
</body>
</html>
