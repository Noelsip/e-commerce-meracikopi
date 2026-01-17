<style>
    /* Footer Social Icons Hover */
    .footer-social a {
        color: rgba(255, 255, 255, 0.7) !important;
        transition: color 0.3s ease;
    }

    .footer-social a:hover {
        color: #ffffff !important;
    }

    @media (max-width: 768px) {
        .footer-container {
            padding: 40px 20px 30px 20px !important;
        }

        .footer-grid {
            grid-template-columns: 1fr !important;
            gap: 30px !important;
            text-align: center;
        }

        .footer-brand-inner {
            flex-direction: column !important;
            gap: 12px !important;
        }

        .footer-social {
            justify-content: center !important;
        }

        .footer-section h4 {
            font-size: 16px !important;
            margin-bottom: 16px !important;
        }

        .footer-section ul,
        .footer-section>div {
            align-items: center;
        }
    }
</style>

<footer class="footer-container"
    style="background-color: #231812; padding: 80px 80px 40px 80px; color: white; border-top: 1px solid rgba(255,255,255,0.05);">
    <div style="max-width: 1200px; margin: 0 auto;">
        <div class="footer-grid"
            style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 60px; margin-bottom: 60px;">
            <!-- Brand Column -->
            <div class="footer-brand">
                <div class="footer-brand-inner"
                    style="display: flex; align-items: center; gap: 20px; margin-bottom: 20px;">
                    <!-- Logo Placeholder -->
                    <div style="width: 60px; height: 60px; background-color: white; border-radius: 50%;"></div>
                    <div>
                        <h3 style="font-size: 24px; font-weight: 700; margin: 0;">Meracikopi</h3>
                        <p style="font-size: 14px; font-weight: 300; color: #a89890; margin: 0;">Coffe Space & Roastery
                        </p>
                    </div>
                </div>
                <!-- Social Icons -->
                <div class="footer-social" style="display: flex; gap: 15px;">
                    <!-- Instagram -->
                    <a href="https://www.instagram.com/meracikopi/" target="_blank"
                        style="color: white; text-decoration: none;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                            <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                            <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                        </svg>
                    </a>

                    <!-- Email -->
                    <a href="#" style="color: white; text-decoration: none;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z">
                            </path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        </svg>
                    </a>

                    <!-- Location / Maps -->
                    <a href="https://www.google.com/maps/search/Meracikopi/@-1.2248893,116.8632845,17z" target="_blank"
                        style="color: white; text-decoration: none;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                    </a>

                    <!-- Facebook -->
                    <a href="https://www.facebook.com/share/1CnSEpX1B6/?mibextid=wwXIfr" target="_blank"
                        style="color: white; text-decoration: none;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="footer-section">
                <h4 style="font-size: 18px; font-weight: 600; margin-bottom: 24px;">Quick Links</h4>
                <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 12px;">
                    <li><a href="{{ url('/') }}"
                            style="color: #a89890; text-decoration: none; font-size: 14px; transition: color 0.3s;"
                            onmouseover="this.style.color='white'" onmouseout="this.style.color='#a89890'">Home</a></li>
                    <li><a href="{{ url('/customer/catalogs') }}"
                            style="color: #a89890; text-decoration: none; font-size: 14px; transition: color 0.3s;"
                            onmouseover="this.style.color='white'" onmouseout="this.style.color='#a89890'">Catalog</a>
                    </li>

                </ul>
            </div>

            <!-- Contact Info -->
            <div class="footer-section">
                <h4 style="font-size: 18px; font-weight: 600; margin-bottom: 24px;">Contact Info</h4>
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <p style="color: #a89890; font-size: 14px; margin: 0; line-height: 1.6;">
                        <a href="https://www.google.com/maps/search/Meracikopi/@-1.2248893,116.8632845,17z"
                            target="_blank" style="color: #a89890; text-decoration: none; transition: color 0.3s;"
                            onmouseover="this.style.color='white'" onmouseout="this.style.color='#a89890'">
                            Jl. Indrakila No.107, Batu Ampar, Kec. Balikpapan Utara, Kota Balikpapan, Kalimantan Timur
                            76114
                        </a>
                    </p>
                    <p style="color: #a89890; font-size: 14px; margin: 0;">Phone: +62 123 4567 890</p>
                    <p style="color: #a89890; font-size: 14px; margin: 0;">Email: Meracikopi@gmail.com</p>
                </div>
            </div>
        </div>

        <!-- Copyright -->
        <div style="border-top: 1px solid rgba(255,255,255,0.1); padding-top: 30px; text-align: center;">
            <p style="color: white; font-size: 12px; letter-spacing: 1px; margin: 0;">© 2026 | Meracikopi. All right
                reserved.</p>
        </div>
    </div>
</footer>