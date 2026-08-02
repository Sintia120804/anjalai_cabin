<!DOCTYPE html>
<html>
<head>
    <title>Laporan Wahana - Anjalai</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #1d4ed8; padding-bottom: 10px; }
        .title { font-size: 18px; font-weight: bold; margin-bottom: 5px; }
        .subtitle { font-size: 14px; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; }
        .total-section { text-align: right; font-size: 14px; font-weight: bold; margin-top: 20px; }
        .footer { position: fixed; bottom: -30px; left: 0; right: 0; height: 50px; text-align: center; font-size: 10px; color: #999; }
        .section-title { font-size: 14px; font-weight: bold; margin: 20px 0 10px 0; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">🎢 ANJALAI CABIN — LAPORAN WAHANA</div>
        <div class="subtitle">{{ $title }}</div>
        <div class="subtitle">Dicetak pada: {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</div>
    </div>

    <div class="section-title">A. Rincian Booking Tiket Wahana</div>
    <table>
        <thead>
            <tr>
                <th width="30">No</th>
                <th>Tgl Kunjungan</th>
                <th>Nama Pengunjung</th>
                <th>Wahana</th>
                <th>Tiket</th>
                <th>Total (Rp)</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $i => $b)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($b->tanggal_kunjungan)->format('d/m/Y') }}</td>
                <td>{{ $b->nama_pengunjung }}</td>
                <td>{{ $b->wahana->nama ?? '-' }}</td>
                <td style="text-align:center;">{{ $b->jumlah_tiket }}</td>
                <td style="text-align:right;">
                    @if($b->status_booking == 'cancelled') - @else {{ number_format($b->total_harga, 0, ',', '.') }} @endif
                </td>
                <td>{{ ucfirst($b->status_booking) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center;">Tidak ada data booking pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="section-title">B. Daftar Wahana Aktif</div>
    <table>
        <thead>
            <tr>
                <th width="30">No</th>
                <th>Nama Wahana</th>
                <th>Durasi</th>
                <th>Harga Tiket (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($wahanas as $i => $w)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td><strong>{{ $w->nama }}</strong></td>
                <td>{{ $w->durasi ?? '-' }}</td>
                <td style="text-align:right;">{{ number_format($w->harga, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align:center;">Tidak ada data wahana.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="total-section">
        Total Wahana: {{ $totalWahana }} | Total Pendapatan: Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
    </div>

    <div class="footer">
        Laporan Wahana Anjalai Cabin — {{ $title }}
    </div>
</body>
</html>
