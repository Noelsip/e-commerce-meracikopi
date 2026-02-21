<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Order History - Meracikopi</title>

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
            background: linear-gradient(to right,
                    rgba(42, 27, 20, 0.75) 0%,
                    rgba(42, 27, 20, 0.45) 50%,
                    rgba(42, 27, 20, 0.75) 100%);
            background-color: #1a1410;
            overflow-x: hidden;
            width: 100%;
        }

        /* Hide announcement bar on this page */
        .announcement-bar {
            display: none !important;
        }

        /* Make navbar fixed at top */
        .navbar-container {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            z-index: 1000 !important;
        }

        /* Add padding to body to compensate for fixed navbar */
        body {
            padding-top: 100px !important;
        }

        /* Order History Page Styles */
        .order-history-container {
            max-width: 1360px;
            margin: 0 auto;
            padding: 40px 60px;
            padding-bottom: 80px;
        }

        .order-history-title {
            font-size: 32px;
            font-weight: 500;
            color: #FFF4D6;
            margin-bottom: 40px;
            font-style: italic;
        }

        .orders-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
            align-items: center;
        }

        /* Order Card - Compact design */
        .order-card {
            width: 100%;
            max-width: 1200px;
            border: 1px solid #D9D9D9;
            border-radius: 20px;
            background: transparent;
            padding: 28px 40px;
            display: flex;
            flex-direction: column;
            gap: 14px;
            position: relative;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .order-card:hover {
            border-color: #CA7842;
            background: rgba(202, 120, 66, 0.05);
        }

        .order-card-content {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 12px;
        }

        /* Left Section */
        .order-left-section {
            display: flex;
            flex-direction: column;
            gap: 6px;
            flex: 1;
        }

        .order-id-row {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 4px;
        }

        .order-id {
            font-size: 18px;
            font-weight: 600;
            color: white;
        }

        /* Status Badges */
        .status-badge {
            padding: 5px 14px;
            border-radius: 14px;
            font-size: 12px;
            font-weight: 600;
            text-transform: capitalize;
        }

        .status-completed {
            background-color: #22c55e;
            color: white;
        }

        .status-cancelled {
            background-color: #ef4444;
            color: white;
        }

        .status-waiting,
        .status-pending_payment,
        .status-created,
        .status-processing {
            background-color: #eab308;
            color: #1a1410;
        }

        .status-paid,
        .status-ready {
            background-color: #3b82f6;
            color: white;
        }

        .status-on_delivery {
            background-color: #8b5cf6;
            color: white;
        }

        .order-type-row {
            display: flex;
            align-items: center;
            gap: 12px;
            color: rgba(255, 255, 255, 0.75);
            font-size: 16px;
        }

        .order-type-row svg {
            width: 20px;
            height: 20px;
            opacity: 0.9;
        }

        .order-date {
            color: rgba(255, 255, 255, 0.55);
            font-size: 14px;
        }

        /* Price and items info - inline */
        .order-price-info {
            font-size: 15px;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 4px;
        }

        .order-price-info .price {
            font-weight: 600;
            color: white;
        }

        .order-price-info .items-count {
            color: #CA7842;
        }

        /* Arrow icon */
        .order-arrow {
            color: rgba(255, 255, 255, 0.5);
            flex-shrink: 0;
        }

        .order-arrow svg {
            width: 20px;
            height: 20px;
        }

        /* Hide center section on new design */
        .order-center-section {
            display: none;
        }

        /* Hide right section on new design */
        .order-right-section {
            display: none;
        }

        /* Divider */
        .order-divider {
            display: none;
        }

        /* Bottom Section - Order type + Order Again */
        .order-bottom-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 8px;
            border-top: 1px solid rgba(217, 217, 217, 0.15);
        }

        .order-card-footer-left {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        /* Order type label */
        .order-type-label {
            font-size: 12px;
            color: #CA7842;
            font-weight: 500;
        }

        /* Table Number */
        .order-table-number {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.5);
        }

        .order-view-detail {
            display: none;
        }

        /* Order Again Button inside card */
        .order-again-btn {
            background-color: var(--secondary);
            color: white;
            border: none;
            border-radius: 16px;
            padding: 8px 20px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .order-again-btn:hover {
            background-color: #d4864c;
        }

        /* Empty State */
        .empty-orders {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 60px 20px;
            color: rgba(255, 255, 255, 0.6);
            min-height: 300px;
        }

        .empty-orders svg {
            width: 60px;
            height: 60px;
            margin-bottom: 16px;
            opacity: 0.5;
        }

        .empty-orders h3 {
            font-size: 16px;
            margin-bottom: 6px;
            color: rgba(255, 255, 255, 0.8);
        }

        .empty-orders p {
            font-size: 13px;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .order-history-container {
                padding: 20px 16px;
            }

            .order-history-title {
                font-size: 22px;
                margin-bottom: 20px;
            }

            .order-card {
                padding: 16px;
                border-radius: 12px;
            }

            .order-id {
                font-size: 14px;
            }

            .status-badge {
                font-size: 9px;
                padding: 2px 8px;
            }

            .order-again-btn {
                padding: 6px 16px;
                font-size: 11px;
            }
        }

        @media (max-width: 480px) {
            .order-card {
                padding: 14px;
            }

            .order-id-row {
                gap: 8px;
            }
        }

        .order-total-amount {
            font-size: 18px;
        }
        }

        /* Order Detail Modal - Popup Style */
        #orderModalRoot {
            position: fixed;
            top: 0;
            left: 0;
            width: 0;
            height: 0;
            z-index: 99999;
        }

        .order-modal-overlay {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            width: 100vw !important;
            height: 100vh !important;
            background: rgba(0, 0, 0, 0.7);
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            z-index: 99999 !important;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            padding: 20px;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }

        .order-modal-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        .order-modal {
            position: relative !important;
            background: linear-gradient(145deg, #2A1B14, #1e1510);
            border: 1px solid rgba(202, 120, 66, 0.3);
            border-radius: 20px;
            max-width: 420px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
            transform: scale(0.8) translateY(20px);
            opacity: 0;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5), 0 0 0 1px rgba(255, 255, 255, 0.05);
        }

        .order-modal-overlay.show .order-modal {
            transform: scale(1) translateY(0);
            opacity: 1;
        }

        .order-modal-header {
            padding: 20px 24px 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            position: relative;
        }

        .order-modal-close {
            position: absolute;
            top: 12px;
            right: 12px;
            background: transparent;
            border: none;
            color: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            padding: 6px;
            transition: color 0.2s;
        }

        .order-modal-close:hover {
            color: white;
        }

        .order-modal-close svg {
            width: 18px;
            height: 18px;
        }

        .order-modal-icon {
            width: 36px;
            height: 36px;
            margin-bottom: 10px;
            color: rgba(255, 255, 255, 0.8);
        }

        .order-modal-label {
            font-size: 10px;
            color: rgba(255, 255, 255, 0.5);
            margin-bottom: 4px;
        }

        .order-modal-code {
            font-size: 15px;
            font-weight: 700;
            color: white;
            margin-bottom: 4px;
        }

        .order-modal-date {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.5);
            margin-bottom: 8px;
        }

        .order-modal-body {
            padding: 0 24px 20px;
        }

        /* Order Info Box */
        .order-info-box {
            display: grid;
            grid-template-columns: 1fr 1fr;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            margin-bottom: 16px;
            overflow: hidden;
        }

        .order-info-item {
            padding: 12px 14px;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .order-info-item:first-child {
            border-right: 1px solid rgba(255, 255, 255, 0.1);
        }

        .order-info-label {
            font-size: 10px;
            color: rgba(255, 255, 255, 0.5);
            text-transform: capitalize;
        }

        .order-info-value {
            font-size: 13px;
            color: white;
            font-weight: 600;
        }

        .order-info-sub {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.5);
        }

        .order-info-value-with-icon {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: white;
            font-weight: 600;
        }

        .order-info-value-with-icon svg {
            width: 14px;
            height: 14px;
            opacity: 0.8;
        }

        /* Items Section */
        .order-modal-section-title {
            font-size: 12px;
            font-weight: 600;
            color: white;
            margin-bottom: 10px;
        }

        .order-modal-items {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 16px;
        }

        .order-modal-item {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .order-modal-item-left {
            display: flex;
            flex-direction: column;
            gap: 1px;
        }

        .order-modal-item-name {
            font-size: 13px;
            font-weight: 500;
            color: white;
        }

        .order-modal-item-variant {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.5);
        }

        .order-modal-item-price {
            font-size: 13px;
            font-weight: 500;
            color: white;
        }

        /* Summary Section */
        .order-modal-summary {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 12px 14px;
            margin-bottom: 16px;
        }

        .order-modal-summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .order-modal-summary-label {
            font-size: 13px;
            font-weight: 500;
            color: white;
        }

        .order-modal-summary-value {
            font-size: 16px;
            font-weight: 700;
            color: #CA7842;
        }

        /* Note Section */
        .order-modal-note {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 12px 14px;
            margin-bottom: 16px;
        }

        .order-modal-note-title {
            font-size: 12px;
            font-weight: 600;
            color: white;
            margin-bottom: 4px;
        }

        .order-modal-note-text {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.6);
        }

        /* Modal Buttons */
        .order-modal-buttons {
            display: flex;
            gap: 10px;
        }

        .order-modal-btn {
            flex: 1;
            padding: 10px 20px;
            border-radius: 24px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            text-align: center;
            text-decoration: none;
        }

        .order-modal-btn-primary {
            background: #CA7842;
            color: white;
            border: none;
        }

        .order-modal-btn-primary:hover {
            background: #d4864c;
        }

        .order-modal-btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .order-modal-btn-secondary:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .order-modal-btn-pay {
            background: #22c55e;
            color: white;
            border: none;
            font-weight: 700;
        }

        .order-modal-btn-pay:hover {
            background: #16a34a;
        }

        /* Pay Now Button on card */
        .order-pay-now-btn {
            background-color: #ef4444;
            color: white;
            border: none;
            border-radius: 16px;
            padding: 8px 20px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            animation: pulse-red 2s infinite;
        }

        .order-pay-now-btn:hover {
            background-color: #dc2626;
        }

        @keyframes pulse-red {
            0%, 100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4); }
            50% { box-shadow: 0 0 0 6px rgba(239, 68, 68, 0); }
        }

        /* Payment status badge colors */
        .status-pay-pending  { background-color: #eab308; color: #1a1410; }
        .status-pay-processing { background-color: #f97316; color: white; }
        .status-pay-paid     { background-color: #22c55e; color: white; }
        .status-pay-failed   { background-color: #ef4444; color: white; }
        .status-pay-expired  { background-color: #6b7280; color: white; }
        .status-pay-cancelled{ background-color: #ef4444; color: white; }

        /* Order process status badge colors */
        .status-order-pending     { background-color: #eab308; color: #1a1410; }
        .status-order-processing  { background-color: #3b82f6; color: white; }
        .status-order-ready       { background-color: #22c55e; color: white; }
        .status-order-on_delivery { background-color: #f97316; color: white; }
        .status-order-completed   { background-color: #22c55e; color: white; }
        .status-order-cancelled   { background-color: #ef4444; color: white; }

        /* Modal Responsive - Keep as centered popup */
        @media (max-width: 768px) {
            .order-modal-overlay {
                padding: 16px !important;
                align-items: center !important;
                justify-content: center !important;
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                width: 100vw !important;
                height: 100vh !important;
            }

            .order-modal {
                position: relative !important;
                border-radius: 20px;
                max-height: 85vh;
                max-width: 95%;
                width: 95%;
            }

            .order-modal-header {
                padding: 20px 20px 16px;
            }

            .order-modal-icon {
                width: 32px;
                height: 32px;
                margin-bottom: 8px;
            }

            .order-modal-label {
                font-size: 9px;
            }

            .order-modal-code {
                font-size: 16px;
            }

            .order-modal-date {
                font-size: 10px;
            }

            .order-modal-body {
                padding: 0 16px 20px;
            }

            .order-info-box {
                grid-template-columns: 1fr;
                gap: 0;
            }

            .order-info-item {
                padding: 10px 12px;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            }

            .order-info-item:first-child {
                border-right: none;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            }

            .order-info-item:last-child {
                border-bottom: none;
            }

            .order-info-label {
                font-size: 9px;
            }

            .order-info-value {
                font-size: 12px;
            }

            .order-info-sub {
                font-size: 10px;
            }

            .order-info-value-with-icon {
                font-size: 12px;
            }

            .order-info-value-with-icon svg {
                width: 12px;
                height: 12px;
            }

            .order-modal-section-title {
                font-size: 11px;
            }

            .order-modal-items {
                gap: 8px;
            }

            .order-modal-item-name {
                font-size: 12px;
            }

            .order-modal-item-variant {
                font-size: 10px;
            }

            .order-modal-item-price {
                font-size: 12px;
            }

            .order-modal-summary {
                padding: 10px 12px;
                border-radius: 8px;
            }

            .order-modal-summary-row {
                font-size: 11px;
            }

            .order-modal-summary-total {
                font-size: 13px;
                padding-top: 8px;
            }

            .order-modal-close {
                top: 16px;
                right: 16px;
            }
        }

        /* Small Mobile */
        @media (max-width: 400px) {
            .order-modal-header {
                padding: 16px 16px 14px;
            }

            .order-modal-body {
                padding: 0 12px 16px;
            }

            .order-info-item {
                padding: 8px 10px;
            }

            .order-modal-code {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    @include('components.customer.navbar')

    <!-- Order Detail Modal - OUTSIDE CONTAINER for proper fixed positioning -->
    <div id="orderModalRoot" x-data="orderModal()" @open-order-detail.window="openOrderDetail($event.detail)">
        <div class="order-modal-overlay" :class="{ 'show': showModal }" @click.self="closeOrderDetail()">
            <div class="order-modal" x-show="selectedOrder">
                <!-- Modal Header -->
                <div class="order-modal-header">
                    <button class="order-modal-close" @click="closeOrderDetail()">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <!-- Receipt Icon -->
                    <svg class="order-modal-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                    </svg>

                    <span class="order-modal-label">Nomor Order</span>
                    <span class="order-modal-code" x-text="selectedOrder?.order_code"></span>
                    <span class="order-modal-date" x-text="formatDate(selectedOrder?.created_at)"></span>
                    <span class="status-badge" :class="'status-' + selectedOrder?.status"
                        x-text="getStatusLabel(selectedOrder?.status)"></span>
                </div>

                <!-- Modal Body -->
                <div class="order-modal-body">
                    <!-- Customer Info Box -->
                    <div class="order-info-box" style="margin-bottom: 16px;">
                        <div class="order-info-item">
                            <span class="order-info-label">Nama Pelanggan</span>
                            <span class="order-info-value" x-text="selectedOrder?.customer_name || '-'"></span>
                        </div>
                        <div class="order-info-item">
                            <span class="order-info-label">No. Telepon</span>
                            <span class="order-info-value" x-text="selectedOrder?.customer_phone || '-'"></span>
                        </div>
                    </div>

                    <!-- Order Type & Payment Method Box -->
                    <div class="order-info-box">
                        <div class="order-info-item">
                            <span class="order-info-label">Order Type</span>
                            <span class="order-info-value" x-text="getOrderTypeLabel(selectedOrder?.order_type)"></span>
                        </div>
                        <div class="order-info-item">
                            <span class="order-info-label">Payment Method</span>
                            <div class="order-info-value-with-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                                <span x-text="selectedOrder?.payment_method || 'QRIS'"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Status Pembayaran & Status Pesanan -->
                    <div class="order-info-box" style="margin-bottom: 16px;">
                        <div class="order-info-item">
                            <span class="order-info-label">Status Pembayaran</span>
                            <span class="status-badge" :class="'status-pay-' + (selectedOrder?.payment_status || 'pending')" x-text="selectedOrder?.payment_status_label || 'Menunggu Pembayaran'"></span>
                        </div>
                        <div class="order-info-item">
                            <span class="order-info-label">Status Pesanan</span>
                            <span class="status-badge" :class="'status-order-' + (selectedOrder?.order_status || 'pending')" x-text="selectedOrder?.order_status_label || 'Menunggu Diproses'"></span>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <h3 class="order-modal-section-title">Order Items</h3>
                    <div class="order-modal-items">
                        <template x-if="!selectedOrder?.items || selectedOrder?.items.length === 0">
                            <div class="order-modal-item"
                                style="justify-content: center; color: rgba(255,255,255,0.5);">
                                <span>Tidak ada item</span>
                            </div>
                        </template>
                        <template x-for="item in selectedOrder?.items" :key="item.id">
                            <div class="order-modal-item">
                                <div class="order-modal-item-left">
                                    <span class="order-modal-item-name" x-text="item.menu_name"></span>
                                    <span class="order-modal-item-variant" x-text="'Qty: ' + item.quantity"></span>
                                </div>
                                <span class="order-modal-item-price"
                                    x-text="'RP ' + formatPrice(item.price * item.quantity)"></span>
                            </div>
                        </template>
                    </div>

                    <!-- Total -->
                    <div class="order-modal-summary">
                        <div class="order-modal-summary-row">
                            <span class="order-modal-summary-label">Total</span>
                            <span class="order-modal-summary-value"
                                x-text="'RP ' + formatPrice(selectedOrder?.final_price || selectedOrder?.total_price)"></span>
                        </div>
                    </div>

                    <!-- Note for Barista -->
                    <div class="order-modal-note" x-show="selectedOrder?.notes">
                        <div class="order-modal-note-title">Note For Barista</div>
                        <div class="order-modal-note-text" x-text="selectedOrder?.notes || '-'"></div>
                    </div>

                    <!-- Buttons -->
                    <div class="order-modal-buttons">
                        <template x-if="selectedOrder?.status === 'pending_payment'">
                            <a :href="'/checkout?reorder=' + selectedOrder?.id" class="order-modal-btn order-modal-btn-pay" @click.prevent="closeOrderDetail(); window.location.href = '/customer/order-history'">Bayar Sekarang</a>
                        </template>
                        <template x-if="selectedOrder?.status !== 'pending_payment'">
                            <a :href="'{{ route('catalogs.index') }}'" class="order-modal-btn order-modal-btn-primary">Order Again</a>
                        </template>
                        <button class="order-modal-btn order-modal-btn-secondary"
                            @click="closeOrderDetail()">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main>
        <div class="order-history-container" x-data="orderHistory()" x-init="loadOrders()">
            <h1 class="order-history-title">Riwayat Pesanan</h1>

            <!-- Loading State -->
            <div x-show="loading" style="text-align: center; padding: 40px; color: rgba(255,255,255,0.7);">
                <p>Memuat riwayat pesanan...</p>
            </div>

            <!-- Empty State -->
            <div x-show="!loading && orders.length === 0" class="empty-orders">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <h3>Belum ada pesanan</h3>
                <p>Anda belum memiliki riwayat pesanan</p>
            </div>

            <!-- Order Cards Container -->
            <div class="orders-list" x-show="!loading && orders.length > 0">
                <!-- Order Cards -->
                <template x-for="order in orders" :key="order.id">
                    <div class="order-card" @click="openOrderDetail(order)">
                        <div class="order-card-content">
                            <!-- Left Section -->
                            <div class="order-left-section">
                                <div class="order-id-row">
                                    <span class="order-id" x-text="order.order_code"></span>
                                    <span class="status-badge" :class="'status-order-' + (order.order_status || 'pending')"
                                        x-text="order.order_status_label || 'Menunggu Diproses'"></span>
                                </div>
                                <div class="order-date" x-text="formatDate(order.created_at)"></div>
                            </div>

                            <!-- Center Section - Simple items summary -->
                            <div class="order-center-section">
                                <div class="order-items-count" x-text="order.items.length + ' items'"></div>
                                <div class="order-items-preview" x-text="getItemsPreview(order.items)"></div>
                            </div>

                            <!-- Right Section -->
                            <div class="order-right-section">
                                <div class="order-total-label">Total</div>
                                <div class="order-total-amount"
                                    x-text="'RP ' + formatPrice(order.final_price || order.total_price)"></div>
                            </div>
                        </div>

                        <div class="order-divider"></div>

                        <div class="order-bottom-section">
                            <div class="order-card-footer-left">
                                <span class="order-view-detail">Klik untuk lihat detail</span>
                            </div>
                            <template x-if="order.status === 'pending_payment'">
                                <span class="order-pay-now-btn" @click.stop="openOrderDetail(order)">Bayar Sekarang</span>
                            </template>
                            <template x-if="order.status !== 'pending_payment'">
                                <a href="{{ route('catalogs.index') }}" class="order-again-btn" @click.stop>Order Again</a>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </main>

    <!-- Footer -->
    @include('components.customer.footer')

    <script>
        // Order Modal Component - separate from orderHistory for proper fixed positioning
        function orderModal() {
            return {
                showModal: false,
                selectedOrder: null,

                openOrderDetail(order) {
                    this.selectedOrder = order;
                    this.showModal = true;
                    document.body.style.overflow = 'hidden';
                },

                closeOrderDetail() {
                    this.showModal = false;
                    this.selectedOrder = null;
                    document.body.style.overflow = '';
                },

                getStatusLabel(status) {
                    const labels = {
                        'created': 'Created',
                        'pending_payment': 'Waiting',
                        'paid': 'Paid',
                        'processing': 'Processing',
                        'ready': 'Ready',
                        'on_delivery': 'On Delivery',
                        'completed': 'Completed',
                        'cancelled': 'Cancelled'
                    };
                    return labels[status] || status;
                },

                getOrderTypeLabel(type) {
                    const labels = {
                        'dine_in': 'Dine In',
                        'delivery': 'Delivery',
                        'takeaway': 'Takeaway'
                    };
                    return labels[type] || type;
                },

                formatDate(dateString) {
                    if (!dateString) return '';
                    const date = new Date(dateString);
                    const options = {
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    };
                    return date.toLocaleDateString('id-ID', options).replace(',', ' pukul');
                },

                formatPrice(price) {
                    return new Intl.NumberFormat('id-ID').format(price);
                }
            }
        }

        // Order History Component
        function orderHistory() {
            return {
                orders: [],
                loading: true,
                _refreshTimer: null,

                // Open order detail modal - dispatch event to modal component
                openOrderDetail(order) {
                    window.dispatchEvent(new CustomEvent('open-order-detail', { detail: order }));
                },

                loadOrders() {
                    // Get guest token
                    const guestToken = localStorage.getItem('guest_token');

                    this.loading = true;
                    this.orders = [];

                    // If guest token exists, load real orders from API
                    if (guestToken) {
                        fetch('/api/customer/orders', {
                            method: 'GET',
                            headers: {
                                'Accept': 'application/json',
                                'X-Guest-Token': guestToken
                            }
                        })
                            .then(response => response.json())
                            .then(data => {
                                console.log('Orders API response:', data);
                                if (data.data && data.data.length > 0) {
                                    this.orders = data.data.map(order => {
                                        // Items sudah di-return dari API sebagai 'items', bukan 'order_items'
                                        const items = (order.items || order.order_items || []).map(item => ({
                                            id: item.menu_id || item.id,
                                            menu_name: item.menu_name || item.name || 'Unknown Item',
                                            quantity: item.quantity || 1,
                                            price: item.price || 0,
                                            variant: item.variant || item.notes || ''
                                        }));

                                        console.log('Order items for', order.id, ':', items);
                                        console.log('Payment method for', order.id, ':', order.payment?.method);

                                        return {
                                            ...order,
                                            order_code: this.generateOrderCode(order),
                                            items: items,
                                            payment_method: this.formatPaymentMethod(order.payment?.method)
                                        };
                                    });

                                    // Auto-refresh jika ada order yang masih pending payment
                                    const hasPending = this.orders.some(o => o.status === 'pending_payment');
                                    if (hasPending && !this._refreshTimer) {
                                        this._refreshTimer = setInterval(() => this.refreshOrders(), 10000);
                                    }
                                }
                                this.loading = false;
                            })
                            .catch(error => {
                                console.error('Error loading orders:', error);
                                this.loading = false;
                            });
                    } else {
                        this.loading = false;
                    }
                },

                // Refresh orders silently (tanpa show loading) untuk auto-update status
                refreshOrders() {
                    const guestToken = localStorage.getItem('guest_token');
                    if (!guestToken) return;

                    fetch('/api/customer/orders', {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-Guest-Token': guestToken
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.data && data.data.length > 0) {
                                this.orders = data.data.map(order => ({
                                    ...order,
                                    order_code: this.generateOrderCode(order),
                                    items: (order.items || []).map(item => ({
                                        id: item.menu_id || item.id,
                                        menu_name: item.menu_name || item.name || 'Unknown Item',
                                        quantity: item.quantity || 1,
                                        price: item.price || 0,
                                        variant: item.variant || item.notes || ''
                                    })),
                                    payment_method: this.formatPaymentMethod(order.payment?.method)
                                }));

                                // Stop timer jika tidak ada pending lagi
                                const hasPending = this.orders.some(o => o.status === 'pending_payment');
                                if (!hasPending && this._refreshTimer) {
                                    clearInterval(this._refreshTimer);
                                    this._refreshTimer = null;
                                }
                            }
                        })
                        .catch(() => {}); // silent fail on refresh
                },

                generateOrderCode(order) {
                    const date = new Date(order.created_at);
                    const year = date.getFullYear();
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const day = String(date.getDate()).padStart(2, '0');
                    const id = String(order.id).padStart(3, '0');
                    return `MRK-${year}${month}${day}-${id}`;
                },

                getStatusLabel(status) {
                    const labels = {
                        'created': 'Created',
                        'pending_payment': 'Waiting',
                        'paid': 'Paid',
                        'processing': 'Processing',
                        'ready': 'Ready',
                        'on_delivery': 'On Delivery',
                        'completed': 'Completed',
                        'cancelled': 'Cancelled'
                    };
                    return labels[status] || status;
                },

                getItemsPreview(items) {
                    if (!items || items.length === 0) return '';
                    const names = items.slice(0, 2).map(item => item.menu_name);
                    if (items.length > 2) {
                        return names.join(', ') + ', +' + (items.length - 2) + ' lainnya';
                    }
                    return names.join(', ');
                },

                formatDate(dateString) {
                    const date = new Date(dateString);
                    const options = {
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    };
                    return date.toLocaleDateString('id-ID', options).replace(',', ' pukul');
                },

                formatPrice(price) {
                    return new Intl.NumberFormat('id-ID').format(price);
                },

                formatPaymentMethod(method) {
                    const paymentMethods = {
                        'qris': 'QRIS',
                    };
                    return paymentMethods[method] || method || 'QRIS';
                }
            }
        }
    </script>
</body>

</html>