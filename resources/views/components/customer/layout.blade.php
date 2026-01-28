<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Meracikopi' }}</title>

    <!-- Fonts - Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            overscroll-behavior: none;
            overflow-x: hidden;
            overflow-y: scroll;
            height: 100%;
            -webkit-overflow-scrolling: touch;
        }

        body {
            overflow-x: hidden;
            overflow-y: auto;
            min-height: 100%;
            height: auto;
            -webkit-overflow-scrolling: touch;
        }

        :root {
            --primary: #34231c;
            --secondary: #CA7842;
            --accent: #B2CD9C;
            --background: #1a1410;
        }

        /* ========== RESPONSIVE UTILITIES ========== */

        /* Hide on mobile (below 768px) */
        @media (max-width: 767px) {
            .hide-mobile {
                display: none !important;
            }
        }

        /* Hide on desktop (768px and above) */
        @media (min-width: 768px) {
            .hide-desktop {
                display: none !important;
            }
        }

        /* ========== MOBILE MENU STYLES ========== */
        /* Mobile menu styles are defined in navbar component */
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            padding: 8px;
            color: white;
            z-index: 1001;
        }

        @media (max-width: 900px) {
            .mobile-menu-btn {
                display: block;
            }
        }

        /* ========== RESPONSIVE SECTIONS ========== */

        /* Hero Section */
        @media (max-width: 900px) {
            .hero-container {
                flex-direction: column !important;
                padding: 30px 20px !important;
                gap: 40px !important;
            }

            .hero-container>div {
                flex: none !important;
                width: 100% !important;
            }

            .hero-title {
                font-size: 32px !important;
            }

            .hero-subtitle {
                font-size: 16px !important;
            }

            .hero-image-grid {
                max-width: 100% !important;
                display: block !important;
                position: relative !important;
            }

            .hero-ornament {
                display: none !important;
            }

            .hero-decorative-lines {
                display: none !important;
            }
        }

        @media (max-width: 480px) {
            .hero-title {
                font-size: 26px !important;
            }

            .hero-subtitle {
                font-size: 14px !important;
            }
        }

        /* Features Section */
        @media (max-width: 900px) {
            .features-section {
                padding: 40px 20px !important;
            }

            .features-grid {
                grid-template-columns: 1fr !important;
                gap: 40px !important;
            }

            .feature-title {
                font-size: 20px !important;
            }

            .feature-description {
                font-size: 14px !important;
            }
        }

        /* Categories Section */
        @media (max-width: 900px) {
            .categories-section {
                padding: 60px 20px !important;
            }

            .categories-grid {
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 20px !important;
            }

            .category-card {
                padding: 24px !important;
            }

            .category-image {
                width: 100px !important;
                height: 100px !important;
                margin-bottom: 16px !important;
            }

            .category-title {
                font-size: 20px !important;
            }
        }

        @media (max-width: 480px) {
            .categories-grid {
                grid-template-columns: 1fr !important;
            }

            .category-image {
                width: 120px !important;
                height: 120px !important;
            }
        }

        /* Bento/Highlight Section */
        @media (max-width: 900px) {
            .bento-section {
                padding: 0 20px 60px 20px !important;
            }

            .bento-grid {
                grid-template-columns: 1fr !important;
                height: auto !important;
            }

            .bento-main-card {
                min-height: 300px !important;
            }

            .bento-stack {
                flex-direction: row !important;
            }

            .bento-stack-card {
                min-height: 200px !important;
            }

            .bento-main-title {
                font-size: 28px !important;
            }

            .bento-stack-title {
                font-size: 20px !important;
            }
        }

        @media (max-width: 600px) {
            .bento-stack {
                flex-direction: column !important;
            }
        }

        /* Footer - Tablet */
        @media (max-width: 900px) {
            .footer-container {
                padding: 50px 30px 40px 30px !important;
            }

            .footer-grid {
                grid-template-columns: 1fr 1fr !important;
                gap: 30px !important;
            }

            .footer-brand {
                grid-column: 1 / -1;
                text-align: center;
            }

            .footer-brand-inner {
                justify-content: center !important;
            }

            .footer-social {
                justify-content: center !important;
            }

            .footer-section {
                text-align: center;
            }

            .footer-section h4 {
                font-size: 16px !important;
            }
        }

        /* Footer - Mobile */
        @media (max-width: 768px) {
            .footer-container {
                padding: 40px 20px 30px 20px !important;
            }

            .footer-grid {
                grid-template-columns: 1fr !important;
                gap: 24px !important;
            }

            .footer-brand {
                text-align: center;
            }

            .footer-brand-inner {
                flex-direction: column !important;
                justify-content: center !important;
                gap: 10px !important;
            }

            .footer-social {
                justify-content: center !important;
            }

            .footer-section h4 {
                font-size: 14px !important;
            }
        }

        /* Footer - Small Mobile */
        @media (max-width: 480px) {
            .footer-container {
                padding: 30px 16px 24px 16px !important;
            }

            .footer-grid {
                gap: 20px !important;
            }

            .footer-section h4 {
                font-size: 13px !important;
            }
        }

        /* Announcement Bar */
        @media (max-width: 600px) {
            .announcement-bar {
                padding: 10px 16px !important;
            }

            .announcement-text {
                font-size: 10px !important;
                letter-spacing: 0.1em !important;
            }

            .announcement-divider {
                display: none !important;
            }
        }

        /* Navbar */
        @media (max-width: 900px) {
            .navbar-main {
                padding: 12px 20px !important;
            }

            .navbar-center-menu {
                display: none !important;
            }

            .navbar-search {
                display: none !important;
            }
        }
    </style>
</head>

<body style="font-family: 'Poppins', sans-serif; background-color: #1a1410; margin: 0;">
    <!-- Navbar -->
    @include('components.customer.navbar')

    <!-- Main Content -->
    <main>
        {{ $slot }}
    </main>

    <!-- Footer -->
    @include('components.customer.footer')
</body>

</html>