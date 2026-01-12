<footer style="background-color: #4B352A; padding: 48px 0 24px;">
    <div style="max-width: 1280px; margin: 0 auto; padding: 0 16px;">
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 32px;">
            <!-- Brand -->
            <div style="grid-column: span 2;">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                    <div
                        style="width: 40px; height: 40px; background-color: #F0F2BD; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <span style="color: #4B352A; font-weight: bold; font-size: 18px;">M</span>
                    </div>
                    <span style="color: white; font-weight: 600; font-size: 20px;">Meracikopi</span>
                </div>
                <p style="color: #ccc; font-size: 14px; max-width: 400px; line-height: 1.6;">
                    Menyajikan kopi berkualitas tinggi dengan cita rasa autentik Indonesia. Nikmati pengalaman ngopi
                    terbaik bersama kami.
                </p>
            </div>

            <!-- Quick Links -->
            <div>
                <h3 style="color: white; font-weight: 600; margin-bottom: 16px;">Quick Links</h3>
                <ul style="list-style: none; padding: 0; margin: 0;">
                    <li style="margin-bottom: 8px;"><a href="/"
                            style="color: #ccc; text-decoration: none; font-size: 14px;">Home</a></li>
                    <li style="margin-bottom: 8px;"><a href="{{ url('/customer/catalogs') }}"
                            style="color: #ccc; text-decoration: none; font-size: 14px;">Menu</a></li>
                    <li style="margin-bottom: 8px;"><a href="#"
                            style="color: #ccc; text-decoration: none; font-size: 14px;">About Us</a></li>
                    <li style="margin-bottom: 8px;"><a href="#"
                            style="color: #ccc; text-decoration: none; font-size: 14px;">Contact</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div>
                <h3 style="color: white; font-weight: 600; margin-bottom: 16px;">Contact Us</h3>
                <ul style="list-style: none; padding: 0; margin: 0; color: #ccc; font-size: 14px;">
                    <li style="margin-bottom: 8px; display: flex; align-items: center; gap: 8px;">
                        <span style="color: #B2CD9C;">📍</span> Jakarta, Indonesia
                    </li>
                    <li style="margin-bottom: 8px; display: flex; align-items: center; gap: 8px;">
                        <span style="color: #B2CD9C;">✉️</span> info@meracikopi.com
                    </li>
                </ul>
            </div>
        </div>

        <!-- Copyright -->
        <div style="border-top: 1px solid #5a4339; margin-top: 32px; padding-top: 24px; text-align: center;">
            <p style="color: #888; font-size: 14px;">
                &copy; {{ date('Y') }} Meracikopi. All rights reserved.
            </p>
        </div>
    </div>
</footer>