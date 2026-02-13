<x-customer.layout title="Meracikopi - Kopi Berkualitas">
    <style>
        /* Hero Image Card Hover Effects */
        .hero-image-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: default;
        }

        .hero-image-card:hover {
            transform: scale(1.05);
            box-shadow: 0 20px 50px rgba(202, 120, 66, 0.3), 0 10px 30px rgba(0, 0, 0, 0.4) !important;
            border-color: rgba(202, 120, 66, 0.4) !important;
        }

        .hero-image-card:hover .hero-card-image {
            transform: scale(1.1);
        }

        .hero-card-image {
            transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Category Card Hover Effects */
        .category-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1) !important;
            border-radius: 24px;
            box-shadow: none !important;
            -webkit-tap-highlight-color: transparent;
            text-decoration: none;
            overflow: hidden;
            background: transparent !important;
            outline: none !important;
        }

        .category-card:hover {
            transform: scale(1.05) !important;
            z-index: 10;
            box-shadow: none !important;
        }

        .category-card:hover .category-card-inner {
            border: 2px solid #CA7842 !important;
            box-shadow: none !important;
        }

        .category-card-inner {
            box-shadow: none !important;
            transition: all 0.4s ease;
            height: 100%;
            border-radius: 24px !important; 
            overflow: hidden;
            border: 2px solid transparent !important;
            box-sizing: border-box;
            outline: none !important;
        }

        /* Mobile - Hover shadow exactly matching card edges */
        @media (max-width: 768px) {
            .category-card:hover {
                box-shadow: none !important;
                transform: scale(1.01) !important;
            }
        }
    </style>

    <!-- Hero Section -->
    <div class="hero-section" style="background-color: #1a1410; min-height: 80vh; padding: 40px 80px;">
        <div class="hero-container"
            style="max-width: 1400px; margin: 0 auto; display: flex; align-items: center; gap: 60px;">

            <!-- Left: Text Content -->
            <div style="flex: 1;">
                <h1 class="hero-title"
                    style="font-size: 48px; font-weight: 700; line-height: 1.2; margin-bottom: 24px;">
                    <span style="color: white; font-weight: 700;">Nikmati </span>
                    <span style="position: relative; display: inline-block;">
                        <span style="color: #CA7842; font-style: italic;">Kopi</span>
                        <!-- Ornament -->
                        <!-- Ornament Desktop -->
                        <svg class="kopi-underline desktop-only"
                            style="position: absolute; bottom: -8px; left: -5%; width: 110%; height: 12px; transform: rotate(-2deg);"
                            viewBox="0 0 100 15" fill="none" preserveAspectRatio="none">
                            <path d="M2 10C20 15 50 15 98 2" stroke="#F8F5F2" stroke-width="5" stroke-linecap="round" />
                        </svg>

                    </span>
                    <br>
                    <span style="color: white; font-weight: 700;">Berkualitas, Diracik</span>
                    <br>
                    <span style="color: white; font-weight: 700;">Sepenuh Hati </span>
                    <!-- Coffee Icon SVG -->
                    <span style="display: inline-block; vertical-align: middle; margin-left: 8px;">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="white" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M18.5 4H6C4.9 4 4 4.9 4 6V14C4 16.2 5.8 18 8 18H14C16.2 18 18 16.2 18 14V11H18.5C19.9 11 21 9.9 21 8.5C21 7.1 19.9 6 18.5 6H18V4H18.5ZM18 9H18.5C18.8 9 19 8.8 19 8.5C19 8.2 18.8 8 18.5 8H18V9ZM15 2H17V0H15V2ZM11 2H13V0H11V2ZM7 2H9V0H7V2Z" />
                        </svg>
                    </span>
                </h1>
                <p class="hero-subtitle"
                    style="color: #a89890; font-size: 18px; line-height: 1.6; margin-bottom: 40px; max-width: 500px;">
                    Kopi dengan cita rasa autentik, diseduh dari biji terbaik untuk menemani setiap momenmu
                </p>
                <style>
                    /* Animation for the wavy lines drawing */
                    @keyframes drawLines {
                        to {
                            stroke-dashoffset: 0;
                        }
                    }

                    .cta-button-custom {
                        display: inline-flex;
                        align-items: center;
                        gap: 12px;
                        padding: 14px 28px;
                        background-color: #6F4E37;
                        color: #FFF8F0;
                        text-decoration: none;
                        border-radius: 12px;
                        font-weight: 600;
                        font-size: 15px;
                        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
                        border: 1px solid rgba(255, 255, 255, 0.1);
                        position: relative;
                        /* Removed overflow: hidden to allow star to extend out */
                    }

                    .cta-button-custom:hover {
                        background-color: #8B5E3C;
                        transform: translateY(-2px) scale(1.02);
                        box-shadow: 0 8px 25px rgba(111, 78, 55, 0.4);
                        border-color: rgba(255, 255, 255, 0.3);
                        color: #FFFFFF;
                    }

                    .cta-button-custom svg {
                        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                    }

                    .cta-button-custom:hover svg {
                        transform: translateX(3px) rotate(-10deg);
                    }

                    .cta-button-custom::after {
                        content: '';
                        position: absolute;
                        top: 0;
                        left: -100%;
                        width: 100%;
                        height: 100%;
                        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
                        transition: 0.5s;
                        /* Ensure sheen is clipped strictly to button, not impacting the SVG outside */
                        pointer-events: none;
                    }

                    /* Bento Grid Hover Effects */
                    .highlight-card {
                        border-radius: 32px;
                        overflow: hidden;
                        position: relative;
                        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
                        border: 1px solid rgba(255, 255, 255, 0.05);
                        transition: transform 0.5s cubic-bezier(0.25, 1, 0.5, 1);
                    }

                    .highlight-card:hover {
                        transform: translateY(-5px) scale(1.01);
                        box-shadow: 0 30px 50px rgba(0, 0, 0, 0.5);
                    }

                    .highlight-bg {
                        transition: transform 0.7s ease;
                    }

                    .highlight-card:hover .highlight-bg {
                        transform: scale(1.1);
                    }

                    .highlight-btn {
                        background-color: rgb(38, 24, 16);
                        transition: all 0.3s ease;
                        transform-origin: center;
                    }

                    .highlight-btn:hover {
                        background-color: #3A281E !important;
                        /* Force override inline if needed, though we will remove inline */
                        transform: scale(1.05);
                        padding-left: 32px;
                        /* Subtle expansion */
                        padding-right: 32px;
                    }

                    /* Mobile Responsive Styles */
                    @media (max-width: 768px) {
                        .hero-container {
                            flex-direction: column !important;
                            padding: 30px 20px !important;
                            gap: 40px !important;
                        }

                        /* Reorder: Images first, then text */
                        .hero-container>div:first-child {
                            order: 2;
                        }

                        .hero-image-grid {
                            order: 1;
                        }

                        .hero-title {
                            font-size: 32px !important;
                            text-align: center;
                            line-height: 1.2 !important;
                        }

                        .hero-title svg {
                            width: 32px !important;
                            height: 32px !important;
                        }

                        .hero-subtitle {
                            font-size: 15px !important;
                            text-align: center;
                            max-width: 100% !important;
                            line-height: 1.5 !important;
                        }

                        .cta-button-custom {
                            width: auto !important;
                            justify-content: center;
                            font-size: 14px !important;
                            padding: 12px 32px !important;
                            margin: 0 auto;
                        }

                        .hero-cta-container {
                            display: block !important;
                            width: 100% !important;
                            text-align: center !important;
                            margin-top: 10px;
                        }

                        .hero-decorative-lines {
                            display: none !important;
                        }

                        .hero-image-grid {
                            max-width: 400px;
                            margin: 0 auto;
                            margin-bottom: 20px;
                        }

                        .hero-ornament {
                            display: none !important;
                        }

                        /* SVG Switching for Mobile */
                        .desktop-only {
                            display: none !important;
                        }

                        .mobile-only {
                            display: block !important;
                        }
                    }

                    @media (max-width: 480px) {
                        .hero-title {
                            font-size: 26px !important;
                            margin-bottom: 16px !important;
                            line-height: 1.2 !important;
                        }

                        .hero-title svg {
                            width: 28px !important;
                            height: 28px !important;
                        }

                        .hero-subtitle {
                            font-size: 14px !important;
                            margin-bottom: 30px !important;
                            line-height: 1.6 !important;
                        }

                        .cta-button-custom {
                            font-size: 13px !important;
                            padding: 10px 20px !important;
                        }

                        .hero-image-grid {
                            max-width: 300px;
                        }

                        .hero-section {
                            padding: 30px 16px !important;
                            min-height: auto !important;
                        }

                    }
                </style>
                <div class="hero-cta-container" style="position: relative; display: inline-block;">
                    <!-- Main CTA Button -->
                    <a href="{{ url('/customer/catalogs') }}" class="cta-button-custom" style="overflow: hidden;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="9" cy="21" r="1"></circle>
                            <circle cx="20" cy="21" r="1"></circle>
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                        </svg>
                        Cari Kopi Favoritmu
                    </a>

                    <!-- Decorative Star Only -->
                    <div class="hero-decorative-lines"
                        style="position: absolute; top: 100%; left: 40px; pointer-events: none; z-index: 50;">
                        <svg width="250" height="350" viewBox="0 0 250 350" fill="none"
                            style="filter: drop-shadow(0 0 8px rgba(255,255,255,0.2));">
                            <!-- Star only -->
                            <path d="M0 -10 L2.3 -3.2 H9.5 L3.7 1 L5.9 7.8 L0 4.2 L-5.9 7.8 L-3.7 1 L-9.5 -3.2 H-2.3 Z"
                                fill="white" stroke="white" stroke-width="2" stroke-linejoin="round"
                                transform="translate(170, 295) scale(2.5)" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Right: Image Grid -->
            <div class="hero-image-grid" style="flex: 1; position: relative;">
                <!-- Decorative Elements -->

                <!-- Top Right: Coffee Bean Ornament -->
                <div class="hero-ornament" style="position: absolute; top: -40px; right: -40px; z-index: 0;">
                    <div style="
                        width: 100px;
                        height: 100px;
                        background: #231812;
                        border-radius: 50%;
                        display: flex;
                        align-items: flex-start;
                        justify-content: flex-end;
                        padding: 18px;
                        position: relative;
                    ">
                        <!-- Orbiting small particle -->
                        <div
                            style="position: absolute; top: 18px; right: 18px; width: 5px; height: 5px; background: #CA7842; border-radius: 50%; opacity: 0.6;">
                        </div>

                        <svg width="45" height="45" viewBox="0 0 24 24" fill="none" stroke="#CA7842" stroke-width="1.2"
                            style="transform: rotate(-15deg);">
                            <path
                                d="M12 3C7.02944 3 3 7.02944 3 12C3 16.9706 7.02944 21 12 21C16.9706 21 21 16.9706 21 12C21 7.02944 16.9706 3 12 3Z" />
                            <path d="M12 3C12 3 7.5 9 12 12C16.5 15 12 21 12 21" stroke-linecap="round" />
                            <path d="M17 8L16 9" stroke-linecap="round" />
                            <path d="M7 16L8 15" stroke-linecap="round" />
                        </svg>
                    </div>
                </div>

                <!-- Middle Right: Smoking Cup Ornament -->
                <div class="hero-ornament" style="position: absolute; top: 45%; right: -60px; z-index: 0;">
                    <div style="
                        width: 70px;
                        height: 70px;
                        background: #2C1E16;
                        border-radius: 50%;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                     ">
                        <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#8C7B70" stroke-width="1.5"
                            style="transform: rotate(15deg);">
                            <path d="M18 8h1a4 4 0 0 1 0 8h-1" />
                            <path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z" />
                            <path d="M6 1v3" />
                            <path d="M10 1v3" />
                            <path d="M14 1v3" />
                        </svg>
                    </div>
                </div>

                <!-- Bottom Left: Go-Cup Ornament -->
                <div class="hero-ornament" style="position: absolute; bottom: -40px; left: -40px; z-index: 0;">
                    <div style="
                        width: 100px;
                        height: 100px;
                        background: #3A281E;
                        border-radius: 50%;
                        display: flex;
                        align-items: flex-end;
                        justify-content: flex-start;
                        padding: 22px;
                        position: relative;
                        overflow: hidden;
                    ">
                        <!-- Abstract Background Circle -->
                        <div
                            style="position: absolute; bottom: -10px; left: -10px; width: 60px; height: 60px; background: rgba(0,0,0,0.1); border-radius: 50%;">
                        </div>

                        <svg width="45" height="45" viewBox="0 0 24 24" fill="none" stroke="#F8F5F2" stroke-width="1.2"
                            style="transform: rotate(-10deg);">
                            <path d="M17 8v11a2 2 0 0 1-2 2H9a2 2 0 0 1-2-2V8" />
                            <path d="M5 4h14" />
                            <path d="M6 4l1 4h10l1-4" />
                            <path d="M6 4L7 2h10l1 2" />
                        </svg>
                    </div>
                </div>

                <!-- Main Grid -->
                <div
                    style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; position: relative; z-index: 1;">
                    <!-- Image 1 (Top Left - Leaf Shape) -->
                    <div class="hero-image-card" style="
                        aspect-ratio: 1;
                        background: linear-gradient(135deg, #2a1f1a, #3a2f2a);
                        border-radius: 100px 20px 20px 20px;
                        overflow: hidden;
                        position: relative;
                        border: 1px solid rgba(255,255,255,0.05);
                    ">
                        <!-- Placeholder Content -->
                        <div class="hero-card-image"
                            style="width: 100%; height: 100%; background: url('https://images.unsplash.com/photo-1559496417-e7f25cb247f3?w=500&q=80') center/cover;">
                        </div>
                    </div>

                    <!-- Image 2 (Top Right - Standard) -->
                    <div class="hero-image-card" style="
                        aspect-ratio: 1;
                        background: linear-gradient(135deg, #2a1f1a, #3a2f2a);
                        border-radius: 20px;
                        overflow: hidden;
                        position: relative;
                        border: 1px solid rgba(255,255,255,0.05);
                    ">
                        <!-- Placeholder Content -->
                        <div class="hero-card-image"
                            style="width: 100%; height: 100%; background: url('https://images.unsplash.com/photo-1497935586351-b67a49e012bf?w=500&q=80') center/cover;">
                        </div>
                    </div>

                    <!-- Image 3 (Bottom Left - Standard) -->
                    <div class="hero-image-card" style="
                        aspect-ratio: 1;
                        background: linear-gradient(135deg, #2a1f1a, #3a2f2a);
                        border-radius: 20px;
                        overflow: hidden;
                        position: relative;
                        border: 1px solid rgba(255,255,255,0.05);
                    ">
                        <!-- Placeholder Content -->
                        <div class="hero-card-image"
                            style="width: 100%; height: 100%; background: url('https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=500&q=80') center/cover;">
                        </div>
                    </div>

                    <!-- Image 4 (Bottom Right - Inverse Leaf Shape) -->
                    <div class="hero-image-card" style="
                        aspect-ratio: 1;
                        background: linear-gradient(135deg, #2a1f1a, #3a2f2a);
                        border-radius: 20px 20px 100px 20px;
                        overflow: hidden;
                        position: relative;
                        border: 1px solid rgba(255,255,255,0.05);
                    ">
                        <!-- Placeholder Content -->
                        <div class="hero-card-image"
                            style="width: 100%; height: 100%; background: url('https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=500&q=80') center/cover;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="features-section"
        style="background-color: #1a1410; padding: 40px 80px 80px 80px; position: relative; z-index: 5; margin-top: 50px;">
        <div style="max-width: 1400px; margin: 0 auto;">
            <div class="features-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 60px;">
                <!-- Feature 1: Quality (Icon replaced by falling star) -->
                <div style="text-align: center;">
                    <div
                        style="height: 60px; margin-bottom: 24px; display: flex; align-items: center; justify-content: center;">
                        <!-- Static Star for Mobile (Chunky/Rounded) -->
                        <svg class="mobile-only" style="display: none;" width="50" height="50" viewBox="0 0 24 24"
                            fill="white">
                            <path
                                d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"
                                stroke="white" stroke-width="2.5" stroke-linejoin="round" stroke-linecap="round" />
                        </svg>
                    </div>
                    <h3 class="feature-title"
                        style="color: white; font-size: 24px; font-weight: 600; margin-bottom: 16px;">Quality</h3>
                    <p class="feature-description"
                        style="color: #a89890; font-size: 15px; line-height: 1.8; max-width: 300px; margin: 0 auto;">
                        Kami menggunakan biji kopi pilihan dari petani terbaik, diseleksi dengan standar kualitas tinggi
                        untuk menghadirkan cita rasa yang konsisten di setiap seduhan
                    </p>
                </div>

                <!-- Feature 2: Service -->
                <div style="text-align: center;">
                    <div style="margin-bottom: 24px; display: flex; justify-content: center;">
                        <svg width="60" height="60" viewBox="0 0 24 24" fill="white" xmlns="http://www.w3.org/2000/svg">
                            <!-- Cloche/Service Bell Shape -->
                            <path
                                d="M12 2C13.1 2 14 2.9 14 4C14 4.3 13.9 4.6 13.8 4.9C17.3 5.4 20 8.4 20 12H4C4 8.4 6.7 5.4 10.2 4.9C10.1 4.6 10 4.3 10 4C10 2.9 10.9 2 12 2Z"
                                fill="white" />
                            <rect x="2" y="14" width="20" height="4" rx="2" fill="white" />
                        </svg>
                    </div>
                    <h3 class="feature-title"
                        style="color: white; font-size: 24px; font-weight: 600; margin-bottom: 16px;">Service</h3>
                    <p class="feature-description"
                        style="color: #a89890; font-size: 15px; line-height: 1.8; max-width: 300px; margin: 0 auto;">
                        Setiap kopi diracik oleh barista berpengalaman dengan teknik penyeduhan yang tepat, memastikan
                        aroma, rasa, dan kualitas terbaik dalam setiap cangkir
                    </p>
                </div>

                <!-- Feature 3: Delivery -->
                <div style="text-align: center;">
                    <div style="margin-bottom: 24px; display: flex; justify-content: center;">
                        <svg width="60" height="60" viewBox="0 0 24 24" fill="white" xmlns="http://www.w3.org/2000/svg">
                            <rect x="1" y="3" width="15" height="13" rx="2" fill="white" />
                            <path d="M16 8H19L22 11V16H16V8Z" fill="white" />
                            <circle cx="5.5" cy="18.5" r="2.5" fill="white" />
                            <circle cx="18.5" cy="18.5" r="2.5" fill="white" />
                        </svg>
                    </div>
                    <h3 class="feature-title"
                        style="color: white; font-size: 24px; font-weight: 600; margin-bottom: 16px;">Delivery</h3>
                    <p class="feature-description"
                        style="color: #a89890; font-size: 15px; line-height: 1.8; max-width: 300px; margin: 0 auto;">
                        Nikmati kopi favoritmu tanpa harus datang ke kedai. Kami siap melayani pemesanan dan pengantaran
                        dengan cepat, aman, dan tepat waktu
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Popular Categories Section -->
    <div class="categories-section" style="background-color: #120c09; padding: 80px 80px;">
        <div style="max-width: 1200px; margin: 0 auto;">
            <!-- Section Header -->
            <div style="text-align: center; margin-bottom: 60px;">
                <h2 style="color: white; font-size: 36px; font-weight: 700; margin-bottom: 8px;">Kategori Populer</h2>
                <p style="color: #A89890; font-size: 16px; font-weight: 400;">Pilihan Terfavorit Bulan Ini</p>
            </div>

            <!-- Categories Grid -->
            <div class="categories-grid" style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 30px;">
                <!-- Category 1: Food -->
                <a href="{{ url('/customer/catalogs?category=food') }}" class="category-card"
                    style="text-decoration: none; display: block;">
                    <div class="category-card-inner" style="
                        background: linear-gradient(to top, #603e2a, #261810);
                        border-radius: 24px;
                        padding: 12px;
                        text-align: center;
                        height: 100%;
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                        justify-content: center;
                        overflow: hidden;
                    ">
                        <!-- Image Container (Squircle) -->
                        <div style="
                            width: 160px;
                            height: 160px;
                            margin-bottom: 15px;
                            border-radius: 40px;
                            overflow: hidden;
                        ">
                            <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=500&q=80" alt="Food"
                                style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <h3 class="category-title" style="color: white; font-size: 26px; font-weight: 700; margin: 0;">
                            Food</h3>
                    </div>
                </a>

                <!-- Category 2: Drink -->
                <a href="{{ url('/customer/catalogs?category=drink') }}" class="category-card"
                    style="text-decoration: none; display: block;">
                    <div class="category-card-inner" style="
                        background: linear-gradient(to top, #603e2a, #261810);
                        border-radius: 24px;
                        padding: 12px;
                        text-align: center;
                        height: 100%;
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                        justify-content: center;
                        overflow: hidden;
                    ">
                        <!-- Image Container (Squircle) -->
                        <div style="
                            width: 160px;
                            height: 160px;
                            margin-bottom: 15px;
                            border-radius: 40px;
                            overflow: hidden;
                        ">
                            <img src="https://images.unsplash.com/photo-1559496417-e7f25cb247f3?w=500&q=80" alt="Drink"
                                style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <h3 class="category-title" style="color: white; font-size: 26px; font-weight: 700; margin: 0;">
                            Drink</h3>
                    </div>
                </a>

                <!-- Category 3: Coffee Beans -->
                <a href="{{ url('/customer/catalogs?category=coffee-beans') }}" class="category-card"
                    style="text-decoration: none; display: block;">
                    <div class="category-card-inner" style="
                        background: linear-gradient(to top, #603e2a, #261810);
                        border-radius: 24px;
                        padding: 12px;
                        text-align: center;
                        height: 100%;
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                        justify-content: center;
                        overflow: hidden;
                    ">
                        <!-- Image Container (Squircle) -->
                        <div style="
                            width: 160px;
                            height: 160px;
                            margin-bottom: 15px;
                            border-radius: 40px;
                            overflow: hidden;
                        ">
                            <img src="https://images.unsplash.com/photo-1447933601403-0c6688de566e?w=500&q=80"
                                alt="Coffee Beans" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <h3 class="category-title" style="color: white; font-size: 26px; font-weight: 700; margin: 0;">
                            Coffee Beans</h3>
                    </div>
                </a>

                <!-- Category 4: Bottled Coffee -->
                <a href="{{ url('/customer/catalogs?category=bottled_coffee') }}" class="category-card"
                    style="text-decoration: none; display: block;">
                    <div class="category-card-inner" style="
                        background: linear-gradient(to top, #603e2a, #261810);
                        border-radius: 24px;
                        padding: 12px;
                        text-align: center;
                        height: 100%;
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                        justify-content: center;
                        overflow: hidden;
                    ">
                        <!-- Image Container (Squircle) -->
                        <div style="
                            width: 160px;
                            height: 160px;
                            margin-bottom: 15px;
                            border-radius: 40px;
                            overflow: hidden;
                        ">
                            <img src="{{ asset('images/categories/category-kopi-botolan.jpg') }}" alt="Bottled Coffee"
                                style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <h3 class="category-title" style="color: white; font-size: 26px; font-weight: 700; margin: 0;">
                            Bottled Coffee</h3>
                    </div>
                </a>

                <!-- Category 5: Sachet Drip -->
                <a href="{{ url('/customer/catalogs?category=sachet_drip') }}" class="category-card"
                    style="text-decoration: none; display: block;">
                    <div class="category-card-inner" style="
                        background: linear-gradient(to top, #603e2a, #261810);
                        border-radius: 24px;
                        padding: 12px;
                        text-align: center;
                        height: 100%;
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                        justify-content: center;
                        overflow: hidden;
                    ">
                        <!-- Image Container (Squircle) -->
                        <div style="
                            width: 160px;
                            height: 160px;
                            margin-bottom: 15px;
                            border-radius: 40px;
                            overflow: hidden;
                        ">
                            <img src="https://images.unsplash.com/photo-1559056199-641a0ac8b55e?w=500&q=80"
                                alt="Sachet Drip" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <h3 class="category-title" style="color: white; font-size: 26px; font-weight: 700; margin: 0;">
                            Sachet Drip</h3>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Product Highlight Section (Bento Grid) -->
    <div class="bento-section" style="background-color: #120c09; padding: 0 80px 80px 80px;">
        <div class="bento-grid"
            style="max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: 1.3fr 1fr; gap: 30px; height: 500px;">
            <!-- Left: Large Coffee Beans Card -->
            <div class="highlight-card bento-main-card">
                <!-- Background Image -->
                <div class="highlight-bg" style="
                    position: absolute; inset: 0;
                    background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1498804103079-a6351b050096?w=800&q=80');
                    background-size: cover;
                    background-position: center;
                "></div>

                <!-- Content -->
                <div
                    style="position: absolute; inset: 0; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; padding: 40px;">
                    <h2 class="bento-main-title"
                        style="color: #fff4d6; font-size: 42px; font-weight: 700; line-height: 1.2; margin-bottom: 30px; max-width: 400px;">
                        Lihat biji kopi yang kami jual
                    </h2>
                    <a href="{{ url('/customer/catalogs?category=coffee_beans') }}" class="highlight-btn" style="
                        color: #fff4d6;
                        padding: 14px 28px;
                        border-radius: 50px;
                        text-decoration: none;
                        font-weight: 600;
                        display: inline-flex;
                        align-items: center;
                        gap: 10px;
                    ">
                        Jelajahi produk kami
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14M12 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Right: Stacked Food & Drink -->
            <div class="bento-stack" style="display: flex; flex-direction: column; gap: 30px;">
                <!-- Top: Food -->
                <div class="highlight-card bento-stack-card" style="flex: 1;">
                    <div class="highlight-bg" style="
                        position: absolute; inset: 0;
                        background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=800&q=80');
                        background-size: cover;
                        background-position: center;
                    "></div>
                    <div
                        style="position: absolute; inset: 0; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; padding: 20px;">
                        <h3 class="bento-stack-title"
                            style="color: #fff4d6; font-size: 28px; font-weight: 700; margin-bottom: 20px;">
                            Lihat makanan yang kami jual
                        </h3>
                        <a href="{{ url('/customer/catalogs?category=food') }}" class="highlight-btn" style="
                            color: #fff4d6;
                            padding: 10px 24px;
                            border-radius: 50px;
                            text-decoration: none;
                            font-size: 14px;
                            font-weight: 600;
                            display: inline-flex;
                            align-items: center;
                            gap: 8px;
                        ">
                            Jelajahi produk kami
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12h14M12 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Bottom: Drink -->
                <div class="highlight-card bento-stack-card" style="flex: 1;">
                    <div class="highlight-bg" style="
                        position: absolute; inset: 0;
                        background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1509042239860-f550ce710b93?w=800&q=80');
                        background-size: cover;
                        background-position: center;
                    "></div>
                    <div
                        style="position: absolute; inset: 0; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; padding: 20px;">
                        <h3 class="bento-stack-title"
                            style="color: #fff4d6; font-size: 28px; font-weight: 700; margin-bottom: 20px;">
                            Lihat minuman yang kami jual
                        </h3>
                        <a href="{{ url('/customer/catalogs?category=drink') }}" class="highlight-btn" style="
                            color: #fff4d6;
                            padding: 10px 24px;
                            border-radius: 50px;
                            text-decoration: none;
                            font-size: 14px;
                            font-weight: 600;
                            display: inline-flex;
                            align-items: center;
                            gap: 8px;
                        ">
                            Jelajahi produk kami
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12h14M12 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-customer.layout>