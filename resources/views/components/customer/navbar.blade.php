<style>
    .search-input-custom {
        width: 240px;
        padding: 10px 16px 10px 40px;
        background-color: #F8F5F2;
        border: 1px solid transparent;
        border-radius: 24px;
        color: #5A4A42;
        font-size: 13px;
        outline: none;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .search-input-custom:hover {
        background-color: #fff;
        border-color: rgba(202, 120, 66, 0.4);
        box-shadow: 0 0 0 2px rgba(202, 120, 66, 0.1);
    }

    .search-input-custom:focus {
        background-color: #fff;
        border-color: #CA7842;
        box-shadow: 0 0 0 3px rgba(202, 120, 66, 0.2);
    }

    .search-wrapper:hover svg {
        color: #CA7842 !important;
        transform: translateY(-50%) scale(1.1);
    }

    /* Nav Link Hover Effects */
    .nav-link-custom {
        position: relative;
        padding-bottom: 2px;
    }

    .nav-link-custom::after {
        content: '';
        position: absolute;
        width: 0;
        height: 2px;
        bottom: -2px;
        left: 50%;
        background-color: #CA7842;
        transition: all 0.3s ease;
        transform: translateX(-50%);
        box-shadow: 0 0 8px rgba(202, 120, 66, 0.6);
    }

    .nav-link-custom:hover::after {
        width: 100%;
    }

    .nav-link-custom:hover {
        color: #FFFFFF !important;
        text-shadow: 0 0 10px rgba(202, 120, 66, 0.4) !important;
        transform: translateY(-1px);
    }
</style>

<!-- Navbar Wrapper with black background -->
<div style="background-color: #1a1410; padding: 0 20px;">
    <!-- Main Navbar Container - Floating with margins -->
    <nav class="navbar-main" style="
        background: linear-gradient(135deg, #2C1E16 0%, #4A332A 100%);
        padding: 12px 40px;
        border-radius: 0 0 24px 24px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.05);
    ">
        <div
            style="display: flex; align-items: center; justify-content: space-between; max-width: 1400px; margin: 0 auto;">

            <!-- Left: Logo & Brand -->
            <a href="/" style="display: flex; align-items: center; gap: 12px; text-decoration: none;">
                <div style="
                    width: 40px; 
                    height: 40px; 
                    background: linear-gradient(145deg, #F0F2BD, #d4d6a3);
                    border-radius: 50%; 
                    display: flex; 
                    align-items: center; 
                    justify-content: center;
                    box-shadow: 0 4px 15px rgba(240, 242, 189, 0.2);
                ">
                    <span style="font-size: 18px;">☕</span>
                </div>
                <span style="
                    color: white; 
                    font-weight: 600; 
                    font-size: 20px; 
                    font-family: 'Figtree', sans-serif;
                    letter-spacing: 0.5px;
                ">Meracikopi</span>
            </a>

            <!-- Center: Navigation Menu (Hidden on mobile) -->
            <div class="navbar-center-menu" style="display: flex; align-items: center; gap: 48px;">
                <!-- Home -->
                <a href="/" class="nav-link-custom" style="
                    color: {{ request()->is('/') ? '#FFFFFF' : 'rgba(255,255,255,0.7)' }}; 
                    text-decoration: none; 
                    font-weight: {{ request()->is('/') ? '600' : '500' }}; 
                    font-size: 14px;
                    transition: all 0.3s ease;
                    text-shadow: {{ request()->is('/') ? '0 0 10px rgba(255,255,255,0.3)' : 'none' }};
                ">Home</a>

                <!-- Menu -->
                <a href="{{ url('/customer/catalogs') }}" class="nav-link-custom" style="
                    color: {{ request()->is('customer/catalogs*') ? '#FFFFFF' : 'rgba(255,255,255,0.7)' }}; 
                    text-decoration: none; 
                    font-weight: {{ request()->is('customer/catalogs*') ? '600' : '500' }}; 
                    font-size: 14px;
                    transition: all 0.3s ease;
                    text-shadow: {{ request()->is('customer/catalogs*') ? '0 0 10px rgba(255,255,255,0.3)' : 'none' }};
                ">Menu</a>

                <!-- Contact -->
                <a href="#" class="nav-link-custom" style="
                    color: rgba(255,255,255,0.7); 
                    text-decoration: none; 
                    font-weight: 500; 
                    font-size: 14px;
                    transition: all 0.3s ease;
                ">Contact</a>

                <!-- Order History -->
                <a href="#" class="nav-link-custom" style="
                    color: rgba(255,255,255,0.7); 
                    text-decoration: none; 
                    font-weight: 500; 
                    font-size: 14px;
                    transition: all 0.3s ease;
                ">Order History</a>
            </div>

            <!-- Right: Search & Cart & Mobile Menu Button -->
            <div style="display: flex; align-items: center; gap: 24px;">

                <!-- Search Bar (Hidden on mobile) -->
                <form method="GET" action="{{ url('/customer/catalogs') }}" class="navbar-search">
                    <div class="search-wrapper" style="position: relative;">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="What Do you Want Today?" class="search-input-custom">
                        <svg style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: #8C7B70;"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </form>

                <!-- Cart Icon -->
                <a href="#" style="color: white; text-decoration: none; opacity: 0.9; transition: opacity 0.3s ease;"
                    onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.9'">
                    <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </a>

                <!-- Mobile Menu Button (Hamburger) -->
                <button class="mobile-menu-btn" onclick="toggleMobileMenu()" aria-label="Open menu">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round">
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>
            </div>
        </div>
    </nav>
</div>

<!-- Mobile Menu Overlay -->
<div class="mobile-menu-overlay" id="mobileMenuOverlay" onclick="toggleMobileMenu()"></div>

<!-- Mobile Menu Drawer -->
<div class="mobile-menu" id="mobileMenu">
    <button class="mobile-menu-close" onclick="toggleMobileMenu()" aria-label="Close menu">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
    </button>

    <div class="mobile-menu-links">
        <a href="/" class="{{ request()->is('/') ? 'active' : '' }}">Home</a>
        <a href="{{ url('/customer/catalogs') }}"
            class="{{ request()->is('customer/catalogs*') ? 'active' : '' }}">Menu</a>
        <a href="#">Contact</a>
        <a href="#">Order History</a>
    </div>

    <form method="GET" action="{{ url('/customer/catalogs') }}" class="mobile-search-form">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari menu...">
    </form>
</div>

<script>
    function toggleMobileMenu() {
        const menu = document.getElementById('mobileMenu');
        const overlay = document.getElementById('mobileMenuOverlay');
        menu.classList.toggle('active');
        overlay.classList.toggle('active');
        document.body.style.overflow = menu.classList.contains('active') ? 'hidden' : '';
    }
</script>

<!-- Announcement Bar / Hero Strip -->
<div class="announcement-bar" style="
    background: radial-gradient(circle at center, #2C1E16 0%, #1a1410 100%);
    padding: 12px 0; 
    margin-top: 24px;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 46px;
    box-shadow: inset 0 0 20px rgba(0,0,0,0.5);
">
    <p class="announcement-text" style="
        color: #F8F5F2; 
        font-size: 12px; 
        line-height: 2.0;
        margin: 0; 
        letter-spacing: 0.25em;
        font-weight: 400;
        text-shadow: 0 0 12px rgba(202, 120, 66, 0.4);
        text-transform: uppercase;
        opacity: 0.9;
    ">
        Nikmati kopi pilihan hari ini <span class="announcement-divider"
            style="margin: 0 16px; color: #CA7842; font-weight: 400;">|</span> Dine In &
        Delivery tersedia
    </p>
</div>