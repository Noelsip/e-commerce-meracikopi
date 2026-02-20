<x-customer.layout title="Meracikopi - User Guide">
    <style>
        /* Hide default navbar, announcement bar & footer on user guide page */
        .navbar-container,
        .announcement-bar,
        .footer-container {
            display: none !important;
        }

        html {
            scroll-behavior: smooth;
        }

        /* ===== CUSTOM NAVBAR ===== */
        .ug-navbar {
            background-color: #2a1b14;
            padding: 14px 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .ug-navbar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .ug-navbar-brand img {
            width: 40px;
            height: 40px;
            object-fit: contain;
        }

        .ug-navbar-brand span {
            color: white;
            font-weight: 600;
            font-size: 18px;
        }

        .ug-navbar-help {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #CA7842;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .ug-navbar-help svg {
            width: 18px;
            height: 18px;
        }

        @media (max-width: 480px) {
            .ug-navbar {
                padding: 12px 16px;
            }

            .ug-navbar-brand img {
                width: 32px;
                height: 32px;
            }

            .ug-navbar-brand span {
                font-size: 15px;
            }

            .ug-navbar-help {
                font-size: 12px;
            }
        }

        /* ===== USER GUIDE BASE ===== */
        .ug-page {
            background-color: #120c09;
            color: #e8e0da;
            min-height: 100vh;
        }

        .ug-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 0 24px;
        }

        /* ===== COVER / HERO ===== */
        .ug-cover {
            background: linear-gradient(160deg, #2a1b14 0%, #120c09 50%, #1a120d 100%);
            border-bottom: 1px solid rgba(202, 120, 66, 0.15);
            padding: 80px 24px 60px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .ug-cover::before {
            content: '';
            position: absolute;
            top: -120px;
            right: -120px;
            width: 320px;
            height: 320px;
            background: radial-gradient(circle, rgba(202, 120, 66, 0.08) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .ug-cover::after {
            content: '';
            position: absolute;
            bottom: -80px;
            left: -80px;
            width: 240px;
            height: 240px;
            background: radial-gradient(circle, rgba(202, 120, 66, 0.05) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .ug-cover-badge {
            display: inline-block;
            background: rgba(202, 120, 66, 0.12);
            border: 1px solid rgba(202, 120, 66, 0.25);
            color: #CA7842;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            padding: 6px 18px;
            border-radius: 50px;
            margin-bottom: 24px;
        }

        .ug-cover h1 {
            font-size: 42px;
            font-weight: 700;
            color: #ffffff;
            margin: 0 0 8px;
            line-height: 1.2;
        }

        .ug-cover h1 span {
            color: #CA7842;
        }

        .ug-cover-subtitle {
            font-size: 16px;
            color: #a89890;
            margin: 0 0 40px;
            font-weight: 400;
        }

        .ug-cover-meta {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 16px;
            overflow: hidden;
            max-width: 620px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .ug-cover-meta-item {
            padding: 20px 24px;
            text-align: center;
            border-right: 1px solid rgba(255, 255, 255, 0.05);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .ug-cover-meta-item:nth-child(3n) {
            border-right: none;
        }

        .ug-cover-meta-item:nth-last-child(-n+3) {
            border-bottom: none;
        }

        .ug-cover-meta-item dt {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #CA7842;
            margin: 0 0 6px;
        }

        .ug-cover-meta-item dd {
            font-size: 14px;
            color: #e8e0da;
            margin: 0;
            font-weight: 500;
        }

        /* ===== TABLE OF CONTENTS ===== */
        .ug-toc {
            background: #1a1410;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            position: sticky;
            top: 0;
            z-index: 90;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .ug-toc::-webkit-scrollbar {
            height: 0;
        }

        .ug-toc-inner {
            max-width: 900px;
            margin: 0 auto;
            display: flex;
            gap: 0;
            padding: 0 24px;
            white-space: nowrap;
        }

        .ug-toc a {
            display: block;
            padding: 14px 16px;
            font-size: 12px;
            font-weight: 500;
            color: #a89890;
            text-decoration: none;
            letter-spacing: 0.3px;
            border-bottom: 2px solid transparent;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .ug-toc a:hover {
            color: #CA7842;
            border-bottom-color: #CA7842;
        }

        /* ===== SECTIONS ===== */
        .ug-section {
            padding: 64px 24px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.04);
        }

        .ug-section:last-child {
            border-bottom: none;
        }

        .ug-section-header {
            margin-bottom: 36px;
        }

        .ug-section-number {
            display: inline-block;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2px;
            color: #CA7842;
            margin-bottom: 8px;
        }

        .ug-section-title {
            font-size: 28px;
            font-weight: 700;
            color: #ffffff;
            margin: 0 0 12px;
            line-height: 1.3;
        }

        .ug-section-line {
            width: 48px;
            height: 3px;
            background: linear-gradient(90deg, #CA7842, transparent);
            border-radius: 2px;
        }

        /* ===== PARAGRAPHS ===== */
        .ug-text {
            font-size: 15px;
            line-height: 1.8;
            color: #b8aea8;
            margin-bottom: 20px;
        }

        .ug-text strong {
            color: #e8e0da;
            font-weight: 600;
        }

        /* ===== CARDS ===== */
        .ug-card {
            background: rgba(255, 255, 255, 0.025);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 16px;
            padding: 28px;
            margin-bottom: 16px;
            transition: border-color 0.3s ease;
        }

        .ug-card:hover {
            border-color: rgba(202, 120, 66, 0.2);
        }

        .ug-card-title {
            font-size: 16px;
            font-weight: 600;
            color: #ffffff;
            margin: 0 0 12px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .ug-card-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: rgba(202, 120, 66, 0.12);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .ug-card-icon svg {
            width: 18px;
            height: 18px;
            color: #CA7842;
        }

        /* ===== TABLE ===== */
        .ug-table-wrap {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.06);
            margin-bottom: 24px;
        }

        .ug-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            min-width: 480px;
        }

        .ug-table thead {
            background: rgba(202, 120, 66, 0.08);
        }

        .ug-table th {
            padding: 14px 20px;
            text-align: left;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #CA7842;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }

        .ug-table td {
            padding: 14px 20px;
            color: #b8aea8;
            border-bottom: 1px solid rgba(255, 255, 255, 0.03);
            vertical-align: top;
        }

        .ug-table tbody tr:last-child td {
            border-bottom: none;
        }

        .ug-table tbody tr:hover {
            background: rgba(255, 255, 255, 0.015);
        }

        .ug-table .ug-badge {
            display: inline-block;
            background: rgba(202, 120, 66, 0.1);
            color: #CA7842;
            font-size: 11px;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 6px;
        }

        /* ===== ORDERED STEPS ===== */
        .ug-steps {
            list-style: none;
            padding: 0;
            margin: 0 0 24px;
            counter-reset: step-counter;
        }

        .ug-steps li {
            counter-increment: step-counter;
            display: flex;
            gap: 16px;
            align-items: flex-start;
            padding: 16px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.03);
        }

        .ug-steps li:last-child {
            border-bottom: none;
        }

        .ug-steps li::before {
            content: counter(step-counter);
            flex-shrink: 0;
            width: 32px;
            height: 32px;
            border-radius: 10px;
            background: rgba(202, 120, 66, 0.12);
            color: #CA7842;
            font-size: 13px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .ug-steps li span {
            font-size: 14px;
            line-height: 1.7;
            color: #b8aea8;
            padding-top: 4px;
        }

        .ug-steps li span strong {
            color: #e8e0da;
        }

        /* ===== FEATURE GRID ===== */
        .ug-feature-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 24px;
        }

        /* ===== ERROR TABLE ===== */
        .ug-error-card {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 14px;
            padding: 24px;
            margin-bottom: 14px;
        }

        .ug-error-title {
            font-size: 15px;
            font-weight: 600;
            color: #ffffff;
            margin: 0 0 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .ug-error-title .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #e74c3c;
            flex-shrink: 0;
        }

        .ug-error-solution {
            font-size: 14px;
            color: #b8aea8;
            line-height: 1.7;
            margin: 0;
            padding-left: 16px;
            border-left: 2px solid rgba(202, 120, 66, 0.3);
        }

        .ug-error-solution strong {
            color: #CA7842;
            font-weight: 600;
        }

        /* ===== SUPPORT CARDS ===== */
        .ug-support-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }

        .ug-support-card {
            background: linear-gradient(160deg, rgba(202, 120, 66, 0.06) 0%, rgba(255, 255, 255, 0.02) 100%);
            border: 1px solid rgba(202, 120, 66, 0.12);
            border-radius: 16px;
            padding: 28px 24px;
            text-align: center;
            transition: border-color 0.3s ease, transform 0.3s ease;
        }

        .ug-support-card:hover {
            border-color: rgba(202, 120, 66, 0.35);
            transform: translateY(-2px);
        }

        .ug-support-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            background: rgba(202, 120, 66, 0.12);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
        }

        .ug-support-icon svg {
            width: 22px;
            height: 22px;
            color: #CA7842;
        }

        .ug-support-label {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #CA7842;
            margin: 0 0 6px;
        }

        .ug-support-value {
            font-size: 14px;
            font-weight: 500;
            color: #e8e0da;
            margin: 0;
            word-break: break-word;
        }

        /* ===== FOOTER COPYRIGHT ===== */
        .ug-footer-copy {
            text-align: center;
            padding: 32px 24px;
            border-top: 1px solid rgba(255, 255, 255, 0.04);
        }

        .ug-footer-copy p {
            font-size: 12px;
            color: #6b6058;
            letter-spacing: 0.5px;
            margin: 0;
        }

        /* ===== BACK TO TOP ===== */
        .ug-back-top {
            position: fixed;
            bottom: 28px;
            right: 28px;
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: #CA7842;
            color: #fff;
            border: none;
            cursor: pointer;
            display: none;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 16px rgba(202, 120, 66, 0.35);
            transition: all 0.3s ease;
            z-index: 200;
        }

        .ug-back-top:hover {
            background: #b5683a;
            transform: translateY(-2px);
        }

        .ug-back-top.show {
            display: flex;
        }

        /* ===== SUB-HEADING ===== */
        .ug-sub-heading {
            font-size: 17px;
            font-weight: 600;
            color: #e8e0da;
            margin: 28px 0 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .ug-sub-heading .ug-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #CA7842;
            flex-shrink: 0;
        }

        /* ===== MENU ITEM LIST ===== */
        .ug-menu-item {
            display: flex;
            gap: 14px;
            align-items: flex-start;
            padding: 18px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.03);
        }

        .ug-menu-item:last-child {
            border-bottom: none;
        }

        .ug-menu-icon-box {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: rgba(202, 120, 66, 0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .ug-menu-icon-box svg {
            width: 18px;
            height: 18px;
            color: #CA7842;
        }

        .ug-menu-name {
            font-size: 15px;
            font-weight: 600;
            color: #ffffff;
            margin: 0 0 4px;
        }

        .ug-menu-desc {
            font-size: 13px;
            color: #a89890;
            line-height: 1.6;
            margin: 0;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .ug-cover {
                padding: 60px 20px 48px;
            }

            .ug-cover h1 {
                font-size: 28px;
            }

            .ug-cover-subtitle {
                font-size: 14px;
            }

            .ug-cover-meta {
                grid-template-columns: 1fr 1fr;
                max-width: 100%;
            }

            .ug-cover-meta-item:nth-child(3n) {
                border-right: 1px solid rgba(255, 255, 255, 0.05);
            }

            .ug-cover-meta-item:nth-child(2n) {
                border-right: none;
            }

            .ug-section {
                padding: 48px 20px;
            }

            .ug-section-title {
                font-size: 22px;
            }

            .ug-feature-grid {
                grid-template-columns: 1fr;
            }

            .ug-support-grid {
                grid-template-columns: 1fr;
            }

            .ug-toc a {
                padding: 12px 12px;
                font-size: 11px;
            }

            .ug-back-top {
                bottom: 20px;
                right: 20px;
                width: 40px;
                height: 40px;
            }
        }

        @media (max-width: 480px) {
            .ug-cover h1 {
                font-size: 24px;
            }

            .ug-cover-meta {
                grid-template-columns: 1fr;
            }

            .ug-cover-meta-item {
                border-right: none !important;
            }

            .ug-section-title {
                font-size: 20px;
            }

            .ug-card {
                padding: 20px;
            }

            .ug-support-card {
                padding: 22px 18px;
            }
        }
    </style>

    <!-- Custom Navbar for User Guide -->
    <nav class="ug-navbar">
        <a href="/" class="ug-navbar-brand">
            <img src="{{ asset('meracik-logo1.png') }}" alt="Meracikopi Logo">
            <span>Meracikopi</span>
        </a>
        <div class="ug-navbar-help">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            Help Center
        </div>
    </nav>

    <div class="ug-page">
        <!-- ===================== COVER ===================== -->
        <div class="ug-cover" id="top">
            <div class="ug-cover-badge">User Guide</div>
            <h1>Panduan Pengguna<br><span>Meracikopi</span></h1>
            <p class="ug-cover-subtitle">Dokumentasi lengkap untuk membantu Anda menggunakan platform Meracikopi</p>

            <div class="ug-cover-meta">
                <div class="ug-cover-meta-item">
                    <dt>Aplikasi</dt>
                    <dd>Meracikopi E-Commerce</dd>
                </div>
                <div class="ug-cover-meta-item">
                    <dt>Versi</dt>
                    <dd>1.0.0</dd>
                </div>
                <div class="ug-cover-meta-item">
                    <dt>Rilis</dt>
                    <dd>Februari 2026</dd>
                </div>
                <div class="ug-cover-meta-item">
                    <dt>Pengembang</dt>
                    <dd>Tim Meracikopi</dd>
                </div>
                <div class="ug-cover-meta-item">
                    <dt>Email</dt>
                    <dd>Meracikopi@gmail.com</dd>
                </div>
                <div class="ug-cover-meta-item">
                    <dt>WhatsApp</dt>
                    <dd>+62 517 7112 017</dd>
                </div>
            </div>
        </div>

        <!-- =============== TABLE OF CONTENTS =============== -->
        <nav class="ug-toc" id="ugToc">
            <div class="ug-toc-inner">
                <a href="#pendahuluan">Pendahuluan</a>
                <a href="#kebutuhan">Kebutuhan Sistem</a>
                <a href="#akses">Cara Akses</a>
                <a href="#menu">Struktur Menu</a>
                <a href="#fitur">Fitur Utama</a>
                <a href="#error">Error Handling</a>
                <a href="#support">Kontak Support</a>
            </div>
        </nav>

        <!-- ================ PENDAHULUAN ================ -->
        <section class="ug-section" id="pendahuluan">
            <div class="ug-container">
                <div class="ug-section-header">
                    <span class="ug-section-number">01</span>
                    <h2 class="ug-section-title">Pendahuluan</h2>
                    <div class="ug-section-line"></div>
                </div>

                <div class="ug-card">
                    <div class="ug-card-title">
                        <div class="ug-card-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                        </div>
                        Gambaran Umum
                    </div>
                    <p class="ug-text">
                        <strong>Meracikopi</strong> adalah platform e-commerce yang dirancang khusus untuk memudahkan pelanggan dalam memesan berbagai produk kopi berkualitas tinggi — mulai dari makanan, minuman, biji kopi, kopi botolan, hingga sachet drip — secara online dengan pengalaman yang cepat, aman, dan nyaman.
                    </p>
                </div>

                <div class="ug-card">
                    <div class="ug-card-title">
                        <div class="ug-card-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        </div>
                        Tujuan Aplikasi
                    </div>
                    <p class="ug-text">
                        Aplikasi ini bertujuan untuk menjembatani pelanggan dengan produk-produk Meracikopi secara digital, menyediakan sistem pemesanan yang terintegrasi dengan pembayaran online, pelacakan status pesanan, serta pengantaran yang efisien dan tepat waktu.
                    </p>
                </div>

                <div class="ug-card">
                    <div class="ug-card-title">
                        <div class="ug-card-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        </div>
                        Target Pengguna
                    </div>
                    <p class="ug-text">
                        Aplikasi ini ditujukan untuk seluruh pelanggan Meracikopi, baik pelanggan baru maupun pelanggan setia, yang ingin memesan produk kopi secara online melalui website. Pengguna dapat mengakses aplikasi tanpa batasan usia maupun latar belakang teknis.
                    </p>
                </div>
            </div>
        </section>

        <!-- ============= KEBUTUHAN SISTEM ============= -->
        <section class="ug-section" id="kebutuhan">
            <div class="ug-container">
                <div class="ug-section-header">
                    <span class="ug-section-number">02</span>
                    <h2 class="ug-section-title">Kebutuhan Sistem</h2>
                    <div class="ug-section-line"></div>
                </div>

                <p class="ug-text">Berikut adalah spesifikasi minimum yang diperlukan untuk mengakses platform Meracikopi secara optimal di berbagai perangkat.</p>

                <!-- Web -->
                <h3 class="ug-sub-heading"><span class="ug-dot"></span> Akses via Web Browser</h3>
                <div class="ug-table-wrap">
                    <table class="ug-table">
                        <thead>
                            <tr>
                                <th>Komponen</th>
                                <th>Spesifikasi Minimum</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Browser</td>
                                <td>Google Chrome, Mozilla Firefox, atau Microsoft Edge <span class="ug-badge">Versi Terbaru</span></td>
                            </tr>
                            <tr>
                                <td>Koneksi Internet</td>
                                <td>Stabil (minimal 1 Mbps)</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Mobile -->
                <h3 class="ug-sub-heading"><span class="ug-dot"></span> Akses via Mobile</h3>
                <div class="ug-table-wrap">
                    <table class="ug-table">
                        <thead>
                            <tr>
                                <th>Komponen</th>
                                <th>Spesifikasi Minimum</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Sistem Operasi</td>
                                <td>Android 8.0 (Oreo) atau lebih baru</td>
                            </tr>
                            <tr>
                                <td>RAM</td>
                                <td>Minimal 3 GB</td>
                            </tr>
                            <tr>
                                <td>Penyimpanan</td>
                                <td>Minimal 200 MB tersedia</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Desktop -->
                <h3 class="ug-sub-heading"><span class="ug-dot"></span> Akses via Desktop</h3>
                <div class="ug-table-wrap">
                    <table class="ug-table">
                        <thead>
                            <tr>
                                <th>Komponen</th>
                                <th>Spesifikasi Minimum</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Sistem Operasi</td>
                                <td>Windows 10 atau macOS versi terbaru</td>
                            </tr>
                            <tr>
                                <td>RAM</td>
                                <td>Minimal 4 GB</td>
                            </tr>
                            <tr>
                                <td>Penyimpanan</td>
                                <td>Minimal 500 MB tersedia</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- ============== CARA AKSES SISTEM ============== -->
        <section class="ug-section" id="akses">
            <div class="ug-container">
                <div class="ug-section-header">
                    <span class="ug-section-number">03</span>
                    <h2 class="ug-section-title">Cara Akses Sistem</h2>
                    <div class="ug-section-line"></div>
                </div>

                <!-- Web -->
                <h3 class="ug-sub-heading"><span class="ug-dot"></span> A. Akses via Web</h3>
                <ol class="ug-steps">
                    <li><span>Buka browser favorit Anda (Chrome, Firefox, atau Edge).</span></li>
                    <li><span>Ketikkan alamat <strong>meracikopi.com</strong> pada kolom URL address bar.</span></li>
                    <li><span>Tekan <strong>Enter</strong> dan tunggu hingga halaman utama Meracikopi tampil.</span></li>
                    <li><span>Anda dapat langsung menjelajahi katalog produk atau melakukan <strong>registrasi / login</strong> untuk mulai memesan.</span></li>
                </ol>

                <!-- Mobile -->
                <h3 class="ug-sub-heading"><span class="ug-dot"></span> B. Akses via Mobile</h3>
                <ol class="ug-steps">
                    <li><span>Buka browser bawaan perangkat Anda (Chrome untuk Android, Safari untuk iOS).</span></li>
                    <li><span>Akses alamat <strong>meracikopi.com</strong> melalui address bar.</span></li>
                    <li><span>Untuk pengalaman lebih baik, tap menu browser dan pilih <strong>"Tambahkan ke Layar Utama"</strong> agar website tersimpan sebagai shortcut.</span></li>
                    <li><span>Buka shortcut dari layar utama dan gunakan seperti aplikasi native.</span></li>
                </ol>

                <!-- Desktop -->
                <h3 class="ug-sub-heading"><span class="ug-dot"></span> C. Akses via Desktop</h3>
                <ol class="ug-steps">
                    <li><span>Pastikan perangkat desktop Anda terhubung dengan koneksi internet yang stabil.</span></li>
                    <li><span>Buka browser dan navigasi ke <strong>meracikopi.com</strong>.</span></li>
                    <li><span>Login menggunakan akun yang telah terdaftar, atau daftar akun baru jika belum memiliki akun.</span></li>
                    <li><span>Anda kini siap menjelajahi fitur lengkap Meracikopi dari desktop Anda.</span></li>
                </ol>
            </div>
        </section>

        <!-- ============= STRUKTUR MENU ============= -->
        <section class="ug-section" id="menu">
            <div class="ug-container">
                <div class="ug-section-header">
                    <span class="ug-section-number">04</span>
                    <h2 class="ug-section-title">Struktur Menu & Tampilan Utama</h2>
                    <div class="ug-section-line"></div>
                </div>

                <p class="ug-text">Berikut adalah penjelasan singkat dari setiap menu utama yang tersedia pada platform Meracikopi.</p>

                <div class="ug-menu-item">
                    <div class="ug-menu-icon-box">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                    </div>
                    <div>
                        <p class="ug-menu-name">Home</p>
                        <p class="ug-menu-desc">Halaman utama yang menampilkan informasi tentang Meracikopi, kategori populer, highlight produk, serta fitur-fitur unggulan yang tersedia.</p>
                    </div>
                </div>

                <div class="ug-menu-item">
                    <div class="ug-menu-icon-box">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                    </div>
                    <div>
                        <p class="ug-menu-name">Catalog</p>
                        <p class="ug-menu-desc">Daftar lengkap seluruh produk Meracikopi yang tersedia, dilengkapi filter kategori (Food, Drink, Coffee Beans, Bottled Coffee, Sachet Drip) untuk memudahkan pencarian.</p>
                    </div>
                </div>

                <div class="ug-menu-item">
                    <div class="ug-menu-icon-box">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                    </div>
                    <div>
                        <p class="ug-menu-name">Cart</p>
                        <p class="ug-menu-desc">Halaman keranjang belanja yang menampilkan daftar produk yang telah dipilih, termasuk jumlah, varian, dan total harga sebelum melanjutkan ke proses checkout.</p>
                    </div>
                </div>

                <div class="ug-menu-item">
                    <div class="ug-menu-icon-box">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                    </div>
                    <div>
                        <p class="ug-menu-name">Order History</p>
                        <p class="ug-menu-desc">Riwayat seluruh pesanan yang pernah dilakukan, termasuk informasi status pesanan, detail pembayaran, dan tracking pengiriman.</p>
                    </div>
                </div>

                <div class="ug-menu-item">
                    <div class="ug-menu-icon-box">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </div>
                    <div>
                        <p class="ug-menu-name">Profile & Settings</p>
                        <p class="ug-menu-desc">Halaman pengaturan akun pengguna untuk mengelola informasi profil, mengubah kata sandi, serta mengatur preferensi tampilan dan keamanan akun.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ============= FITUR UTAMA ============= -->
        <section class="ug-section" id="fitur">
            <div class="ug-container">
                <div class="ug-section-header">
                    <span class="ug-section-number">05</span>
                    <h2 class="ug-section-title">Cara Menggunakan Fitur Utama</h2>
                    <div class="ug-section-line"></div>
                </div>

                <!-- Menjelajahi Katalog -->
                <div class="ug-card" style="margin-bottom: 20px;">
                    <div class="ug-card-title">
                        <div class="ug-card-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        </div>
                        Menjelajahi Katalog & Memesan Produk
                    </div>
                    <ol class="ug-steps">
                        <li><span>Buka halaman <strong>Catalog</strong> melalui menu navigasi di bagian atas.</span></li>
                        <li><span>Gunakan <strong>filter kategori</strong> (Food, Drink, Coffee Beans, dll.) untuk menyaring produk sesuai kebutuhan Anda.</span></li>
                        <li><span>Klik pada <strong>kartu produk</strong> untuk melihat detail lengkap, termasuk deskripsi, harga, dan pilihan varian (ukuran / suhu).</span></li>
                        <li><span>Pilih <strong>varian</strong> yang diinginkan dan tentukan <strong>jumlah pesanan</strong>.</span></li>
                        <li><span>Klik tombol <strong>"Tambah ke Keranjang"</strong> untuk menyimpan produk ke dalam keranjang belanja Anda.</span></li>
                    </ol>
                </div>

                <!-- Checkout -->
                <div class="ug-card" style="margin-bottom: 20px;">
                    <div class="ug-card-title">
                        <div class="ug-card-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                        </div>
                        Proses Checkout & Pembayaran
                    </div>
                    <ol class="ug-steps">
                        <li><span>Buka halaman <strong>Cart</strong> dan periksa kembali daftar produk yang akan dipesan.</span></li>
                        <li><span>Klik tombol <strong>"Checkout"</strong> untuk melanjutkan ke halaman pembayaran.</span></li>
                        <li><span>Pilih <strong>tipe pesanan</strong> (Dine-in / Delivery) dan lengkapi informasi yang diperlukan.</span></li>
                        <li><span>Pilih <strong>metode pembayaran</strong> yang tersedia.</span></li>
                        <li><span>Klik <strong>"Bayar Sekarang"</strong> dan ikuti instruksi pembayaran hingga selesai.</span></li>
                        <li><span>Setelah pembayaran berhasil, Anda akan menerima <strong>konfirmasi pesanan</strong> beserta detail order.</span></li>
                    </ol>
                </div>

                <!-- Order History -->
                <div class="ug-card" style="margin-bottom: 20px;">
                    <div class="ug-card-title">
                        <div class="ug-card-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        </div>
                        Melacak Pesanan
                    </div>
                    <ol class="ug-steps">
                        <li><span>Buka halaman <strong>Order History</strong> melalui menu navigasi.</span></li>
                        <li><span>Anda akan melihat daftar seluruh pesanan dengan <strong>status terkini</strong> (Pending, Diproses, Dikirim, Selesai).</span></li>
                        <li><span>Klik pada pesanan tertentu untuk melihat <strong>detail lengkap</strong> termasuk item, harga, dan informasi pengiriman.</span></li>
                    </ol>
                </div>

                <!-- QR Code -->
                <div class="ug-card">
                    <div class="ug-card-title">
                        <div class="ug-card-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
                        </div>
                        Pesan via QR Code (Dine-in)
                    </div>
                    <ol class="ug-steps">
                        <li><span>Saat berada di kedai Meracikopi, cari <strong>QR Code</strong> yang tersedia di meja Anda.</span></li>
                        <li><span>Scan QR Code menggunakan <strong>kamera smartphone</strong> atau aplikasi scanner.</span></li>
                        <li><span>Anda akan diarahkan langsung ke halaman pemesanan dengan <strong>nomor meja otomatis terisi</strong>.</span></li>
                        <li><span>Pilih produk, lakukan checkout, dan pesanan Anda akan diantar langsung ke meja.</span></li>
                    </ol>
                </div>
            </div>
        </section>

        <!-- ============= ERROR HANDLING ============= -->
        <section class="ug-section" id="error">
            <div class="ug-container">
                <div class="ug-section-header">
                    <span class="ug-section-number">06</span>
                    <h2 class="ug-section-title">Error Handling & Troubleshooting</h2>
                    <div class="ug-section-line"></div>
                </div>

                <p class="ug-text">Berikut adalah beberapa permasalahan umum yang mungkin Anda temui beserta solusi yang dapat dilakukan.</p>

                <!-- Koneksi Terputus -->
                <div class="ug-error-card">
                    <div class="ug-error-title">
                        <span class="dot"></span>
                        Koneksi Internet Terputus
                    </div>
                    <p class="ug-error-solution">
                        <strong>Solusi:</strong> Periksa koneksi internet Anda — pastikan Wi-Fi atau data seluler aktif dan stabil. Coba <strong>refresh halaman</strong> (tekan F5 atau tarik layar ke bawah pada mobile). Jika masalah berlanjut, coba ganti jaringan atau restart perangkat Anda.
                    </p>
                </div>

                <!-- Error Sistem -->
                <div class="ug-error-card">
                    <div class="ug-error-title">
                        <span class="dot"></span>
                        Error Sistem / Halaman Tidak Memuat
                    </div>
                    <p class="ug-error-solution">
                        <strong>Solusi:</strong> Coba <strong>clear cache browser</strong> Anda (Ctrl + Shift + Delete), lalu reload halaman. Jika error masih terjadi, tunggu beberapa saat karena kemungkinan server sedang dalam pemeliharaan. Hubungi tim support jika masalah berlangsung lebih dari 30 menit.
                    </p>
                </div>

                <!-- Pembayaran Gagal -->
                <div class="ug-error-card">
                    <div class="ug-error-title">
                        <span class="dot"></span>
                        Pembayaran Gagal
                    </div>
                    <p class="ug-error-solution">
                        <strong>Solusi:</strong> Pastikan saldo atau limit kartu Anda mencukupi. Periksa apakah koneksi internet stabil selama proses pembayaran. Jika pembayaran terpotong namun pesanan tidak terkonfirmasi, <strong>segera hubungi tim support</strong> melalui WhatsApp dengan menyertakan bukti pembayaran.
                    </p>
                </div>
            </div>
        </section>

        <!-- ============= KONTAK SUPPORT ============= -->
        <section class="ug-section" id="support">
            <div class="ug-container">
                <div class="ug-section-header">
                    <span class="ug-section-number">07</span>
                    <h2 class="ug-section-title">Kontak Support</h2>
                    <div class="ug-section-line"></div>
                </div>

                <p class="ug-text">Jika Anda memerlukan bantuan lebih lanjut, jangan ragu untuk menghubungi tim support kami melalui salah satu kanal berikut.</p>

                <div class="ug-support-grid">
                    <!-- Email -->
                    <div class="ug-support-card">
                        <div class="ug-support-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        </div>
                        <p class="ug-support-label">Email</p>
                        <p class="ug-support-value">Meracikopi@gmail.com</p>
                    </div>

                    <!-- WhatsApp -->
                    <div class="ug-support-card">
                        <div class="ug-support-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        </div>
                        <p class="ug-support-label">WhatsApp</p>
                        <p class="ug-support-value">+62 517 7112 017</p>
                    </div>

                    <!-- Jam Operasional -->
                    <div class="ug-support-card">
                        <div class="ug-support-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        </div>
                        <p class="ug-support-label">Jam Operasional</p>
                        <p class="ug-support-value">Senin – Minggu<br>11.00 – 23.00 WITA</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ============= FOOTER COPYRIGHT ============= -->
        <div class="ug-footer-copy">
            <p>&copy; 2026 Meracikopi. All right reserved.</p>
        </div>

        <!-- Back to Top Button -->
        <button class="ug-back-top" id="ugBackTop" onclick="window.scrollTo({top: 0, behavior: 'smooth'})" title="Kembali ke atas">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="18 15 12 9 6 15"/>
            </svg>
        </button>
    </div>

    <script>
        // Back to top button visibility
        (function () {
            const btn = document.getElementById('ugBackTop');
            if (!btn) return;
            window.addEventListener('scroll', function () {
                if (window.scrollY > 400) {
                    btn.classList.add('show');
                } else {
                    btn.classList.remove('show');
                }
            });
        })();
    </script>
</x-customer.layout>
