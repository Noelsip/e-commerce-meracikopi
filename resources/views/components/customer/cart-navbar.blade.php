<style>
    /* Cart Navbar Styling */
    .cart-navbar {
        background-color: #2A1B14;
        height: 115px;
        box-shadow: 0 7px 4px rgba(0, 0, 0, 0.25);
        padding: 0 40px;
        position: relative;
    }

    .cart-navbar-inner {
        max-width: 1360px;
        height: 100%;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: relative;
    }

    .cart-navbar-container {
        background-color: #2A1B14;
        padding: 0;
        width: 100%;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 999;
    }

    /* Logo and Cart Title */
    .cart-logo-section {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-shrink: 0;
    }

    .cart-logo-circle {
        width: 40px;
        height: 40px;
        background: linear-gradient(145deg, #F0F2BD, #d4d6a3);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .cart-logo-text {
        color: white;
        font-weight: 600;
        font-size: 18px;
        font-family: 'Poppins', sans-serif;
    }

    .cart-divider {
        width: 2px;
        height: 30px;
        background-color: rgba(255, 255, 255, 0.5);
        margin: 0 16px;
    }

    .cart-title {
        color: #CA7842;
        font-weight: 600;
        font-size: 18px;
        font-family: 'Poppins', sans-serif;
    }

    /* Search bar in cart navbar - aligned with Aksi column (right edge of rectangle) */
    .cart-search-wrapper {
        width: 100%;
        max-width: 629px;
        position: absolute;
        left: 50%;
        top: 55%;
        transform: translate(-50%, -50%);
    }

    .cart-search-input {
        width: 100%;
        height: 36px;
        padding: 0 40px 0 20px;
        background-color: #4b3c35;
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 24px;
        color: #f0f2bd;
        font-size: 13px;
        outline: none;
        transition: all 0.3s ease;
        text-align: center;
    }

    .cart-search-input::placeholder {
        color: rgba(200, 190, 180, 0.6);
        text-align: center;
    }

    .cart-search-input:focus {
        background-color: rgba(75, 53, 42, 1);
        border-color: rgba(202, 120, 66, 0.4);
    }

    .cart-search-icon {
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        width: 16px;
        height: 16px;
        color: rgba(200, 190, 180, 0.6);
    }

    /* Mobile responsiveness */
    @media (max-width: 768px) {
        .cart-navbar {
            height: auto;
            min-height: 80px;
            padding: 16px 20px;
            flex-wrap: wrap;
            justify-content: center;
            gap: 16px;
        }

        .cart-search-wrapper {
            position: relative;
            left: auto;
            top: auto;
            transform: none;
            max-width: 100%;
            width: 100%;
            margin-right: 0;
        }

        .cart-logo-text {
            font-size: 16px;
        }

        .cart-title {
            font-size: 16px;
        }

        .back-button-container {
            padding-top: 100px !important;
        }
    }

    /* Back Button Container (Below Navbar, aligned with content) */
    .back-button-container {
        max-width: 1360px;
        margin: 0 auto;
        padding: 135px 20px 0 20px;
    }

    .back-to-home-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: white;
        text-decoration: none;
        font-size: 14px;
        font-weight: 400;
        transition: all 0.2s ease;
    }

    .back-to-home-btn:hover {
        color: #CA7842;
    }

    .back-to-home-btn svg {
        flex-shrink: 0;
    }
</style>

<!-- Cart Navbar Container -->
<div class="cart-navbar-container">
    <div class="cart-navbar">
        <div class="cart-navbar-inner">
            <!-- Left: Logo and Cart Title -->
            <div class="cart-logo-section">
                <a href="/" style="display: flex; align-items: center; gap: 12px; text-decoration: none;">
                    <div class="cart-logo-circle"></div>
                    <span class="cart-logo-text">Meracikopi</span>
                </a>
                <div class="cart-divider"></div>
                <span class="cart-title">Cart</span>
            </div>

            <!-- Center: Search Bar -->
            <div class="cart-search-wrapper">
                <div style="position: relative;">
                    <input type="text" name="search" placeholder="What Do you Want Today?" class="cart-search-input">
                    <svg class="cart-search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Back Button (Below Navbar) -->
<div class="back-button-container">
    <a href="{{ route('catalogs.index') }}" class="back-to-home-btn">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M19 12H5M12 19l-7-7 7-7" />
        </svg>
        <span>Back</span>
    </a>
</div>