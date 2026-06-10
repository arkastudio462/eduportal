<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        @page { margin: 15mm; }
        body { font-family: 'Source Sans 3', 'DejaVu Sans', sans-serif; font-size: 10px; color: #000421; }
        h1 { font-size: 18px; text-align: center; margin-bottom: 4px; color: #000421; }
        .subtitle { text-align: center; font-size: 11px; color: #666; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        th { background: #000421; color: #fff; padding: 6px 8px; text-align: left; font-size: 9px; text-transform: uppercase; }
        td { padding: 5px 8px; border: 1px solid #e0e0e0; vertical-align: top; }
        tr:nth-child(even) { background: #f8f8fc; }
        .mapel-name { font-weight: 600; }
        .time { color: #A6600C; font-size: 9px; }
        .footer { text-align: center; color: #999; font-size: 8px; margin-top: 20px; border-top: 1px solid #e0e0e0; padding-top: 8px; }
        .day-header { background: #FEAF2C; color: #000421; font-weight: 700; }
        .empty { color: #999; font-style: italic; }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>
    <h1>JADWAL PELAJARAN</h1>
    <p class="subtitle">{{ $selectedKelas ? 'Kelas ' . $selectedKelas->nama : 'Semua Kelas' }}</p>

    @if($semuaJadwal->isEmpty())
        <p style="text-align:center;color:#999;margin-top:40px;">Belum ada jadwal pelajaran.</p>
    @else
        @php
            $kelompokKelas = $semuaJadwal->groupBy(function($j) {
                return $j->kelas?->nama ?? 'Tanpa Kelas';
            });
        @endphp

        @foreach($kelompokKelas as $kelasNama => $jadwalKelas)
            @php $perHari = collect($hariList)->mapWithKeys(fn($h) => [$h => $jadwalKelas->where('hari', $h)->sortBy('jam_mulai')]); @endphp

            <h2 style="font-size:13px;margin-bottom:8px;color:#A6600C;">{{ $kelasNama }}</h2>

            <table>
                <thead>
                    <tr>
                        <th style="width:60px;">Hari</th>
                        <th style="width:80px;">Jam</th>
                        <th>Mata Pelajaran</th>
                        <th>Guru</th>
                        <th style="width:60px;">Ruang</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($hariList as $hari)
                        @php $items = $perHari[$hari] ?? collect(); @endphp
                        <tr>
                            <td class="day-header">{{ $hari }}</td>
                            <td colspan="4">
                                @if($items->isEmpty())
                                    <span class="empty">Tidak ada jadwal</span>
                                @else
                                    @foreach($items as $j)
                                        <div style="margin-bottom:3px;">
                                            <span class="time">{{ substr($j->jam_mulai, 0, 5) }} - {{ substr($j->jam_selesai, 0, 5) }}</span>
                                            <span class="mapel-name">{{ $j->mapel?->nama ?? '-' }}</span>
                                            <span style="color:#666;"> • {{ $j->guru?->user?->name ?? '-' }}</span>
                                            @if($j->ruang) <span style="color:#999;"> • {{ $j->ruang }}</span> @endif
                                        </div>
                                    @endforeach
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if(!$loop->last)
            <div class="page-break"></div>
            @endif
        @endforeach
    @endif

    <div class="footer">
        Dicetak pada {{ now()->isoFormat('dddd, D MMMM YYYY') }} • Sistem Informasi Akademik SMA Nusantara
    </div>
</body>
</html>
