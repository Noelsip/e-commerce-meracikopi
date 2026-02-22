<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meracikopi — Sedang Tutup</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: #1e1715;
            color: #f0f2bd;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 24px;
            overflow: hidden;
            position: relative;
        }

        /* Animated background elements */
        body::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(ellipse at 30% 20%, rgba(202, 120, 66, 0.08) 0%, transparent 60%),
                        radial-gradient(ellipse at 70% 80%, rgba(139, 94, 60, 0.06) 0%, transparent 60%);
            animation: bgFloat 15s ease-in-out infinite;
            pointer-events: none;
        }

        @keyframes bgFloat {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(-2%, 1%) rotate(1deg); }
            66% { transform: translate(1%, -1%) rotate(-0.5deg); }
        }

        .closed-container {
            text-align: center;
            max-width: 520px;
            position: relative;
            z-index: 1;
            animation: fadeInUp 0.8s ease;
        }

        @keyframes fadeInUp {
            0% { opacity: 0; transform: translateY(30px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        /* Coffee Cup Icon */
        .coffee-icon {
            width: 120px;
            height: 120px;
            margin: 0 auto 32px;
            position: relative;
        }

        .coffee-cup {
            width: 80px;
            height: 70px;
            background: linear-gradient(135deg, #CA7842 0%, #8B5E3C 100%);
            border-radius: 0 0 30px 30px;
            margin: 30px auto 0;
            position: relative;
            box-shadow: 0 8px 32px rgba(202, 120, 66, 0.3);
        }

        .coffee-cup::before {
            content: '';
            position: absolute;
            top: -8px;
            left: -8px;
            right: -8px;
            height: 12px;
            background: linear-gradient(135deg, #d4a574 0%, #CA7842 100%);
            border-radius: 6px;
        }

        /* Handle */
        .coffee-cup::after {
            content: '';
            position: absolute;
            right: -20px;
            top: 10px;
            width: 20px;
            height: 30px;
            border: 4px solid #CA7842;
            border-left: none;
            border-radius: 0 12px 12px 0;
        }

        /* Steam */
        .steam {
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 8px;
        }

        .steam span {
            width: 3px;
            height: 20px;
            background: rgba(240, 242, 189, 0.3);
            border-radius: 3px;
            animation: steamRise 2s ease-in-out infinite;
        }

        .steam span:nth-child(2) {
            animation-delay: 0.4s;
            height: 24px;
        }

        .steam span:nth-child(3) {
            animation-delay: 0.8s;
        }

        @keyframes steamRise {
            0% { opacity: 0; transform: translateY(0) scaleY(0.5); }
            50% { opacity: 0.6; transform: translateY(-10px) scaleY(1); }
            100% { opacity: 0; transform: translateY(-25px) scaleY(0.5); }
        }

        .closed-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 20px;
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 100px;
            font-size: 13px;
            font-weight: 600;
            color: #f87171;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 24px;
        }

        .closed-badge .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #ef4444;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(0.8); }
        }

        h1 {
            font-size: 32px;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 16px;
            background: linear-gradient(135deg, #f0f2bd, #CA7842);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .subtitle {
            font-size: 16px;
            color: rgba(240, 242, 189, 0.6);
            line-height: 1.6;
            margin-bottom: 40px;
        }

        .info-card {
            background: rgba(43, 33, 30, 0.8);
            border: 1px solid #3e302b;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 28px;
            backdrop-filter: blur(10px);
        }

        .info-card h3 {
            font-size: 14px;
            font-weight: 600;
            color: #CA7842;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
        }

        .info-row:not(:last-child) {
            border-bottom: 1px solid rgba(62, 48, 43, 0.6);
        }

        .info-label {
            font-size: 14px;
            color: rgba(240, 242, 189, 0.5);
        }

        .info-value {
            font-size: 14px;
            font-weight: 600;
            color: #f0f2bd;
        }

        .order-history-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 14px 28px;
            background: linear-gradient(135deg, #CA7842 0%, #8B5E3C 100%);
            color: #fff;
            font-weight: 600;
            font-size: 15px;
            border-radius: 12px;
            text-decoration: none;
            transition: all 0.3s;
            box-shadow: 0 4px 16px rgba(202, 120, 66, 0.3);
        }

        .order-history-link:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(202, 120, 66, 0.4);
        }

        .brand-footer {
            position: absolute;
            bottom: 24px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 13px;
            color: rgba(240, 242, 189, 0.25);
            letter-spacing: 1px;
        }

        .brand-footer span {
            color: #CA7842;
            font-weight: 600;
        }

        @media (max-width: 480px) {
            h1 { font-size: 26px; }
            .subtitle { font-size: 14px; }
            .coffee-icon { width: 100px; height: 100px; }
            .coffee-cup { width: 64px; height: 56px; }
        }
    </style>
</head>

<body>
    <div class="closed-container">
        <!-- Coffee Cup with Steam -->
        <div class="coffee-icon">
            <div class="steam">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <div class="coffee-cup"></div>
        </div>

        <!-- Status Badge -->
        <div class="closed-badge">
            <span class="dot"></span>
            Saat ini tutup
        </div>

        <!-- Title -->
        <h1>Meracikopi Belum Buka</h1>

        <!-- Description -->
        <p class="subtitle">
            Kami sedang mempersiapkan racikan kopi terbaik untuk kamu. 
            Silakan kembali lagi nanti saat toko sudah buka. Terima kasih sudah menunggu! ☕
        </p>

        <!-- Info Card -->
        <div class="info-card">
            <h3>
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
                </svg>
                Informasi
            </h3>
            <div class="info-row">
                <span class="info-label">Status</span>
                <span class="info-value" style="color: #f87171;">Tutup</span>
            </div>
            <div class="info-row">
                <span class="info-label">Pemesanan</span>
                <span class="info-value" style="color: #f87171;">Tidak tersedia</span>
            </div>
            <div class="info-row">
                <span class="info-label">Cek kembali</span>
                <span class="info-value">Beberapa saat lagi</span>
            </div>
        </div>

        <!-- Action -->
        <a href="{{ route('order-history.index') }}" class="order-history-link">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/>
                <rect x="9" y="3" width="6" height="4" rx="2"/>
                <path d="M9 14l2 2 4-4"/>
            </svg>
            Lihat Riwayat Pesanan
        </a>
    </div>

    <div class="brand-footer">
        <span>MERACIKOPI</span> · Coffee & More
    </div>

    <!-- Auto-refresh: check if store has opened every 30 seconds -->
    <script>
        setInterval(async () => {
            try {
                const res = await fetch(window.location.href, { method: 'HEAD' });
                // If the store is now open, the middleware won't return 503
                if (res.ok) {
                    window.location.reload();
                }
            } catch(e) {}
        }, 30000);
    </script>
</body>

</html>
