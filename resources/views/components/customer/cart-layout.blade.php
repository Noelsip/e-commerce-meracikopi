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
            background: linear-gradient(
                to right,
                rgba(42, 27, 20, 0.75) 0%,
                rgba(42, 27, 20, 0.45) 50%,
                rgba(42, 27, 20, 0.75) 100%
            );
            background-color: #1a1410;
        }

        /* Cart Page Styles */
        .cart-page-container {
            width: 100%;
            max-width: 1239px;
            margin: 0 auto;
            padding: 40px 20px;
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
            width: 100%;
            max-width: 1239px;
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
            background: transparent;
            transition: all 0.3s ease;
            width: 100%;
            max-width: 1239px;
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

        /* Cart Footer/Summary */
        .cart-summary {
            display: grid;
            grid-template-columns: 2fr 2fr 1fr;
            align-items: center;
            padding: 20px 24px;
            border: 1px solid #D9D9D9;
            border-radius: 8px;
            margin-top: 24px;
            background: transparent;
            width: 100%;
            max-width: 1239px;
            margin-left: auto;
            margin-right: auto;
        }

        .cart-summary-left {
            display: flex;
            align-items: center;
            gap: 24px;
        }

        .select-all-label {
            color: rgba(255, 255, 255, 0.85);
            font-size: 13px;
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
            align-items: center;
            gap: 16px;
            justify-content: flex-end;
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
            border-radius: 24px;
            padding: 12px 32px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(202, 120, 66, 0.3);
            justify-self: end;
        }

        .checkout-btn:hover {
            background-color: #d4864c;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(202, 120, 66, 0.4);
        }

        /* Responsive */
        @media (max-width: 992px) {
            .cart-table-header,
            .cart-item-row,
            .cart-summary {
                grid-template-columns: 40px 1fr;
                gap: 12px;
            }

            .cart-table-header span:not(:first-child):not(:nth-child(2)) {
                display: none;
            }

            .product-info {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }

            .cart-item-row {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .cart-summary {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .cart-summary-left {
                justify-content: center;
            }

            .cart-total-section {
                justify-content: center;
            }
        }

        @media (max-width: 600px) {
            .cart-page-container {
                padding: 20px 12px;
            }

            .product-image {
                width: 60px;
                height: 60px;
            }
        }

        /* Footer adjustments for cart */
        .footer-container {
            margin-top: 60px;
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

    <!-- Footer -->
    @include('components.customer.footer')
</body>

</html>
