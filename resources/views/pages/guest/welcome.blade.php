<x-customer.layout title="Meracikopi - Kopi Berkualitas">
    <!-- Hero Section -->
    <div style="background-color: #1a1410; min-height: 80vh; padding: 60px 80px;">
        <div style="max-width: 1400px; margin: 0 auto; display: flex; align-items: center; gap: 60px;">

            <!-- Left: Text Content -->
            <div style="flex: 1;">
                <h1 style="font-size: 56px; font-weight: 700; line-height: 1.1; margin-bottom: 24px;">
                    <span style="color: white;">Nikmati </span>
                    <span style="color: #CA7842; font-style: italic;">Kopi</span>
                    <br>
                    <span style="color: white;">Berkualitas, Diracik</span>
                    <br>
                    <span style="color: white;">Sepenuh Hati </span>
                    <span style="font-size: 48px;">☕</span>
                </h1>
                <p style="color: #a89890; font-size: 18px; line-height: 1.6; margin-bottom: 40px; max-width: 500px;">
                    Kopi dengan cita rasa autentik, diseduh dari biji terbaik untuk menemani setiap momenmu
                </p>
                <div style="display: flex; gap: 16px;">
                    <a href="{{ url('/customer/catalogs') }}" style="
                        display: inline-block;
                        padding: 16px 32px;
                        background-color: #CA7842;
                        color: white;
                        text-decoration: none;
                        border-radius: 30px;
                        font-weight: 600;
                        font-size: 16px;
                        transition: all 0.3s ease;
                    ">Lihat Menu</a>
                    <a href="#" style="
                        display: inline-block;
                        padding: 16px 32px;
                        background-color: transparent;
                        color: white;
                        text-decoration: none;
                        border: 1px solid #5a4a42;
                        border-radius: 30px;
                        font-weight: 500;
                        font-size: 16px;
                    ">Tentang Kami</a>
                </div>
            </div>

            <!-- Right: Image Grid -->
            <div style="flex: 1; display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px;">
                <!-- Image 1 -->
                <div style="
                    aspect-ratio: 1;
                    background: linear-gradient(145deg, #2a1f1a, #3a2f2a);
                    border-radius: 20px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 64px;
                    border: 2px solid #CA7842;
                ">🍜</div>

                <!-- Image 2 -->
                <div style="
                    aspect-ratio: 1;
                    background: linear-gradient(145deg, #2a1f1a, #3a2f2a);
                    border-radius: 20px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 64px;
                    border: 2px solid #CA7842;
                ">☕</div>

                <!-- Image 3 -->
                <div style="
                    aspect-ratio: 1;
                    background: linear-gradient(145deg, #2a1f1a, #3a2f2a);
                    border-radius: 20px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 48px;
                    color: #CA7842;
                ">☕</div>

                <!-- Image 4 -->
                <div style="
                    aspect-ratio: 1;
                    background: linear-gradient(145deg, #2a1f1a, #3a2f2a);
                    border-radius: 20px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 48px;
                ">🫘</div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div style="background-color: #1a1410; padding: 60px 80px;">
        <div style="max-width: 1400px; margin: 0 auto;">
            <h2 style="color: white; font-size: 32px; font-weight: 700; text-align: center; margin-bottom: 48px;">
                Mengapa Memilih <span style="color: #CA7842;">Meracikopi</span>?
            </h2>
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 32px;">
                <!-- Feature 1 -->
                <div style="background-color: #34231c; padding: 32px; border-radius: 20px; text-align: center;">
                    <div style="font-size: 48px; margin-bottom: 16px;">🌿</div>
                    <h3 style="color: white; font-size: 20px; font-weight: 600; margin-bottom: 12px;">Biji Pilihan</h3>
                    <p style="color: #a89890; font-size: 14px; line-height: 1.6;">
                        Kopi dari biji pilihan terbaik Indonesia yang dipilih dengan teliti
                    </p>
                </div>

                <!-- Feature 2 -->
                <div style="background-color: #34231c; padding: 32px; border-radius: 20px; text-align: center;">
                    <div style="font-size: 48px; margin-bottom: 16px;">👨‍🍳</div>
                    <h3 style="color: white; font-size: 20px; font-weight: 600; margin-bottom: 12px;">Barista Handal
                    </h3>
                    <p style="color: #a89890; font-size: 14px; line-height: 1.6;">
                        Diseduh oleh barista berpengalaman dengan teknik terbaik
                    </p>
                </div>

                <!-- Feature 3 -->
                <div style="background-color: #34231c; padding: 32px; border-radius: 20px; text-align: center;">
                    <div style="font-size: 48px; margin-bottom: 16px;">🚀</div>
                    <h3 style="color: white; font-size: 20px; font-weight: 600; margin-bottom: 12px;">Delivery Cepat
                    </h3>
                    <p style="color: #a89890; font-size: 14px; line-height: 1.6;">
                        Dine in, take away, atau delivery - kopi sampai hangat
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-customer.layout>