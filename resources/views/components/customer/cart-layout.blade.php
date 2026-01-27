<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Cart - Meracikopi</title>

    <!-- Fonts - Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #2A1B14;
            --secondary: #CA7842;
            --accent: #B2CD9C;
            --background: #1a1410;
            --stroke-color: #D9D9D9;
        }

        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            min-height: 100vh;
            /* Background linear gradient sesuai gambar ketiga */
            background: linear-gradient(to right,
                    rgba(42, 27, 20, 0.75) 0%,
                    rgba(42, 27, 20, 0.45) 50%,
                    rgba(42, 27, 20, 0.75) 100%);
            background-color: #1a1410;
            overflow-x: hidden;
            overflow-y: auto;
            width: 100%;
            position: relative;
        }

        /* Cart Page Styles */
        .cart-page-container {
            max-width: 1360px;
            margin: 0 auto;
            padding: 40px 20px;
            padding-bottom: 140px;
            /* Space for fixed footer */
        }

        /* Cart Table Header */
        .cart-table-header {
            display: grid;
            grid-template-columns: 50px 2fr 150px 140px 150px 100px;
            align-items: center;
            padding: 16px 24px;
            border: 1px solid #D9D9D9;
            border-radius: 8px;
            margin-bottom: 16px;
            background: transparent;
            max-width: 1360px;
            margin-left: auto;
            margin-right: auto;
        }

        .cart-table-header span {
            color: rgba(255, 255, 255, 0.9);
            font-size: 14px;
            font-weight: 500;
        }

        .cart-table-header .header-harga,
        .cart-table-header .header-kuantitas,
        .cart-table-header .header-total {
            text-align: center;
        }

        .cart-table-header .header-aksi {
            text-align: center;
        }

        /* Cart Item Row */
        .cart-item-row {
            display: grid;
            grid-template-columns: 50px 2fr 150px 140px 150px 100px;
            align-items: center;
            padding: 20px 24px;
            border: 1px solid #D9D9D9;
            border-radius: 8px;
            margin-bottom: 12px;
            background: #241813;
            transition: all 0.3s ease;
            max-width: 1360px;
            margin-left: auto;
            margin-right: auto;
        }

        .cart-item-row:hover {
            background: rgba(255, 255, 255, 0.02);
        }

        /* Checkbox styling */
        .cart-checkbox {
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255, 255, 255, 0.4);
            border-radius: 4px;
            background: transparent;
            cursor: pointer;
            appearance: none;
            -webkit-appearance: none;
            position: relative;
            transition: all 0.2s ease;
            margin: 0;
        }

        .cart-checkbox:checked {
            background-color: var(--secondary);
            border-color: var(--secondary);
        }

        .cart-checkbox:checked::after {
            content: '✓';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 12px;
            font-weight: bold;
        }

        /* Product Info */
        .product-info {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .product-image {
            width: 80px;
            height: 80px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            object-fit: cover;
        }

        .product-image-placeholder {
            width: 80px;
            height: 80px;
            background-color: rgba(100, 80, 70, 0.5);
            border-radius: 8px;
        }

        .product-name {
            color: white;
            font-size: 14px;
            font-weight: 500;
        }

        /* Price */
        .product-price {
            color: rgba(255, 255, 255, 0.85);
            font-size: 14px;
            text-align: center;
        }

        /* Quantity Controls */
        .quantity-controls {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }

        .quantity-btn {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .quantity-btn-minus {
            background-color: #4b3c35;
            color: white;
        }

        .quantity-btn-plus {
            background-color: var(--secondary);
            color: white;
        }

        .quantity-btn:hover {
            transform: scale(1.1);
        }

        .quantity-value {
            color: white;
            font-size: 14px;
            min-width: 20px;
            text-align: center;
        }

        /* Total Price */
        .total-price {
            color: rgba(255, 255, 255, 0.85);
            font-size: 14px;
            text-align: center;
        }

        /* Delete Action */
        .delete-btn {
            color: rgba(255, 255, 255, 0.7);
            font-size: 13px;
            background: none;
            border: none;
            cursor: pointer;
            padding: 8px 12px;
            transition: all 0.2s ease;
            text-align: center;
            width: 100%;
        }

        .delete-btn:hover {
            color: #e74c3c;
        }

        /* Cart Item Wrapper */
        .cart-item-wrapper {
            position: relative;
            overflow: visible;
        }
        
        @media (max-width: 768px) {
            .cart-item-wrapper {
                overflow: hidden;
                margin-bottom: 8px;
            }
        }

        /* Hide swipe delete button on desktop */
        .swipe-delete-btn {
            display: none;
        }

        /* Cart Footer/Summary - Fixed at bottom */
        .cart-summary-wrapper {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            width: 100%;
            background-color: #2A1B14;
            padding: 16px 0;
            z-index: 1000;
            box-shadow: 0 -4px 10px rgba(0, 0, 0, 0.3);
            transform: translateZ(0);
            -webkit-transform: translateZ(0);
        }

        /* Inner container that matches cart-page-container */
        .cart-summary-container {
            max-width: 1360px;
            margin: 0 auto;
            padding: 0 20px;
            width: 100%;
            box-sizing: border-box;
        }

        .cart-summary {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 24px;
            border: 1px solid #D9D9D9;
            border-radius: 8px;
            background: transparent;
        }

        .cart-summary-left {
            display: flex;
            align-items: center;
            gap: 12px;
            padding-left: 16px;
        }

        .cart-summary-right {
            display: flex;
            align-items: center;
            gap: 24px;
        }

        .cart-total-section {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .select-all-text {
            color: rgba(255, 255, 255, 0.85);
            font-size: 14px;
            cursor: pointer;
        }

        .select-all-label {
            color: rgba(255, 255, 255, 0.85);
            font-size: 14px;
            cursor: pointer;
            user-select: none;
        }

        .select-all-label:hover {
            color: white;
        }

        .header-checkbox {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .select-all-checkbox {
            cursor: pointer;
        }

        .delete-selected-btn {
            color: rgba(255, 255, 255, 0.6);
            font-size: 13px;
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px 8px;
            transition: all 0.2s ease;
        }

        .delete-selected-btn:hover {
            color: #e74c3c;
        }

        .cart-total-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
        }

        .cart-total-label {
            color: rgba(255, 255, 255, 0.7);
            font-size: 14px;
        }

        .cart-total-amount {
            color: var(--secondary);
            font-size: 20px;
            font-weight: 700;
        }

        /* Checkout Button */
        .checkout-btn {
            background-color: var(--secondary);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 10px 16px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(202, 120, 66, 0.3);
            text-align: center;
        }

        .checkout-btn:hover:not(:disabled) {
            background-color: #d4864c;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(202, 120, 66, 0.4);
        }

        .checkout-btn:disabled {
            background-color: #6b5c54;
            cursor: not-allowed;
            box-shadow: none;
            opacity: 0.6;
        }

        .checkout-btn:disabled {
            background-color: #6b5c54;
            cursor: not-allowed;
            box-shadow: none;
            opacity: 0.6;
        }

        /* Mobile Responsive - Shopee Style */
        @media (max-width: 768px) {

            /* Hide desktop table header */
            .cart-table-header {
                display: none;
            }

            .cart-page-container {
                padding: 8px;
                padding-bottom: 180px;
                min-height: calc(100vh - 60px);
            }

            /* Cart item: horizontal layout like Shopee */
            .cart-item-row {
                display: grid;
                grid-template-columns: auto auto 1fr;
                gap: 12px;
                border: 1px solid #D9D9D9;
                border-radius: 8px;
                padding: 12px 8px;
                margin-bottom: 8px;
                background: #241813;
                align-items: start;
                position: relative;
            }

            .cart-item-row:hover {
                background: rgba(255, 255, 255, 0.02);
            }

            /* Checkbox column - Grid Column 1 */
            .cart-item-row>div:first-child {
                display: flex;
                align-items: flex-start;
                padding-top: 4px;
                grid-column: 1;
                grid-row: 1 / span 2;
                align-self: start;
            }

            .cart-checkbox {
                width: 18px;
                height: 18px;
            }

            /* Main content: Unwrap flex to use Grid */
            .product-info {
                display: contents;
            }

            /* Image - Grid Column 2 */
            .product-image,
            .product-image-placeholder {
                grid-column: 2;
                grid-row: 1 / span 2;
                width: 80px;
                height: 80px;
                flex-shrink: 0;
                border-radius: 4px;
            }

            /* Name - Grid Column 3, Row 1 */
            .product-name {
                grid-column: 3;
                grid-row: 1;
                font-size: 13px;
                font-weight: 400;
                line-height: 1.4;
                color: rgba(255, 255, 255, 0.9);
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
                width: 100%;
                align-self: start;
            }

            /* Quantity controls - Grid Column 3, Row 2 (Left side) */
            .cart-item-row>.quantity-controls {
                grid-column: 3;
                grid-row: 2;
                display: flex;
                align-items: center;
                justify-content: flex-start;
                gap: 8px;
                margin-top: auto;
                align-self: end;
                width: fit-content;
            }

            /* Price - Col 3, Row 2 (Right aligned) */
            .cart-item-row>.product-price {
                display: block !important;
                font-size: 15px;
                font-weight: 600;
                color: #ca7842;
                text-align: right;
                align-self: end;
                justify-self: end;
                position: relative;
                right: 0;
                width: auto;
                padding-left: 0 !important;
                margin-top: 0;
            }

            /* Total price visible on desktop, hidden on mobile */
            .cart-item-row>.total-price {
                display: none;
            }

            /* Button Styling */
            .quantity-btn {
                width: 24px;
                height: 24px;
                font-size: 14px;
                border-radius: 4px;
                background-color: rgba(255, 255, 255, 0.1);
                color: rgba(255, 255, 255, 0.8);
                border: 1px solid rgba(255, 255, 255, 0.2);
            }

            .quantity-btn-minus {
                background-color: rgba(255, 255, 255, 0.08);
            }

            .quantity-btn-plus {
                background-color: rgba(202, 120, 66, 0.3);
                color: #CA7842;
                border-color: rgba(202, 120, 66, 0.4);
            }

            .quantity-value {
                font-size: 13px;
                min-width: 24px;
                font-weight: 400;
                color: rgba(255, 255, 255, 0.9);
            }

            /* Delete button */
            .delete-btn {
                grid-column: 3;
                justify-self: end;
                color: rgba(255, 255, 255, 0.5);
                font-size: 11px;
                padding: 4px 8px;
                margin-top: 4px;
                text-align: right;
                display: none;
                /* Hide standard delete matching previous */
            }

            /* Hide desktop delete button on mobile */
            .delete-btn-desktop {
                display: none !important;
            }

            /* Swipe container - styles already defined above */

            .cart-item-wrapper .cart-item-row {
                transition: transform 0.3s ease;
                margin-bottom: 0;
                background: #241813;
            }

            .cart-item-wrapper .cart-item-row.swiped {
                transform: translateX(-80px);
            }

            /* Swipe delete button - hidden by default */
            .swipe-delete-btn {
                position: absolute;
                right: -80px;
                top: 0;
                bottom: 0;
                width: 80px;
                background: #e74c3c;
                color: white;
                border: none;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                opacity: 0;
                transition: right 0.3s ease, opacity 0.3s ease;
            }

            /* Show delete button when swiped */
            .cart-item-wrapper:has(.cart-item-row.swiped) .swipe-delete-btn {
                right: 0;
                opacity: 1;
            }



            .swipe-delete-btn:hover {
                background: #c0392b;
            }

            .swipe-delete-btn svg {
                width: 20px;
                height: 20px;
            }

            /* Remove ::after subtotal */
            .cart-item-row::after {
                display: none;
            }

            /* Fixed Bottom Bar */
            .cart-summary-wrapper {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                background: #2A1B14;
                padding: 8px 12px;
                box-shadow: 0 -2px 8px rgba(0, 0, 0, 0.2);
                border-top: 1px solid rgba(255, 255, 255, 0.1);
                z-index: 9999 !important;
                bottom: 0 !important;
            }

            .cart-summary {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 8px;
                max-width: 100%;
                padding: 0;
                border: none;
                background: transparent;
            }

            .cart-summary-left {
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .select-all-text {
                font-size: 12px;
                white-space: nowrap;
            }

            .cart-summary-right {
                display: flex;
                align-items: center;
                gap: 12px;
            }

            .delete-selected-btn {
                display: none;
            }

            .cart-total-section {
                display: flex;
                flex-direction: column;
                align-items: flex-end;
                justify-content: center;
                gap: 0;
                margin-right: 4px;
            }

            .cart-total-label {
                font-size: 10px;
                color: rgba(255, 255, 255, 0.6);
                line-height: 1.2;
                text-align: right;
            }

            .cart-total-amount {
                font-size: 14px;
                font-weight: 700;
                color: #ca7842;
                line-height: 1.2;
            }

            .swipe-delete-btn {
                gap: 4px;
                font-size: 11px;
                font-weight: 500;
                cursor: pointer;
                transition: background 0.2s ease;
            }

            .swipe-delete-btn:hover {
                background: #c0392b;
            }

            .swipe-delete-btn svg {
                width: 20px;
                height: 20px;
            }

            /* Remove ::after subtotal */
            .cart-item-row::after {
                display: none;
            }

            /* Fixed Bottom Bar */
            .cart-summary-wrapper {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                width: 100%;
                background: #2A1B14;
                padding: 8px 12px;
                box-shadow: 0 -2px 8px rgba(0, 0, 0, 0.2);
                border-top: 1px solid rgba(255, 255, 255, 0.1);
                z-index: 1000;
                transform: translateZ(0);
                -webkit-transform: translateZ(0);
                -webkit-backface-visibility: hidden;
                backface-visibility: hidden;
            }

            .cart-summary {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 8px;
                max-width: 100%;
                padding: 0;
                border: none;
                background: transparent;
            }

            .cart-summary-left {
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .select-all-text {
                font-size: 12px;
                white-space: nowrap;
            }

            .cart-summary-right {
                display: flex;
                align-items: center;
                gap: 12px;
            }

            .delete-selected-btn {
                display: none;
            }

            .cart-total-section {
                display: flex;
                flex-direction: column;
                align-items: flex-end;
                justify-content: center;
                gap: 0;
                margin-right: 4px;
            }

            .cart-total-label {
                font-size: 10px;
                color: rgba(255, 255, 255, 0.6);
                line-height: 1.2;
                text-align: right;
            }

            .cart-total-amount {
                font-size: 14px;
                font-weight: 700;
                color: #ca7842;
                line-height: 1.2;
            }

            /* Checkout button */
            .checkout-btn {
                padding: 8px 16px;
                border-radius: 6px;
                font-size: 13px;
                font-weight: 600;
                white-space: nowrap;
                height: 36px;
                display: flex;
                align-items: center;
            }
        }

        @media (max-width: 480px) {
            .cart-page-container {
                padding: 6px;
                padding-bottom: 160px;
            }

            .cart-summary-wrapper {
                padding: 0 15px !important;
                /* 6px container + 1px border + 8px card padding = 15px */
            }

            .product-image,
            .product-image-placeholder {
                width: 70px;
                height: 70px;
            }

            .product-name {
                font-size: 12px;
            }

            .product-price {
                font-size: 14px;
                padding-left: 82px !important;
            }

            .quantity-btn {
                width: 22px;
                height: 22px;
                font-size: 13px;
            }

            .cart-total-amount {
                font-size: 15px;
            }

            .checkout-btn {
                padding: 9px 20px;
                font-size: 13px;
            }

            /* Ensure checkout bar is visible */
            .cart-summary-wrapper {
                position: fixed !important;
                bottom: 0 !important;
                left: 0 !important;
                right: 0 !important;
                z-index: 9999 !important;
            }
        }

        /* Footer adjustments for cart */
        .footer-container {
            margin-top: 60px;
            padding-bottom: 100px;
        }

        @media (max-width: 768px) {
            .footer-container {
                margin-top: 40px;
                padding-bottom: 80px !important;
            }
        }
    </style>
</head>

<body>
    <!-- Cart Navbar -->
    @include('components.customer.cart-navbar')

    <!-- Main Content -->
    <main>
        {{ $slot }}
    </main>

    <!-- Footer - Hidden when cart has items (controlled by Alpine.js) -->
    @include('components.customer.footer')
</body>

</html>