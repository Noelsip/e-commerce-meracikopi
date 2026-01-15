<style>
    /* Checkout Navbar Styling */
    .checkout-navbar {
        background-color: #2A1B14;
        width: 100%;
        max-width: 1440px;
        height: 115px;
        margin: 0 auto;
        box-shadow: 0 7px 4px rgba(0, 0, 0, 0.25);
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 100px;
        position: relative;
    }

    .checkout-navbar-container {
        background-color: #2A1B14;
        padding: 0;
        width: 100%;
    }

    /* Logo and Title */
    .checkout-logo-section {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-shrink: 0;
    }

    .checkout-logo-circle {
        width: 45px;
        height: 45px;
        background: linear-gradient(145deg, #F0F2BD, #d4d6a3);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .checkout-logo-text {
        color: white;
        font-weight: 600;
        font-size: 20px;
        font-family: 'Poppins', sans-serif;
    }

    .checkout-divider {
        width: 2px;
        height: 30px;
        background-color: rgba(255, 255, 255, 0.5);
        margin: 0 16px;
    }

    .checkout-title {
        color: #CA7842;
        font-weight: 600;
        font-size: 20px;
        font-family: 'Poppins', sans-serif;
    }

    /* Order Type Dropdown Section */
    .order-type-section {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-left: auto;
    }

    .order-type-label {
        color: rgba(255, 255, 255, 0.7);
        font-size: 14px;
        font-family: 'Poppins', sans-serif;
    }

    .order-type-dropdown {
        position: relative;
    }

    .order-type-select {
        appearance: none;
        -webkit-appearance: none;
        background-color: transparent;
        border: none;
        color: white;
        font-size: 14px;
        font-weight: 500;
        font-family: 'Poppins', sans-serif;
        padding: 8px 32px 8px 12px;
        cursor: pointer;
        outline: none;
        min-width: 120px;
    }

    .order-type-select option {
        background-color: #2A1B14;
        color: white;
        padding: 10px;
    }

    .dropdown-arrow {
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
        color: white;
    }

    /* Table Info (for Dine In) */
    .table-info {
        display: flex;
        align-items: center;
        gap: 8px;
        color: white;
        font-size: 14px;
        font-family: 'Poppins', sans-serif;
    }

    .table-number {
        color: #CA7842;
        font-weight: 600;
    }

    /* Mobile responsiveness */
    @media (max-width: 768px) {
        .checkout-navbar {
            height: auto;
            min-height: 80px;
            padding: 16px 20px;
            flex-wrap: wrap;
            justify-content: center;
            gap: 16px;
        }

        .order-type-section {
            width: 100%;
            justify-content: center;
        }

        .checkout-logo-text {
            font-size: 16px;
        }

        .checkout-title {
            font-size: 16px;
        }
    }
</style>

<!-- Checkout Navbar Container -->
<div class="checkout-navbar-container">
    <div class="checkout-navbar">
        <!-- Left: Logo and Title -->
        <div class="checkout-logo-section">
            <a href="/" style="display: flex; align-items: center; gap: 12px; text-decoration: none;">
                <div class="checkout-logo-circle"></div>
                <span class="checkout-logo-text">Meracikopi</span>
            </a>
            <div class="checkout-divider"></div>
            <span class="checkout-title">Ringkasan Pesanan</span>
        </div>

        <!-- Right: Order Type Dropdown -->
        <div class="order-type-section">
            <div class="order-type-dropdown">
                <select class="order-type-select" id="orderTypeNavbar" onchange="syncOrderType(this.value)">
                    <option value="dine_in" selected>Dine In, Meja 07</option>
                    <option value="takeaway">Takeaway</option>
                    <option value="delivery">Delivery</option>
                </select>
                <svg class="dropdown-arrow" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M6 9l6 6 6-6"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<script>
    function syncOrderType(value) {
        // Update the tab display text
        const orderTypeDisplay = document.getElementById('orderTypeDisplay');
        if (orderTypeDisplay) {
            let displayText = '';
            switch(value) {
                case 'dine_in':
                    displayText = 'Dine In';
                    break;
                case 'takeaway':
                    displayText = 'Takeaway';
                    break;
                case 'delivery':
                    displayText = 'Delivery';
                    break;
            }
            orderTypeDisplay.textContent = displayText;
        }
        console.log('Order type changed to:', value);
    }
</script>
