<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        .header {
            background: linear-gradient(135deg, #2563eb, #0ea5e9);
            color: #ffffff;
            padding: 40px 20px;
            text-align: center;
        }
        .content {
            padding: 30px;
        }
        .booking-info {
            background-color: #f1f5f9;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            padding: 20px;
            color: #94a3b8;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background-color: #2563eb;
            color: #ffffff;
            text-decoration: none;
            border-radius: 30px;
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Reservasi Diterima!</h1>
            <p>Terima kasih telah memilih Anjalai Nature Cabin</p>
        </div>
        <div class="content">
            <p>Halo <strong>{{ $booking->user->name }}</strong>,</p>
            <p>Kami telah menerima reservasi Anda. Berikut adalah rincian pesanan Anda:</p>
            
            <div class="booking-info">
                <p><strong>Cabin:</strong> {{ $booking->cabin->name_cabin }}</p>
                <p><strong>Check-in:</strong> {{ \Carbon\Carbon::parse($booking->tanggal_checkin)->format('d M Y') }}</p>
                <p><strong>Check-out:</strong> {{ \Carbon\Carbon::parse($booking->tanggal_checkout)->format('d M Y') }}</p>
                <p><strong>Total Bayar:</strong> Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</p>
                <p><strong>Status:</strong> <span style="color: #f59e0b;">Menunggu Pembayaran</span></p>
            </div>

            <p>Silakan segera lakukan pembayaran melalui dashboard untuk mengamankan slot Anda.</p>
            
            <div style="text-align: center;">
                <a href="{{ url('/user/dashboard') }}" class="btn">Lihat Pesanan Saya</a>
            </div>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Anjalai Nature Cabin. Semua hak dilindungi.</p>
            <p>Taluak Anjalai, Kec. Lembah Gumanti, Kab. Solok</p>
        </div>
    </div>
</body>
</html>
