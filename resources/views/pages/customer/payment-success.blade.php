<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Berhasil - Meracikopi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f5f5f5;
            text-align: center;
        }
        .container {
            max-width: 500px;
            margin: 50px auto;
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .success-icon {
            color: #28a745;
            font-size: 64px;
            margin-bottom: 20px;
        }
        h1 {
            color: #28a745;
            margin-bottom: 10px;
        }
        .message {
            color: #666;
            margin-bottom: 30px;
            line-height: 1.5;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 5px;
        }
        .btn:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="success-icon">✓</div>
        <h1>Pembayaran Berhasil!</h1>
        <div class="message">
            Terima kasih! Pembayaran Anda telah berhasil diproses.<br>
            Anda akan menerima konfirmasi pesanan segera.
        </div>
        <a href="/customer/order-history" class="btn">Lihat Pesanan</a>
        <a href="/" class="btn">Kembali ke Beranda</a>
    </div>
    
    <script>
        // Auto redirect setelah 5 detik
        setTimeout(function() {
            window.location.href = '/customer/order-history';
        }, 5000);
    </script>
</body>
</html>