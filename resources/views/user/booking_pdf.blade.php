<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Bukti Reservasi - {{ $orderId }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 10px;
        }

        .header h1 {
            color: #2563eb;
            margin: 0;
            font-size: 24px;
        }

        .header p {
            margin: 5px 0 0;
            color: #666;
            font-size: 11px;
        }

        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }

        .info-table td {
            padding: 5px 0;
            vertical-align: top;
        }

        .info-table .label {
            font-weight: bold;
            width: 120px;
            color: #555;
        }

        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table.items th,
        table.items td {
            border: 1px solid #ddd;
            padding: 10px;
            font-size: 11px;
        }

        table.items th {
            background-color: #2563eb;
            color: white;
            text-align: left;
        }

        .total-row {
            font-weight: bold;
            background-color: #f8fafc;
        }

        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
        }

        .status-lunas {
            background-color: #dcfce7;
            color: #166534;
        }

        .status-pending {
            background-color: #fef9c3;
            color: #854d0e;
        }

        .status-ditolak {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #888;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>ANJALAI NATURE CABIN</h1>
        <p>Alamat: Alahan Panjang, Sumatera Barat | Email: info@anjalaicabin.com | Telp: +62 85161248112</p>
    </div>

    <h2 style="text-align: center; font-size: 16px; margin-bottom: 20px; color: #333;">BUKTI RESERVASI</h2>

    <table class="info-table">
        <tr>
            <td width="50%">
                <table width="100%">
                    <tr>
                        <td class="label">No. Order</td>
                        <td>: <strong>{{ $orderId }}</strong></td>
                    </tr>
                    <tr>
                        <td class="label">Tanggal Pesan</td>
                        <td>: {{ \Carbon\Carbon::parse($firstBooking->created_at)->format('d M Y, H:i') }}</td>
                    </tr>
                    <tr>
                        <td class="label">Nama Pemesan</td>
                        <td>: {{ Auth::user()->name }}</td>
                    </tr>
                    <tr>
                        <td class="label">Email</td>
                        <td>: {{ Auth::user()->email }}</td>
                    </tr>
                </table>
            </td>
            <td width="50%">
                <table width="100%">
                    <tr>
                        <td class="label">Status Pembayaran</td>
                        <td>:
                            @if($pembayaran && $pembayaran->status_pembayaran === 'diterima')
                                @if($firstBooking->jenis_pembayaran == 'dp' && $firstBooking->sisa_pembayaran > 0)
                                    <span class="status" style="background-color: #fef08a; color: #854d0e;">DP 50%
                                        DIBAYAR</span>
                                @else
                                    <span class="status status-lunas">LUNAS</span>
                                @endif
                            @elseif($pembayaran && $pembayaran->status_pembayaran === 'menunggu_konfirmasi')
                                <span class="status status-pending">MENUNGGU VERIFIKASI</span>
                            @elseif($pembayaran && $pembayaran->status_pembayaran === 'ditolak')
                                <span class="status status-ditolak">DITOLAK</span>
                            @else
                                <span class="status status-pending">MENUNGGU PEMBAYARAN</span>
                            @endif
                        </td>
                    </tr>
                    @if($pembayaran && $pembayaran->status_pembayaran === 'diterima')
                        <tr>
                            <td class="label">Tanggal Lunas</td>
                            <td>:
                                {{ $pembayaran->tanggal_pembayaran ? \Carbon\Carbon::parse($pembayaran->tanggal_pembayaran)->format('d M Y, H:i') : '-' }}
                            </td>
                        </tr>
                        <tr>
                            <td class="label">Metode Bayar</td>
                            <td>:
                                @php
                                    $metode = $pembayaran->metode_pembayaran ?? '-';
                                    $metodeLabel = match (strtolower($metode)) {
                                        'bank_transfer' => 'Transfer Bank',
                                        'gopay' => 'GoPay',
                                        'shopeepay' => 'ShopeePay',
                                        'qris' => 'QRIS',
                                        'cstore' => 'Minimarket',
                                        'credit_card' => 'Kartu Kredit',
                                        default => $metode,
                                    };
                                @endphp
                                {{ $metodeLabel }}
                            </td>
                        </tr>
                    @endif
                </table>
            </td>
        </tr>
    </table>

    <h3 style="font-size: 14px; margin-top: 20px; border-bottom: 1px solid #ddd; padding-bottom: 5px;">Rincian Pesanan
    </h3>
    <table class="items">
        <thead>
            <tr>
                <th>No</th>
                <th>Kabin</th>
                <th>Check-In & Check-Out</th>
                <th>Tamu</th>
                <!-- <th>Harga & Fasilitas</th> -->
                <th style="text-align: right;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orderBookings as $index => $booking)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $booking->cabin->name_cabin }}</strong><br>
                        <span style="font-size: 9px; color: #666;">ID:
                            BKG-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</span>
                    </td>
                    <td>
                        In: {{ \Carbon\Carbon::parse($booking->tanggal_checkin)->format('d M Y H:i') }}<br>
                        Out: {{ \Carbon\Carbon::parse($booking->tanggal_checkout)->format('d M Y H:i') }}
                    </td>
                    <td style="text-align: center;">{{ $booking->jumlah_tamu }} Org</td>

                    <!-- <td>
                            @php $fasilitas = json_decode($booking->fasilitas_tambahan, true); @endphp
                            @if($fasilitas && count($fasilitas) > 0)
                                <div style="font-size: 10px; margin-bottom: 3px;">+ Fasilitas:</div>
                                <ul style="margin: 0; padding-left: 15px; font-size: 9px;">
                                    @foreach($fasilitas as $fas)
                                        <li>{{ $fas['nama'] }} (Rp {{ number_format($fas['harga'], 0, ',', '.') }})</li>
                                    @endforeach
                                </ul>
                            @else
                                -
                            @endif
                        </td> -->
                    <td style="text-align: right;">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="4" style="text-align: right;">TOTAL BAYAR (SEKARANG)</td>
                <td style="text-align: right; color: #2563eb;">Rp
                    {{ number_format($pembayaran->jumlah_bayar ?? $grandTotal, 0, ',', '.') }}
                </td>
            </tr>
            @if($firstBooking->jenis_pembayaran == 'dp' && $orderBookings->sum('sisa_pembayaran') > 0)
                <tr class="total-row" style="background-color: #fffbeb;">
                    <td colspan="4" style="text-align: right; color: #b45309;">SISA PELUNASAN (SAAT CHECK-IN)</td>
                    <td style="text-align: right; color: #b45309;">Rp
                        {{ number_format($orderBookings->sum('sisa_pembayaran'), 0, ',', '.') }}
                    </td>
                </tr>
            @endif
        </tbody>
    </table>

    <div style="margin-top: 30px; border: 1px solid #cbd5e1; padding: 15px; background-color: #f8fafc;">
        <h3 style="font-size: 12px; margin-top: 0; color: #2563eb; margin-bottom: 8px; text-transform: uppercase;">
            Penting: Syarat & Ketentuan Menginap</h3>
        <ul style="margin: 0; padding-left: 15px; font-size: 9px; color: #475569; line-height: 1.4;">
            <li><strong>Waktu Menginap:</strong> Check-in mulai pukul 14.00 - 23.00 WIB. Check-out maksimal pukul 11.00
                WIB.</li>
            <li><strong>Kapasitas & Tambahan Tamu:</strong> Tamu usia 6 th ke atas dihitung dewasa. Jika melebihi
                kapasitas kabin, dikenakan denda/biaya tambahan <strong>Rp 185.000/orang</strong> (sudah termasuk
                extrabed & sarapan).</li>
            <li><strong>Pasangan:</strong> Kami hanya menerima pasangan menikah. Wajib menunjukkan identitas (KTP
                Suami-Istri / Fotokopi Buku Nikah) saat check-in.</li>
            <li><strong>Kebijakan Refund & Reschedule:</strong> Pembayaran/DP yang sudah masuk <strong>tidak bisa
                    direfund</strong>. Reschedule hanya bisa 1x (maksimal H-14 sebelum check-in).</li>
            <li><strong>Hewan Peliharaan:</strong> Tidak diperkenankan membawa hewan peliharaan ke dalam area kabin.
            </li>
        </ul>
    </div>

    <div class="footer">
        <p>Dokumen ini adalah e-Tiket / bukti reservasi yang sah diterbitkan oleh sistem Anjalai Nature Cabin.</p>
        <p>Silakan tunjukkan bukti ini beserta kartu identitas Anda saat proses check-in di resepsionis.</p>
        <p style="margin-top: 15px;">Dicetak pada: {{ \Carbon\Carbon::now()->format('d M Y, H:i') }}</p>
    </div>
</body>

</html>