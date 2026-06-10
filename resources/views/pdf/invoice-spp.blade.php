<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice SPP</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000421; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 18px; color: #000421; text-transform: uppercase; }
        .header p { margin: 3px 0; font-size: 12px; }
        .info-table { width: 100%; margin-bottom: 15px; }
        .info-table td { padding: 3px 5px; }
        .info-table td:first-child { width: 120px; font-weight: bold; }
        .detail { margin-bottom: 20px; }
        .detail table { width: 100%; border-collapse: collapse; }
        .detail th { background: #000421; color: white; padding: 6px 8px; text-align: left; font-size: 10px; }
        .detail td { padding: 5px 8px; border-bottom: 1px solid #ddd; }
        .status-lunas { color: #16a34a; font-weight: bold; }
        .status-belum { color: #dc2626; font-weight: bold; }
        .footer { text-align: center; margin-top: 30px; font-size: 10px; color: #666; border-top: 1px solid #ddd; padding-top: 10px; }
        .note { margin-top: 20px; padding: 10px; background: #f8f9fa; border-radius: 5px; font-size: 10px; }
    </style>
</head>
<body>
    @php $namaBulan = \Carbon\Carbon::create()->month((int) $pembayaran->bulan)->locale('id')->monthName; @endphp

    <div class="header">
        <h1>Invoice Pembayaran SPP</h1>
        <p>Sekolah EduPortal</p>
    </div>

    <table class="info-table">
        <tr><td>Nama Siswa</td><td>: {{ $pembayaran->siswa->user->name }}</td></tr>
        <tr><td>NISN</td><td>: {{ $pembayaran->siswa->nisn }}</td></tr>
        <tr><td>Kelas</td><td>: {{ $pembayaran->siswa->kelas?->nama ?? '-' }}</td></tr>
        <tr><td>Periode</td><td>: {{ $namaBulan }} {{ $pembayaran->tahun }}</td></tr>
        <tr><td>Status</td><td>: <span class="{{ $pembayaran->status === 'lunas' ? 'status-lunas' : 'status-belum' }}">{{ strtoupper($pembayaran->status) }}</span></td></tr>
    </table>

    <div class="detail">
        <table>
            <thead>
                <tr>
                    <th>Deskripsi</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>SPP Bulan {{ $namaBulan }} {{ $pembayaran->tahun }}</td>
                    <td>Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</td>
                </tr>
                <tr style="font-weight: bold; background: #f8f9fa;">
                    <td>Total</td>
                    <td>Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    @if($pembayaran->tanggal_bayar)
    <div class="note">
        <p><strong>Tanggal Bayar:</strong> {{ $pembayaran->tanggal_bayar->format('d/m/Y') }}</p>
    </div>
    @endif

    <div class="footer">
        Dicetak pada {{ now()->isoFormat('D MMMM YYYY') }} melalui Sistem Informasi Sekolah EduPortal
    </div>
</body>
</html>
