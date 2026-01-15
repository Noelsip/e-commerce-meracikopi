<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Ringkasan Pesanan - Meracikopi</title>

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
            /* Background linear gradient */
            background: linear-gradient(
                to right,
                rgba(42, 27, 20, 0.75) 0%,
                rgba(42, 27, 20, 0.45) 50%,
                rgba(42, 27, 20, 0.75) 100%
            );
            background-color: #1a1410;
        }

        /* Checkout Page Container */
        .checkout-page-container {
            width: 100%;
            max-width: 1239px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        /* Order Type Tabs */
        .order-type-tabs {
            display: flex;
            gap: 40px;
            margin-bottom: 30px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            padding-bottom: 12px;
        }

        .order-type-tab-label {
            color: rgba(255, 255, 255, 0.6);
            font-size: 14px;
            font-weight: 500;
        }

        .order-type-tab-value {
            color: white;
            font-size: 14px;
            font-weight: 500;
            position: relative;
            padding-bottom: 12px;
            margin-bottom: -12px;
        }

        .order-type-tab-value::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: var(--secondary);
        }

        /* Main Content Grid */
        .checkout-content {
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            gap: 30px;
            align-items: start;
        }

        /* Section Title */
        .section-title {
            color: rgba(255, 255, 255, 0.7);
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 16px;
        }

        /* Order Items Section */
        .order-items-section {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        /* Order Summary Section - Sticky */
        .order-summary-section {
            position: sticky;
            top: 20px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        /* Order Item Card */
        .order-item-card {
            display: flex;
            align-items: center;
            padding: 16px 20px;
            border: 1px solid #D9D9D9;
            border-radius: 8px;
            background: transparent;
            gap: 16px;
        }

        .order-item-checkbox {
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
            flex-shrink: 0;
        }

        .order-item-checkbox:checked {
            background-color: var(--secondary);
            border-color: var(--secondary);
        }

        .order-item-checkbox:checked::after {
            content: '✓';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 12px;
            font-weight: bold;
        }

        .order-item-image {
            width: 80px;
            height: 80px;
            background-color: rgba(100, 80, 70, 0.5);
            border-radius: 8px;
            flex-shrink: 0;
        }

        .order-item-details {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .order-item-name {
            color: white;
            font-size: 14px;
            font-weight: 500;
        }

        .order-item-quantity {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .quantity-dropdown {
            appearance: none;
            -webkit-appearance: none;
            background-color: rgba(75, 60, 53, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 4px;
            color: white;
            font-size: 12px;
            padding: 6px 24px 6px 10px;
            cursor: pointer;
            outline: none;
        }

        .quantity-wrapper {
            position: relative;
            display: inline-block;
        }

        .quantity-wrapper::after {
            content: '▼';
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            color: white;
            font-size: 8px;
            pointer-events: none;
        }

        .order-item-delete {
            color: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            font-size: 18px;
            transition: color 0.2s ease;
        }

        .order-item-delete:hover {
            color: #e74c3c;
        }

        .order-item-price {
            color: var(--secondary);
            font-size: 16px;
            font-weight: 600;
            margin-left: auto;
        }

        .order-summary-card {
            padding: 24px;
            border: 1px solid #D9D9D9;
            border-radius: 8px;
            background: transparent;
        }

        .summary-title {
            color: white;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .summary-label {
            color: rgba(255, 255, 255, 0.7);
            font-size: 14px;
        }

        .summary-value {
            color: white;
            font-size: 14px;
        }

        .summary-value.strikethrough {
            text-decoration: line-through;
            color: rgba(255, 255, 255, 0.5);
        }

        .summary-divider {
            height: 1px;
            background-color: rgba(255, 255, 255, 0.1);
            margin: 16px 0;
        }

        .summary-total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .summary-total-label {
            color: white;
            font-size: 16px;
            font-weight: 600;
        }

        .summary-total-value {
            color: var(--secondary);
            font-size: 20px;
            font-weight: 700;
        }

        /* Checkout Button */
        .checkout-btn {
            width: 100%;
            background-color: var(--secondary);
            color: white;
            border: none;
            border-radius: 24px;
            padding: 14px 32px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(202, 120, 66, 0.3);
            margin-top: 8px;
        }

        .checkout-btn:hover {
            background-color: #d4864c;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(202, 120, 66, 0.4);
        }

        /* Payment Methods Section */
        .payment-methods-section {
            margin-top: 40px;
        }

        .payment-method-card {
            display: flex;
            align-items: center;
            padding: 16px 20px;
            border: 1px solid #D9D9D9;
            border-radius: 8px;
            background: transparent;
            margin-bottom: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .payment-method-card:hover {
            background: rgba(255, 255, 255, 0.02);
        }

        .payment-method-card.selected {
            border-color: var(--secondary);
            background: rgba(202, 120, 66, 0.1);
        }

        .payment-radio {
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255, 255, 255, 0.4);
            border-radius: 50%;
            background: transparent;
            cursor: pointer;
            appearance: none;
            -webkit-appearance: none;
            position: relative;
            transition: all 0.2s ease;
            margin-right: 16px;
            flex-shrink: 0;
        }

        .payment-radio:checked {
            border-color: var(--secondary);
        }

        .payment-radio:checked::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 10px;
            height: 10px;
            background-color: var(--secondary);
            border-radius: 50%;
        }

        .payment-method-name {
            color: white;
            font-size: 14px;
            font-weight: 500;
        }

        .payment-method-icon {
            margin-left: auto;
            width: 40px;
            height: 24px;
            object-fit: contain;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .checkout-content {
                grid-template-columns: 1fr;
            }

            .order-summary-section {
                order: -1;
                position: relative;
                top: 0;
            }
        }

        @media (max-width: 600px) {
            .checkout-page-container {
                padding: 20px 12px;
            }

            .order-item-card {
                flex-wrap: wrap;
            }

            .order-item-price {
                width: 100%;
                text-align: right;
                margin-top: 8px;
            }
        }

        /* Footer adjustments */
        .footer-container {
            margin-top: 60px;
        }
    </style>
</head>

<body>
    <!-- Checkout Navbar -->
    @include('components.customer.checkout-navbar')

    <!-- Main Content -->
    <main>
        {{ $slot }}
    </main>

    <!-- Footer -->
    @include('components.customer.footer')
</body>

</html>
