<!-- Navbar Wrapper with black background -->
<div style="background-color: #1a1410; padding: 0 20px;">
    <!-- Main Navbar Container - Floating with margins -->
    <nav style="
        background-color: #34231c;
        padding: 16px 60px;
        border-radius: 0 0 30px 30px;
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.3);
    ">
        <div
            style="display: flex; align-items: center; justify-content: space-between; max-width: 1400px; margin: 0 auto;">

            <!-- Left: Logo & Brand -->
            <a href="/" style="display: flex; align-items: center; gap: 14px; text-decoration: none;">
                <div style="
                    width: 46px; 
                    height: 46px; 
                    background: linear-gradient(145deg, #F0F2BD, #d4d6a3);
                    border-radius: 50%; 
                    display: flex; 
                    align-items: center; 
                    justify-content: center;
                    box-shadow: 0 4px 15px rgba(240, 242, 189, 0.3);
                ">
                    <span style="font-size: 20px;">☕</span>
                </div>
                <span style="
                    color: white; 
                    font-weight: 600; 
                    font-size: 20px; 
                    letter-spacing: 0.5px;
                ">Meracikopi</span>
            </a>

            <!-- Center: Navigation Menu -->
            <div style="display: flex; align-items: center; gap: 48px;">
                <!-- Home - Active when on home page -->
                <a href="/" style="
                    color: {{ request()->is('/') ? 'white' : 'rgba(255,255,255,0.85)' }}; 
                    text-decoration: none; 
                    font-weight: {{ request()->is('/') ? '600' : '500' }}; 
                    font-size: 15px;
                    {{ request()->is('/') ? 'border-bottom: 2px solid #CA7842; padding-bottom: 2px;' : '' }}
                ">Home</a>

                <!-- Menu - Active when on catalogs page -->
                <a href="{{ url('/customer/catalogs') }}" style="
                    color: {{ request()->is('customer/catalogs*') ? 'white' : 'rgba(255,255,255,0.85)' }}; 
                    text-decoration: none; 
                    font-weight: {{ request()->is('customer/catalogs*') ? '600' : '500' }}; 
                    font-size: 15px;
                    {{ request()->is('customer/catalogs*') ? 'border-bottom: 2px solid #CA7842; padding-bottom: 2px;' : '' }}
                ">Menu</a>

                <!-- Contact -->
                <a href="#" style="
                    color: rgba(255,255,255,0.85); 
                    text-decoration: none; 
                    font-weight: 500; 
                    font-size: 15px;
                ">Contact</a>

                <!-- Order History -->
                <a href="#" style="
                    color: rgba(255,255,255,0.85); 
                    text-decoration: none; 
                    font-weight: 500; 
                    font-size: 15px;
                ">Order History</a>
            </div>

            <!-- Right: Search & Cart -->
            <div style="display: flex; align-items: center; gap: 24px;">

                <!-- Search Bar -->
                <form method="GET" action="{{ url('/customer/catalogs') }}">
                    <div style="position: relative;">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="What Do you Want Today?" style="
                                   width: 220px; 
                                   padding: 12px 16px 12px 44px; 
                                   background-color: transparent;
                                   border: 1px solid #5a4a42; 
                                   border-radius: 30px; 
                                   color: #a89890; 
                                   font-size: 13px; 
                                   outline: none;
                               ">
                        <svg style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: #B2CD9C;"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </form>

                <!-- Cart Icon -->
                <a href="#" style="color: white; text-decoration: none;">
                    <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </a>
            </div>
        </div>
    </nav>
</div>

<!-- Announcement Bar -->
<div style="
    background-color: #1a1410;
    border-top: 1px solid #3a2a22;
    padding: 14px 0; 
    text-align: center;
">
    <p style="color: #CA7842; font-size: 14px; margin: 0; letter-spacing: 0.5px;">
        Nikmati kopi pilihan hari ini | Dine In & Delivery tersedia
    </p>
</div>