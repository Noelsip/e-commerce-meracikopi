<style>
    /* Search bar styling */
    .navbar-search-input {
        width: 320px;
        padding: 0 16px 0 44px;
        background-color: #4b3c35;
        border: 2px solid transparent;
        border-radius: 24px;
        color: #f0f2bd;
        font-size: 14px;
        outline: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .navbar-search-input::placeholder {
        color: rgba(200, 190, 180, 0.7);
        transition: all 0.3s ease;
    }

    .navbar-search-input:hover {
        background-color: #5a4940;
        border-color: rgba(240, 242, 189, 0.3);
    }

    .navbar-search-input:focus {
        background-color: #5a4940;
        border-color: #f0f2bd;
        transform: scale(1.02);
    }

    .navbar-search-input:focus::placeholder {
        color: rgba(200, 190, 180, 0.4);
    }

    /* Search icon animation on focus */
    .navbar-search-wrapper:focus-within .navbar-search-icon {
        color: #f0f2bd !important;
        transform: translateY(-50%) scale(1.1);
    }

    .navbar-search-icon {
        transition: all 0.3s ease;
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
        border-bottom: 3px solid #ffffff;
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

    /* iPad Mini & Tablet responsiveness (601px - 1024px) */
    @media (min-width: 601px) and (max-width: 1024px) {
        .navbar-search-input {
            width: 240px;
            font-size: 13px;
        }

        .navbar-search-wrapper {
            position: relative !important;
            left: auto !important;
            top: auto !important;
            transform: none !important;
            max-width: 300px !important;
            flex: 1;
        }

        .navbar-top-row {
            flex-wrap: wrap;
            gap: 16px;
        }

        .find-cafe-btn span {
            display: none;
        }

        .nav-link-main {
            font-size: 13px;
        }

        .navbar-desktop-menu {
            padding: 0 24px 20px 24px !important;
        }

        .navbar-right-section {
            gap: 20px !important;
        }
    }

    /* Mobile responsiveness (600px and below) */
    @media (max-width: 600px) {
        .navbar-search-input {
            width: 150px;
            font-size: 12px;
        }

        .navbar-desktop-menu,
        .find-cafe-btn {
            display: none !important;
        }

        .mobile-menu-btn {
            display: flex !important;
        }

        .navbar-container {
            padding: 0 12px !important;
        }

        .navbar-inner {
            min-height: 70px !important;
            border-radius: 0 0 16px 16px !important;
        }

        .navbar-top-wrapper {
            padding: 16px 16px !important;
        }

        .navbar-logo-text {
            font-size: 16px !important;
        }

        .navbar-logo-icon {
            width: 32px !important;
            height: 32px !important;
        }

        .navbar-right-icons {
            gap: 16px !important;
        }

        /* Show mobile search button */
        .mobile-search-btn {
            display: flex !important;
            order: 1;
        }

        .mobile-search-btn:hover {
            color: #ffffff !important;
        }

        /* Hide desktop search bar */
        .navbar-search-wrapper {
            display: none !important;
        }

        /* Reorder icons: Search, Cart, Menu */
        .navbar-right-icons {
            display: flex !important;
        }

        .navbar-right-icons>a:not(.mobile-search-btn):not(.find-cafe-btn) {
            order: 2;
        }

        .mobile-menu-btn {
            order: 3;
        }

        .navbar-cart-icon {
            width: 22px !important;
            height: 22px !important;
        }

        .mobile-menu-btn svg {
            width: 24px !important;
            height: 24px !important;
        }
    }

    @media (max-width: 480px) {
        .navbar-search-wrapper {
            display: none !important;
        }

        .navbar-inner {
            min-height: 60px !important;
        }

        .navbar-top-wrapper {
            padding: 12px 14px !important;
        }

        .navbar-logo-text {
            font-size: 15px !important;
        }

        .navbar-logo-icon {
            width: 28px !important;
            height: 28px !important;
        }

        .navbar-right-icons {
            gap: 12px !important;
        }

        .navbar-cart-icon {
            width: 20px !important;
            height: 20px !important;
        }

        .mobile-menu-btn svg {
            width: 22px !important;
            height: 22px !important;
        }

        .mobile-menu-btn {
            padding: 4px !important;
        }
    }

    /* Mobile Search Overlay */
    .mobile-search-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #2a1b14;
        z-index: 9999;
        display: none;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .mobile-search-overlay.active {
        display: flex;
        opacity: 1;
    }

    .mobile-search-overlay-content {
        width: 100%;
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .mobile-search-overlay-header {
        display: flex;
        align-items: center;
        gap: 16px;
    }
</style>

<!-- Navbar Container -->
<div class="navbar-container" style="background-color: #2a1b14; padding: 0; position: sticky; top: 0; z-index: 100;">
    <div class="navbar-inner"
        style="background-color: #2a1b14; box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4); min-height: 115px;">
        <!-- Top Row: Logo, Search, Find a Cafe, Cart -->
        <div class="navbar-top-wrapper" style="padding: 20px 40px;">
            <div
                style="display: flex; align-items: center; justify-content: space-between; max-width: 1360px; margin: 0 auto; position: relative;">

                <!-- Left: Logo -->
                <a href="/" style="display: flex; align-items: center; gap: 10px; text-decoration: none;">
                    <img src="{{ asset('meracik-logo1.png') }}" alt="Meracikopi Logo" class="navbar-logo-icon"
                        style="width: 40px; height: 40px; object-fit: contain;">
                    <span class="navbar-logo-text"
                        style="color: white; font-weight: 600; font-size: 18px; font-family: 'Figtree', sans-serif;">Meracikopi</span>
                </a>

                <!-- Center: Search Bar -->
                <form method="GET" action="{{ url('/customer/catalogs') }}" class="navbar-search-wrapper"
                    style="position: absolute; left: 50%; top: 55%; transform: translate(-50%, -50%); width: 100%; max-width: 629px;">
                    <div style="position: relative;">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="What Do you Want Today?" class="navbar-search-input"
                            style="width: 100%; height: 30px;">
                        <svg class="navbar-search-icon"
                            style="position: absolute; right: 16px; top: 50%; transform: translateY(-50%); width: 14px; height: 14px; color: rgba(200, 190, 180, 0.7);"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </form>

                <!-- Right: Find a Cafe & Cart -->
                <div class="navbar-right-icons" style="display: flex; align-items: center; gap: 40px;">
                    <!-- Find a Cafe -->
                    <a href="https://www.google.com/maps/search/Meracikopi/@-1.2248893,116.8632845,17z" target="_blank"
                        class="find-cafe-btn">
                        <svg style="width: 20px; height: 25px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>Find a Cafe</span>
                    </a>

                    <!-- Cart Icon -->
                    <!-- Cart Icon -->
                    <a href="/customer/cart"
                        style="color: white; text-decoration: none; opacity: 0.9; transition: opacity 0.3s ease;"
                        onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.9'">
                        <svg class="navbar-cart-icon" style="width: 25px; height: 25px;" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </a>

                    <!-- Mobile Search Button -->
                    <button type="button" class="mobile-search-btn" onclick="toggleMobileSearchOverlay()"
                        style="display: none; color: rgba(255, 255, 255, 0.85); padding: 8px; transition: color 0.3s ease; background: none; border: none; cursor: pointer;">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </button>

                    <!-- Mobile Menu Button -->
                    <button class="mobile-menu-btn" onclick="toggleMobileMenu()" aria-label="Open menu"
                        style="display: none; background: none; border: none; color: white; cursor: pointer; padding: 8px;">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round">
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <line x1="3" y1="12" x2="21" y2="12"></line>
                            <line x1="3" y1="18" x2="21" y2="18"></line>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Search Bar (hidden by default) -->
        <div id="mobileSearchBar" class="mobile-search-bar" style="display: none; padding: 0 16px 16px 16px;">
            <form method="GET" action="{{ url('/customer/catalogs') }}" style="position: relative;">
                <input type="text" name="search" placeholder="Cari produk..."
                    style="width: 100%; height: 40px; padding: 0 16px 0 44px; background-color: #4b3c35; border: 2px solid transparent; border-radius: 20px; color: #f0f2bd; font-size: 14px; outline: none;"
                    id="mobileSearchInput">
                <svg style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); width: 18px; height: 18px; color: rgba(200, 190, 180, 0.7);"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </form>
        </div>

        <!-- Bottom Row: Navigation Links -->
        <div class="navbar-desktop-menu" style="padding: 0 40px 24px 40px;">
            <div style="display: flex; align-items: center; gap: 32px; max-width: 1360px; margin: 0 auto;">
                <a href="/" class="nav-link-main {{ request()->is('/') ? 'active' : '' }}"
                    style="font-weight: 600;">Home</a>
                <a href="{{ url('/customer/catalogs') }}"
                    class="nav-link-main {{ request()->is('customer/catalogs*') ? 'active' : '' }}"
                    style="font-weight: 600;">Catalog</a>
            </div>
        </div>
    </div>
</div>



<!-- Mobile Search Overlay -->
<div class="mobile-search-overlay" id="mobileSearchOverlay">
    <div class="mobile-search-overlay-content">
        <div class="mobile-search-overlay-header">
            <img src="{{ asset('meracik-logo1.png') }}" alt="Meracikopi Logo"
                style="height: 30px; width: auto; flex-shrink: 0;">
            <form method="GET" action="{{ url('/customer/catalogs') }}" style="position: relative; flex: 1;">
                <svg style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: rgba(200, 190, 180, 0.7); z-index: 1;"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" name="search" placeholder="Cari produk..." autofocus id="mobileOverlaySearchInput"
                    style="width: 100%; padding: 12px 40px 12px 42px; background-color: #4b3c35; border: 2px solid #6b4d3a; border-radius: 50px; color: #f0f2bd; font-size: 14px; outline: none;">
                <button type="button" onclick="toggleMobileSearchOverlay()"
                    style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #f0f2bd; cursor: pointer; padding: 6px; z-index: 1;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </form>
        </div>
    </div>
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
            class="{{ request()->is('customer/catalogs*') ? 'active' : '' }}">Catalog</a>
    </div>
</div>

<script>
    function toggleMobileMenu() {
        const menu = document.getElementById('mobileMenu');
        const overlay = document.getElementById('mobileMenuOverlay');
        menu.classList.toggle('active');
        overlay.classList.toggle('active');
        document.body.style.overflow = menu.classList.contains('active') ? 'hidden' : '';
    }

    function toggleMobileSearch() {
        const searchBar = document.getElementById('mobileSearchBar');
        const searchInput = document.getElementById('mobileSearchInput');

        if (searchBar.style.display === 'none' || searchBar.style.display === '') {
            searchBar.style.display = 'block';
            searchInput.focus();
        } else {
            searchBar.style.display = 'none';
        }
    }

    function toggleMobileSearchOverlay() {
        const overlay = document.getElementById('mobileSearchOverlay');
        const searchInput = document.getElementById('mobileOverlaySearchInput');

        overlay.classList.toggle('active');
        document.body.style.overflow = overlay.classList.contains('active') ? 'hidden' : '';

        if (overlay.classList.contains('active')) {
            setTimeout(() => searchInput.focus(), 100);
        }
    }
</script>

<!-- Announcement Bar -->
<div style="background-color: #1a1410; padding: 0; margin-top: 60px;">
    <div style="
        padding: 16px 24px; 
        text-align: center;
        background: radial-gradient(ellipse 75% 60% at 50% 50%, rgba(50, 32, 21, 0.7) 0%, rgba(50, 32, 21, 0.3) 45%, rgba(26, 20, 16, 0) 100%);
    ">
        <p class="announcement-text" style="
            color: #E8E0D5; 
            font-size: 14px; 
            margin: 0; 
            letter-spacing: 0.1em;
            font-weight: 500;
        ">
            Nikmati kopi pilihan hari ini &nbsp;<span style="opacity: 0.6;">|</span>&nbsp; Dine In & Delivery tersedia
        </p>
    </div>
</div>

<style>
    @media (max-width: 600px) {
        .announcement-text {
            font-size: 11px !important;
            letter-spacing: 0.05em !important;
            padding: 0 8px;
        }
    }

    @media (max-width: 480px) {
        .announcement-text {
            font-size: 10px !important;
            letter-spacing: 0.03em !important;
        }
    }
</style>