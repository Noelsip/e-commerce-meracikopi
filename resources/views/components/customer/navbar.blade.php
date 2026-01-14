<style>
    /* Search bar styling */
    .navbar-search-input {
        width: 320px;
        padding: 10px 16px 10px 44px;
        background-color: rgba(75, 53, 42, 0.8);
        border: 1px solid rgba(100, 75, 60, 0.6);
        border-radius: 24px;
        color: #f0f2bd;
        font-size: 14px;
        outline: none;
        transition: all 0.3s ease;
    }

    .navbar-search-input::placeholder {
        color: rgba(200, 190, 180, 0.7);
    }

    .navbar-search-input:focus {
        background-color: rgba(75, 53, 42, 1);
        border-color: rgba(202, 120, 66, 0.6);
    }

    /* Nav Links */
    .nav-link-main {
        color: rgba(255, 255, 255, 0.75);
        text-decoration: none;
        font-weight: 500;
        font-size: 14px;
        padding-bottom: 4px;
        border-bottom: 2px solid transparent;
        transition: all 0.3s ease;
    }

    .nav-link-main:hover {
        color: #ffffff;
    }

    .nav-link-main.active {
        color: #ffffff;
        border-bottom: 2px solid #ffffff;
    }

    /* Find a Cafe button */
    .find-cafe-btn {
        display: flex;
        align-items: center;
        gap: 6px;
        color: rgba(255, 255, 255, 0.85);
        font-size: 13px;
        font-weight: 500;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .find-cafe-btn:hover {
        color: #ffffff;
    }

    /* Mobile responsiveness */
    @media (max-width: 768px) {
        .navbar-search-input {
            width: 180px;
            font-size: 12px;
        }

        .navbar-desktop-menu,
        .find-cafe-btn {
            display: none !important;
        }

        .mobile-menu-btn {
            display: flex !important;
        }
    }

    @media (max-width: 480px) {
        .navbar-search-wrapper {
            display: none !important;
        }
    }
</style>

<!-- Navbar Container -->
<div style="background-color: #2b211e;">
    <!-- Top Row: Logo, Search, Find a Cafe, Cart -->
    <div style="padding: 16px 24px;">
        <div style="display: flex; align-items: center; justify-content: space-between; max-width: 1280px; margin: 0 auto;">
            
            <!-- Left: Logo -->
            <a href="/" style="display: flex; align-items: center; gap: 12px; text-decoration: none;">
                <div style="
                    width: 40px; 
                    height: 40px; 
                    background: linear-gradient(145deg, #F0F2BD, #d4d6a3);
                    border-radius: 50%; 
                    display: flex; 
                    align-items: center; 
                    justify-content: center;
                ">
                </div>
                <span style="color: white; font-weight: 600; font-size: 18px; font-family: 'Figtree', sans-serif;">Meracikopi</span>
            </a>

            <!-- Center: Search Bar -->
            <form method="GET" action="{{ url('/customer/catalogs') }}" class="navbar-search-wrapper" style="flex: 1; max-width: 400px; margin: 0 40px;">
                <div style="position: relative;">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="What Do you Want Today?" 
                           class="navbar-search-input" style="width: 100%;">
                    <svg style="position: absolute; right: 16px; top: 50%; transform: translateY(-50%); width: 18px; height: 18px; color: rgba(200, 190, 180, 0.7);" 
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </form>

            <!-- Right: Find a Cafe & Cart -->
            <div style="display: flex; align-items: center; gap: 24px;">
                <!-- Find a Cafe -->
                <a href="#" class="find-cafe-btn">
                    <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span>Find a Cafe</span>
                </a>

                <!-- Cart Icon -->
                <a href="#" style="color: white; text-decoration: none; opacity: 0.9; transition: opacity 0.3s ease;"
                   onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.9'">
                    <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </a>

                <!-- Mobile Menu Button -->
                <button class="mobile-menu-btn" onclick="toggleMobileMenu()" aria-label="Open menu" 
                        style="display: none; background: none; border: none; color: white; cursor: pointer; padding: 8px;">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Bottom Row: Navigation Links -->
    <div class="navbar-desktop-menu" style="padding: 0 24px 16px 24px;">
        <div style="display: flex; align-items: center; gap: 32px; max-width: 1280px; margin: 0 auto;">
            <a href="/" class="nav-link-main {{ request()->is('/') ? 'active' : '' }}">Home</a>
            <a href="{{ url('/customer/catalogs') }}" class="nav-link-main {{ request()->is('customer/catalogs*') ? 'active' : '' }}">Menu</a>
            <a href="#" class="nav-link-main">Contact</a>
            <a href="#" class="nav-link-main">Order History</a>
        </div>
    </div>
</div>

<!-- Mobile Menu Overlay -->
<div class="mobile-menu-overlay" id="mobileMenuOverlay" onclick="toggleMobileMenu()"></div>

<!-- Mobile Menu Drawer -->
<div class="mobile-menu" id="mobileMenu">
    <button class="mobile-menu-close" onclick="toggleMobileMenu()" aria-label="Close menu">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
    </button>

    <div class="mobile-menu-links">
        <a href="/" class="{{ request()->is('/') ? 'active' : '' }}">Home</a>
        <a href="{{ url('/customer/catalogs') }}" class="{{ request()->is('customer/catalogs*') ? 'active' : '' }}">Menu</a>
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

<!-- Announcement Bar -->
<div style="
    background-color: #1a1410;
    padding: 12px 24px; 
    text-align: center;
">
    <p style="
        color: #F8F5F2; 
        font-size: 11px; 
        margin: 0; 
        letter-spacing: 0.2em;
        font-weight: 400;
        text-transform: uppercase;
        opacity: 0.85;
    ">
        Nikmati kopi pilihan hari ini Dine In & Delivery tersedia
    </p>
</div>