<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Dibatalkan - Meracikopi</title>
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
        .cancel-icon {
            color: #ffc107;
            font-size: 64px;
            margin-bottom: 20px;
        }
        h1 {
            color: #ffc107;
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
        .btn-secondary {
            background: #6c757d;
        }
        .btn-secondary:hover {
            background: #545b62;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="cancel-icon">⚠</div>
        <h1>Pembayaran Dibatalkan</h1>
        <div class="message">
            Pembayaran Anda telah dibatalkan.<br>
            Pesanan Anda masih tersimpan dan dapat diselesaikan kapan saja.
        </div>
        <a href="/customer/order-history" class="btn">Lihat Pesanan</a>
        <a href="/" class="btn btn-secondary">Kembali ke Beranda</a>
    </div>
</body>
</html>