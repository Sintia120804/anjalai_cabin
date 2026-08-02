<!DOCTYPE html>
<html>
<head>
    <title>Laporan Pendapatan Anjalai Cabin</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .title { font-size: 18px; font-weight: bold; margin-bottom: 5px; }
        .subtitle { font-size: 14px; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; }
        .total-section { text-align: right; font-size: 14px; font-weight: bold; margin-top: 20px; }
        .footer { position: fixed; bottom: -30px; left: 0px; right: 0px; height: 50px; text-align: center; font-size: 10px; color: #999; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">ANJALAI CABIN</div>
        <div class="subtitle">{{ $title }}</div>
    </div>

    <h3>A. Transaksi Booking Online</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tgl Check-in</th>
                <th>Nama Pengunjung</th>
                <th>Cabin</th>
                <th>Keterangan</th>
                <th>Nominal (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($onlineBookings as $b)
            @php
                $pembayaran = \App\Models\Pembayaran::where('order_id', $b->order_id)->first();
                $isCancel = in_array(strtolower($b->status_booking), ['ditolak', 'batal', 'cancelled']);
                
                if ($isCancel) {
                    $label = 'Pembatalan';
                    $nominal = 0;
                } elseif (!$pembayaran || $pembayaran->status_pembayaran !== 'diterima') {
                    $label = 'Menunggu Pembayaran';
                    $nominal = 0;
                } else {
                    $nominal = $b->total_harga - $b->sisa_pembayaran;
                    
                    if (strtolower($b->jenis_pembayaran) === 'dp') {
                        if ($b->sisa_pembayaran > 0) {
                            $label = 'Pembayaran DP 50%';
                        } else {
                            $label = 'Pelunasan Sisa Bayar';
                            $nominal = $b->total_harga; // Kalau sudah lunas, nominal total masuk
                        }
                    } else {
                        $label = 'Pembayaran Lunas 100%';
                        $nominal = $b->total_harga;
                    }
                }
            @endphp
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ \Carbon\Carbon::parse($b->tanggal_checkin)->format('d/m/Y') }}</td>
                <td>{{ $b->user->name ?? '-' }}</td>
                <td>{{ $b->cabin->name_cabin ?? '-' }}</td>
                <td>{{ $label }}</td>
                <td style="text-align: right;">{{ number_format($nominal, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center;">Tidak ada transaksi online pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <h3>B. Transaksi Booking Manual (Offline)</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tgl Check-in</th>
                <th>Nama Pengunjung</th>
                <th>Cabin</th>
                <th>Total Harga (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($manualBookings as $m)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ \Carbon\Carbon::parse($m->tanggal_checkin)->format('d/m/Y') }}</td>
                <td>{{ $m->nama_pengunjung }}</td>
                <td>{{ $m->cabin->name_cabin ?? '-' }}</td>
                <td style="text-align: right;">{{ number_format($m->total_harga, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center;">Tidak ada transaksi offline pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="total-section">
        Total Pendapatan Keseluruhan: Rp {{ number_format($revenue, 0, ',', '.') }}
    </div>

    <div class="footer">
        Dicetak pada: {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}
    </div>
</body>
</html>
