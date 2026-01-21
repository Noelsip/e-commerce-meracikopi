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

        /* Order History Page Styles */
        .order-history-container {
            max-width: 1360px;
            margin: 0 auto;
            padding: 40px 20px;
            padding-bottom: 80px;
        }

        .order-history-title {
            font-size: 28px;
            font-weight: 600;
            color: #FFF4D6;
            margin-bottom: 32px;
            font-style: italic;
        }

        /* Order Card */
        .order-card {
            width: 100%;
            max-width: 1239px;
            min-height: 230px;
            border: 1px solid #D9D9D9;
            border-radius: 30px;
            background: transparent;
            margin-bottom: 24px;
            padding: 28px 40px;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .order-card-content {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
            flex: 1;
        }

        /* Left Section */
        .order-left-section {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .order-id-row {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .order-id {
            font-size: 16px;
            font-weight: 600;
            color: white;
        }

        /* Status Badges */
        .status-badge {
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 11px;
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
            gap: 8px;
            color: rgba(255, 255, 255, 0.7);
            font-size: 13px;
        }

        .order-type-row svg {
            width: 16px;
            height: 16px;
        }

        .order-date {
            color: rgba(255, 255, 255, 0.6);
            font-size: 12px;
        }

        /* Center Section - Items */
        .order-center-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding-top: 4px;
        }

        .order-items-count {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 8px;
        }

        .order-items-list {
            display: flex;
            flex-direction: column;
            gap: 4px;
            text-align: center;
        }

        .order-item {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.8);
        }

        /* Right Section - Total */
        .order-right-section {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            justify-content: flex-start;
            padding-top: 4px;
        }

        .order-total-label {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: 4px;
        }

        .order-total-amount {
            font-size: 22px;
            font-weight: 700;
            color: #CA7842;
        }

        /* Divider */
        .order-divider {
            width: 100%;
            height: 1px;
            background-color: rgba(217, 217, 217, 0.3);
            margin: 16px 0;
        }

        /* Table Number */
        .order-table-number {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.6);
        }

        /* Order Again Button */
        .order-again-wrapper {
            display: flex;
            justify-content: center;
            margin-top: 32px;
        }

        .order-again-btn {
            background-color: var(--secondary);
            color: white;
            border: none;
            border-radius: 24px;
            padding: 14px 40px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(202, 120, 66, 0.3);
            text-decoration: none;
            display: inline-block;
        }

        .order-again-btn:hover {
            background-color: #d4864c;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(202, 120, 66, 0.4);
        }

        /* Empty State */
        .empty-orders {
            text-align: center;
            padding: 80px 20px;
            color: rgba(255, 255, 255, 0.6);
        }

        .empty-orders svg {
            width: 80px;
            height: 80px;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .empty-orders h3 {
            font-size: 18px;
            margin-bottom: 8px;
            color: rgba(255, 255, 255, 0.8);
        }

        .empty-orders p {
            font-size: 14px;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .order-history-container {
                padding: 20px 12px;
            }

            .order-history-title {
                font-size: 22px;
                margin-bottom: 20px;
            }

            .order-card {
                padding: 20px 16px;
                border-radius: 20px;
                min-height: auto;
            }

            .order-card-content {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .order-center-section {
                align-items: flex-start;
                padding-top: 0;
            }

            .order-items-list {
                text-align: left;
            }

            .order-right-section {
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
                padding-top: 0;
            }

            .order-total-label {
                margin-bottom: 0;
            }

            .order-total-amount {
                font-size: 18px;
            }

            .order-id {
                font-size: 14px;
            }

            .status-badge {
                font-size: 10px;
                padding: 3px 10px;
            }
        }

        @media (max-width: 480px) {
            .order-card {
                padding: 16px 12px;
                border-radius: 16px;
            }

            .order-id-row {
                flex-wrap: wrap;
                gap: 8px;
            }

            .order-total-amount {
                font-size: 16px;
            }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    @include('components.customer.navbar')

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

            <!-- Order Cards -->
            <template x-for="order in orders" :key="order.id">
                <div class="order-card">
                    <div class="order-card-content">
                        <!-- Left Section -->
                        <div class="order-left-section">
                            <div class="order-id-row">
                                <span class="order-id" x-text="order.order_code"></span>
                                <span class="status-badge" 
                                    :class="'status-' + order.status"
                                    x-text="getStatusLabel(order.status)"></span>
                            </div>
                            <div class="order-type-row">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <span x-text="getOrderTypeLabel(order.order_type) + ' | ' + (order.payment_method || 'Credit Card')"></span>
                            </div>
                            <div class="order-date" x-text="formatDate(order.created_at)"></div>
                        </div>

                        <!-- Center Section -->
                        <div class="order-center-section">
                            <div class="order-items-count" x-text="order.items.length + ' items'"></div>
                            <div class="order-items-list">
                                <template x-for="item in order.items.slice(0, 3)" :key="item.id">
                                    <div class="order-item" x-text="item.menu_name + ' x ' + item.quantity"></div>
                                </template>
                                <div class="order-item" x-show="order.items.length > 3" 
                                    x-text="'+ ' + (order.items.length - 3) + ' lainnya'"></div>
                            </div>
                        </div>

                        <!-- Right Section -->
                        <div class="order-right-section">
                            <div class="order-total-label">Total</div>
                            <div class="order-total-amount" x-text="'RP ' + formatPrice(order.final_price || order.total_price)"></div>
                        </div>
                    </div>

                    <div class="order-divider"></div>

                    <div class="order-table-number" x-show="order.table_number">
                        <span x-text="'Meja Nomor: ' + order.table_number"></span>
                    </div>
                </div>
            </template>

            <!-- Order Again Button -->
            <div class="order-again-wrapper" x-show="!loading && orders.length > 0">
                <a href="{{ route('catalogs.index') }}" class="order-again-btn">Order Again</a>
            </div>
        </div>
    </main>

    <!-- Footer -->
    @include('components.customer.footer')

    <script>
        function orderHistory() {
            return {
                orders: [],
                loading: true,

                loadOrders() {
                    // Get guest token
                    const guestToken = localStorage.getItem('guest_token');
                    
                    if (!guestToken) {
                        this.loading = false;
                        return;
                    }

                    fetch('/api/customer/orders', {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-Guest-Token': guestToken
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.data) {
                            this.orders = data.data.map(order => ({
                                ...order,
                                order_code: this.generateOrderCode(order),
                                items: order.order_items || [],
                                table_number: order.tables?.table_number || null,
                                payment_method: order.payments?.[0]?.payment_method || 'Credit Card'
                            }));
                        }
                        this.loading = false;
                    })
                    .catch(error => {
                        console.error('Error loading orders:', error);
                        this.loading = false;
                    });
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

                getOrderTypeLabel(type) {
                    const labels = {
                        'dine_in': 'Dine In',
                        'delivery': 'Delivery',
                        'takeaway': 'Takeaway'
                    };
                    return labels[type] || type;
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
                }
            }
        }
    </script>
</body>

</html>
