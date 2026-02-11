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
        padding-bottom: 8px;
        border-bottom: 3px solid transparent;
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
            padding: 0 !important;
        }

        .navbar-inner {
            min-height: 70px !important;
            border-radius: 0 !important;
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

    /* Mobile Menu Drawer */
    .mobile-menu {
        position: fixed;
        top: 0;
        right: -100%;
        width: 280px;
        height: 100vh;
        background-color: #2a1b14;
        z-index: 10001;
        transition: right 0.3s ease;
        box-shadow: -4px 0 16px rgba(0, 0, 0, 0.3);
        overflow-y: auto;
        display: flex;
        flex-direction: column;
    }

    .mobile-menu.active {
        right: 0;
    }

    .mobile-menu-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 20px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        background: #2a1b14;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .mobile-menu-logo {
        height: 32px;
        width: auto;
    }

    .mobile-menu-close {
        background: none;
        border: none;
        color: #ffffff;
        cursor: pointer;
        padding: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        border-radius: 50%;
    }

    .mobile-menu-close:active {
        background: rgba(255, 255, 255, 0.1);
    }

    .mobile-menu-close svg {
        width: 22px;
        height: 22px;
    }

    .mobile-menu-content {
        flex: 1;
        padding: 8px 0;
    }

    .mobile-menu-links {
        display: flex;
        flex-direction: column;
    }

    .mobile-menu-links a {
        color: rgba(255, 255, 255, 0.9);
        text-decoration: none;
        font-size: 16px;
        font-weight: 500;
        padding: 14px 20px;
        transition: all 0.2s ease;
        display: block;
        position: relative;
    }

    .mobile-menu-links a:active {
        background-color: rgba(255, 255, 255, 0.05);
    }

    .mobile-menu-links a.active {
        color: #CA7842;
        font-weight: 600;
        background-color: rgba(202, 120, 66, 0.1);
    }

    .mobile-menu-links a.active::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 3px;
        background-color: #CA7842;
    }

    .mobile-menu-footer {
        padding: 16px 20px;
        border-top: 1px solid #f0f0f0;
        background: #fafafa;
    }

    .mobile-menu-cta {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 12px;
        background-color: #2a1b14;
        border: none;
        border-radius: 25px;
        color: #ffffff;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        text-align: center;
        transition: all 0.2s ease;
        justify-content: center;
    }

    .mobile-menu-cta:active {
        background-color: #1f150f;
        transform: scale(0.98);
    }

    .mobile-menu-cta svg {
        width: 16px;
        height: 16px;
    }

    .mobile-menu-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        z-index: 10000;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
        backdrop-filter: blur(2px);
    }

    .mobile-menu-overlay.active {
        opacity: 1;
        visibility: visible;
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
    <div class="navbar-inner" style="background-color: #2a1b14; box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);">
        <!-- Top Row: Logo, Search, Find a Cafe, Cart -->
        <div class="navbar-top-wrapper" style="padding: 16px 40px 8px 40px;">
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

                    <!-- Cart Icon with Badge -->
                    <a href="/customer/cart"
                        style="position: relative; color: white; text-decoration: none; opacity: 0.9; transition: opacity 0.3s ease;"
                        onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.9'">
                        <svg class="navbar-cart-icon" style="width: 25px; height: 25px;" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <!-- Cart Badge -->
                        <span id="cartBadge" class="cart-badge" style="
                            position: absolute;
                            top: -8px;
                            right: -10px;
                            background: linear-gradient(135deg, #ef4444, #dc2626);
                            color: white;
                            font-size: 11px;
                            font-weight: 700;
                            min-width: 18px;
                            height: 18px;
                            border-radius: 9px;
                            display: none;
                            align-items: center;
                            justify-content: center;
                            padding: 0 5px;
                            box-shadow: 0 2px 6px rgba(0,0,0,0.3);
                            font-family: 'Poppins', sans-serif;
                            line-height: 1;
                        "></span>
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
        <div class="navbar-desktop-menu" style="padding: 4px 40px 0 40px;">
            <div style="display: flex; align-items: center; gap: 32px; max-width: 1360px; margin: 0 auto;">
                <a href="/" class="nav-link-main {{ request()->is('/') || request()->is('customer') ? 'active' : '' }}"
                    style="font-weight: 600;">Home</a>
                <a href="{{ url('/customer/catalogs') }}"
                    class="nav-link-main {{ request()->is('customer/catalogs*') ? 'active' : '' }}"
                    style="font-weight: 600;">Catalog</a>
                <a href="{{ url('/customer/order-history') }}"
                    class="nav-link-main {{ request()->is('customer/order-history*') ? 'active' : '' }}"
                    style="font-weight: 600;">Order History</a>
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

<!-- Mobile Menu Drawer - Starbucks Style -->
<div class="mobile-menu" id="mobileMenu">
    <!-- Menu Header -->
    <div class="mobile-menu-header">
        <img src="{{ asset('meracik-logo1.png') }}" alt="Meracikopi" class="mobile-menu-logo">
        <button class="mobile-menu-close" onclick="toggleMobileMenu()" aria-label="Close menu">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
    </div>

    <!-- Menu Content -->
    <div class="mobile-menu-content">
        <div class="mobile-menu-links">
            <a href="/" class="{{ request()->is('/') || request()->is('customer') ? 'active' : '' }}">Home</a>
            <a href="{{ url('/customer/catalogs') }}"
                class="{{ request()->is('customer/catalogs*') ? 'active' : '' }}">Catalog</a>
            <a href="{{ url('/customer/order-history') }}"
                class="{{ request()->is('customer/order-history*') ? 'active' : '' }}">Order History</a>
        </div>
    </div>

    <!-- Menu Footer -->
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

<!-- Cart Badge Counter Script -->
<script>
    // Cart Badge Counter Functions
    function updateCartBadge(count) {
        const badge = document.getElementById('cartBadge');
        if (!badge) return;

        if (count > 0) {
            // Display count or "99+" if more than 99
            badge.textContent = count > 99 ? '99+' : count.toString();
            badge.style.display = 'flex';
        } else {
            badge.style.display = 'none';
        }
    }

    function fetchCartCount() {
        const guestToken = localStorage.getItem('guest_token');
        if (!guestToken) {
            updateCartBadge(0);
            return;
        }

        fetch('/api/customer/cart', {
            headers: {
                'X-GUEST-TOKEN': guestToken,
                'Accept': 'application/json'
            }
        })
            .then(response => {
                if (!response.ok) throw new Error('Failed to fetch cart');
                return response.json();
            })
            .then(data => {
                const items = data.data.items || [];
                // Count total quantity of all items
                const totalCount = items.reduce((sum, item) => sum + (item.quantity || 0), 0);
                updateCartBadge(totalCount);
            })
            .catch(error => {
                console.error('Error fetching cart count:', error);
                updateCartBadge(0);
            });
    }

    // Load cart count on page load
    document.addEventListener('DOMContentLoaded', function () {
        fetchCartCount();

        // Reset body overflow on page load to fix any leftover hidden state
        document.body.style.overflow = '';
        document.documentElement.style.overflow = '';

        // Refresh cart count every 60 seconds (optimized for performance)
        setInterval(fetchCartCount, 60000);
    });

    // Listen for storage events (cart updates from other tabs)
    window.addEventListener('storage', function (e) {
        if (e.key === 'guest_token' || e.key === 'cart_updated') {
            fetchCartCount();
        }
    });

    // Custom event for cart updates
    window.addEventListener('cartUpdated', function () {
        fetchCartCount();
    });
</script>

<!-- Announcement Bar -->
<div class="announcement-bar" style="background-color: #1a1410; padding: 0; margin-top: 60px;">
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
            white-space: nowrap;
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