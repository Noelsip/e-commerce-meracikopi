<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Admin - Meracikopi' }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            overflow: hidden;
        }

        /* ========== ADMIN RESPONSIVE STYLES ========== */

        /* Mobile Menu Button */
        .admin-mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            padding: 8px;
            color: #f0f2bd;
        }

        /* Sidebar Overlay */
        .admin-sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            z-index: 40;
        }

        .admin-sidebar-overlay.active {
            display: block;
        }

        /* Responsive Rules */
        @media (max-width: 768px) {

            /* Show mobile menu button */
            .admin-mobile-menu-btn {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            /* Sidebar as drawer */
            .admin-sidebar {
                position: fixed !important;
                left: -280px;
                top: 0;
                bottom: 0;
                z-index: 50;
                transition: left 0.3s ease;
                width: 280px !important;
            }

            .admin-sidebar.active {
                left: 0;
            }

            /* Header adjustments */
            .admin-header-search {
                display: none !important;
            }

            .admin-header-user-details {
                display: none !important;
            }

            .admin-header-logout-desktop {
                display: none !important;
            }

            /* Main content full width */
            .admin-main-wrapper {
                width: 100% !important;
            }

            /* Header title smaller */
            .admin-header-title {
                font-size: 1rem !important;
            }
        }

        @media (max-width: 480px) {
            .admin-content {
                padding: 1rem !important;
            }
        }

        /* Table responsive */
        .admin-table-wrapper {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
    </style>
</head>

<body class="font-sans antialiased h-full" style="background-color: #3a2a1f;">
    <!-- Mobile Sidebar Overlay -->
    <div class="admin-sidebar-overlay" id="adminSidebarOverlay" onclick="toggleAdminSidebar()"></div>

    <div class="h-full flex overflow-hidden">
        <!-- Sidebar -->
        <aside id="adminSidebar" class="admin-sidebar w-64 flex-shrink-0 flex flex-col transition-all duration-300"
            style="background-color: #2b211e; border-right: 1px solid #3e302b;">

            <!-- Mobile Close Button -->
            <button class="admin-mobile-menu-btn" onclick="toggleAdminSidebar()"
                style="position: absolute; top: 16px; right: 16px; z-index: 51;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>

            <!-- Logo -->
            <div class="p-6">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                    <img src="{{ asset('meracik-logo1.png') }}" alt="Meracikopi Logo" class="h-10 w-auto">
                    <span class="text-xl font-bold" style="color: #f0f2bd;">Meracikopi</span>
                </a>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 space-y-1 overflow-y-auto">
                <!-- Home -->
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-[#3e302b]' : 'hover:bg-[#3e302b]/50' }}"
                    style="color: #f0f2bd;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Home
                </a>

                <!-- Catalog -->
                <a href="{{ route('admin.menus.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.menus.*') ? 'bg-[#3e302b]' : 'hover:bg-[#3e302b]/50' }}"
                    style="color: #f0f2bd;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                    Catalog
                </a>

                <!-- Table -->
                <a href="{{ route('admin.tables.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.tables.*') ? 'bg-[#3e302b]' : 'hover:bg-[#3e302b]/50' }}"
                    style="color: #f0f2bd;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                    Table
                </a>

                <!-- Orders -->
                <a href="{{ route('admin.orders.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.orders.*') ? 'bg-[#3e302b]' : 'hover:bg-[#3e302b]/50' }}"
                    style="color: #f0f2bd;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    Orders
                </a>

                <!-- Users -->
                <a href="{{ route('admin.users.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-[#3e302b]' : 'hover:bg-[#3e302b]/50' }}"
                    style="color: #f0f2bd;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    Users
                </a>

                <!-- Settings -->
                <a href="{{ route('admin.settings.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.settings.*') ? 'bg-[#3e302b]' : 'hover:bg-[#3e302b]/50' }}"
                    style="color: #f0f2bd;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Settings
                </a>
            </nav>

            <!-- Log Out -->
            <div class="p-4 mt-auto">
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit"
                        class="logout-btn flex items-center gap-3 px-4 py-3 rounded-lg w-full transition-all duration-200"
                        style="color: #dc2626;">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Log Out
                    </button>
                </form>
            </div>
            <style>
                .logout-btn:hover {
                    background-color: rgba(220, 38, 38, 0.15);
                }

                .header-logout-btn:hover {
                    background-color: rgba(220, 38, 38, 0.2);
                    color: #ef4444 !important;
                }
            </style>
        </aside>

        <!-- Main Content Wrapper -->
        <div class="admin-main-wrapper flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Header -->
            <header class="px-6 py-3 flex-shrink-0 z-10 shadow-sm" style="background-color: #2b211e;">
                <div class="flex items-center justify-between">
                    <!-- Mobile Menu Button -->
                    <button class="admin-mobile-menu-btn" onclick="toggleAdminSidebar()" style="margin-right: 12px;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <line x1="3" y1="12" x2="21" y2="12"></line>
                            <line x1="3" y1="18" x2="21" y2="18"></line>
                        </svg>
                    </button>

                    <!-- Title -->
                    <h1 class="admin-header-title text-xl font-semibold" style="color: #f0f2bd;">
                        {{ $title ?? 'Dashboard' }}
                    </h1>

                    <!-- Search Bar (Removed) -->
                    <div class="flex-1 max-w-md mx-8"></div>

                    <!-- User Info -->
                    <div class="flex items-center gap-3">
                        <!-- Notification Bell -->
                        <a href="{{ route('admin.orders.index') }}" id="adminNotifBell" class="admin-notif-bell" title="Notifikasi pesanan" onclick="resetNotifBadge()">
                            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M12 22c1.1 0 2-.9 2-2h-4a2 2 0 002 2z"/>
                                <path d="M18 16v-5c0-3.07-1.63-5.64-4.5-6.32V4a1.5 1.5 0 00-3 0v.68C7.64 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/>
                            </svg>
                            <span id="bellBadge" class="bell-badge hidden">0</span>
                        </a>
                        <div class="w-10 h-10 rounded-full" style="background-color: #6b4d3a;"></div>
                        <div class="admin-header-user-details text-right">
                            <p class="font-medium" style="color: #f0f2bd;">{{ Auth::user()->name ?? 'Admin' }}</p>
                            <p class="text-xs" style="color: #f0f2bd;">{{ Auth::user()->email ?? 'admin@meracikopi.com'
                                }}</p>
                        </div>
                        <form method="POST" action="{{ route('admin.logout') }}"
                            class="admin-header-logout-desktop ml-2">
                            @csrf
                            <button type="submit"
                                class="header-logout-btn text-sm px-3 py-1 rounded transition-all duration-200"
                                style="color: #f0f2bd;">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="admin-content flex-1 overflow-y-auto p-6 scroll-smooth" style="background-color: #1e1715;">
                {{ $slot }}
            </main>
        </div>
    </div>
    <!-- Order Notification Toast Container -->
    <div id="orderNotifContainer" style="position: fixed; top: 24px; right: 24px; z-index: 9999; display: flex; flex-direction: column; gap: 12px; pointer-events: none;"></div>

    <style>
        /* Notification Toast */
        .order-notif-toast {
            pointer-events: auto;
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 16px 20px;
            background: linear-gradient(135deg, #2b211e 0%, #3e302b 100%);
            border: 1px solid #CA7842;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.4), 0 0 0 1px rgba(202,120,66,0.2);
            min-width: 340px;
            max-width: 420px;
            animation: notifSlideIn 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
            transform-origin: top right;
            position: relative;
            overflow: hidden;
        }

        .order-notif-toast::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #CA7842, #f0f2bd, #CA7842);
            animation: notifShimmer 2s ease-in-out infinite;
        }

        .order-notif-toast.dismissing {
            animation: notifSlideOut 0.4s ease-in forwards;
        }

        @keyframes notifSlideIn {
            0% { opacity: 0; transform: translateX(100px) scale(0.8); }
            100% { opacity: 1; transform: translateX(0) scale(1); }
        }

        @keyframes notifSlideOut {
            0% { opacity: 1; transform: translateX(0) scale(1); }
            100% { opacity: 0; transform: translateX(100px) scale(0.8); }
        }

        @keyframes notifShimmer {
            0%, 100% { opacity: 0.6; }
            50% { opacity: 1; }
        }

        @keyframes notifPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.15); }
        }

        .order-notif-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: linear-gradient(135deg, #CA7842, #8B5E3C);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            animation: notifPulse 1s ease-in-out 2;
        }

        .order-notif-body {
            flex: 1;
            min-width: 0;
        }

        .order-notif-title {
            font-size: 14px;
            font-weight: 700;
            color: #f0f2bd;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .order-notif-title .notif-badge {
            font-size: 10px;
            background: #22c55e;
            color: #fff;
            padding: 1px 6px;
            border-radius: 10px;
            font-weight: 600;
        }

        .order-notif-detail {
            font-size: 13px;
            color: #a89890;
            line-height: 1.4;
        }

        .order-notif-detail strong {
            color: #f0f2bd;
        }

        .order-notif-amount {
            font-size: 15px;
            font-weight: 700;
            color: #22c55e;
            margin-top: 4px;
        }

        .order-notif-close {
            position: absolute;
            top: 10px;
            right: 12px;
            background: none;
            border: none;
            color: #a89890;
            cursor: pointer;
            padding: 4px;
            line-height: 1;
            font-size: 18px;
            transition: color 0.2s;
        }

        .order-notif-close:hover {
            color: #f0f2bd;
        }

        .order-notif-time {
            font-size: 11px;
            color: #6b5d54;
            margin-top: 2px;
        }

        /* Header notification bell */
        .admin-notif-bell {
            position: relative;
            background: none;
            border: none;
            cursor: pointer;
            padding: 6px;
            color: #f0f2bd;
            transition: all 0.2s;
        }

        .admin-notif-bell:hover {
            color: #CA7842;
        }

        .admin-notif-bell .bell-badge {
            position: absolute;
            top: 0;
            right: 0;
            background: #ef4444;
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            min-width: 18px;
            height: 18px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 4px;
            animation: notifPulse 1s ease-in-out infinite;
        }

        .admin-notif-bell .bell-badge.hidden {
            display: none;
        }

        @keyframes bellRing {
            0%, 100% { transform: rotate(0deg); }
            10% { transform: rotate(14deg); }
            20% { transform: rotate(-14deg); }
            30% { transform: rotate(10deg); }
            40% { transform: rotate(-10deg); }
            50% { transform: rotate(6deg); }
            60% { transform: rotate(-6deg); }
            70% { transform: rotate(2deg); }
            80% { transform: rotate(-2deg); }
        }

        .admin-notif-bell.ringing svg {
            animation: bellRing 0.8s ease-in-out;
        }
    </style>

    <script>
        function toggleAdminSidebar() {
            const sidebar = document.getElementById('adminSidebar');
            const overlay = document.getElementById('adminSidebarOverlay');
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
            document.body.style.overflow = sidebar.classList.contains('active') ? 'hidden' : '';
        }

        // ========== ORDER NOTIFICATION SYSTEM ==========
        (function() {
            const POLL_INTERVAL = 10000; // 10 seconds
            const TOAST_DURATION = 8000; // 8 seconds
            let lastChecked = null;
            let audioCtx = null;
            let bellBadgeCount = 0;

            // Initialize AudioContext on first user interaction (required by browsers)
            function getAudioContext() {
                if (!audioCtx) {
                    audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                }
                return audioCtx;
            }

            // Generate "dring" bell notification sound using Web Audio API
            function playNotificationSound() {
                try {
                    const ctx = getAudioContext();
                    if (ctx.state === 'suspended') {
                        ctx.resume();
                    }

                    const now = ctx.currentTime;

                    // === First bell tone (higher pitch) ===
                    const osc1 = ctx.createOscillator();
                    const gain1 = ctx.createGain();
                    osc1.type = 'sine';
                    osc1.frequency.setValueAtTime(1200, now);
                    osc1.frequency.exponentialRampToValueAtTime(800, now + 0.15);
                    gain1.gain.setValueAtTime(0.35, now);
                    gain1.gain.exponentialRampToValueAtTime(0.01, now + 0.4);
                    osc1.connect(gain1);
                    gain1.connect(ctx.destination);
                    osc1.start(now);
                    osc1.stop(now + 0.4);

                    // Harmonic overtone for richness
                    const osc1h = ctx.createOscillator();
                    const gain1h = ctx.createGain();
                    osc1h.type = 'sine';
                    osc1h.frequency.setValueAtTime(2400, now);
                    osc1h.frequency.exponentialRampToValueAtTime(1600, now + 0.12);
                    gain1h.gain.setValueAtTime(0.12, now);
                    gain1h.gain.exponentialRampToValueAtTime(0.01, now + 0.25);
                    osc1h.connect(gain1h);
                    gain1h.connect(ctx.destination);
                    osc1h.start(now);
                    osc1h.stop(now + 0.25);

                    // === Second bell tone (lower pitch, delayed) ===
                    const osc2 = ctx.createOscillator();
                    const gain2 = ctx.createGain();
                    osc2.type = 'sine';
                    osc2.frequency.setValueAtTime(900, now + 0.2);
                    osc2.frequency.exponentialRampToValueAtTime(600, now + 0.45);
                    gain2.gain.setValueAtTime(0, now);
                    gain2.gain.setValueAtTime(0.35, now + 0.2);
                    gain2.gain.exponentialRampToValueAtTime(0.01, now + 0.7);
                    osc2.connect(gain2);
                    gain2.connect(ctx.destination);
                    osc2.start(now + 0.2);
                    osc2.stop(now + 0.7);

                    // Harmonic for second tone
                    const osc2h = ctx.createOscillator();
                    const gain2h = ctx.createGain();
                    osc2h.type = 'sine';
                    osc2h.frequency.setValueAtTime(1800, now + 0.2);
                    osc2h.frequency.exponentialRampToValueAtTime(1200, now + 0.4);
                    gain2h.gain.setValueAtTime(0, now);
                    gain2h.gain.setValueAtTime(0.1, now + 0.2);
                    gain2h.gain.exponentialRampToValueAtTime(0.01, now + 0.45);
                    osc2h.connect(gain2h);
                    gain2h.connect(ctx.destination);
                    osc2h.start(now + 0.2);
                    osc2h.stop(now + 0.45);

                    // === Third bell "ding" (final, slightly higher) ===
                    const osc3 = ctx.createOscillator();
                    const gain3 = ctx.createGain();
                    osc3.type = 'sine';
                    osc3.frequency.setValueAtTime(1100, now + 0.5);
                    osc3.frequency.exponentialRampToValueAtTime(700, now + 0.8);
                    gain3.gain.setValueAtTime(0, now);
                    gain3.gain.setValueAtTime(0.25, now + 0.5);
                    gain3.gain.exponentialRampToValueAtTime(0.01, now + 1.0);
                    osc3.connect(gain3);
                    gain3.connect(ctx.destination);
                    osc3.start(now + 0.5);
                    osc3.stop(now + 1.0);

                    console.log('🔔 Notification sound played');
                } catch (e) {
                    console.warn('Could not play notification sound:', e);
                }
            }

            // Show toast notification
            function showOrderNotification(order) {
                const container = document.getElementById('orderNotifContainer');
                if (!container) return;

                const toast = document.createElement('div');
                toast.className = 'order-notif-toast';

                const tableInfo = order.table_number
                    ? `Meja <strong>${order.table_number}</strong> · `
                    : '';

                toast.innerHTML = `
                    <div class="order-notif-icon">
                        <svg width="22" height="22" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M12 22c1.1 0 2-.9 2-2h-4a2 2 0 002 2z"/>
                            <path d="M18 16v-5c0-3.07-1.63-5.64-4.5-6.32V4a1.5 1.5 0 00-3 0v.68C7.64 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/>
                        </svg>
                    </div>
                    <div class="order-notif-body">
                        <div class="order-notif-title">
                            Pesanan Baru Dibayar! <span class="notif-badge">PAID</span>
                        </div>
                        <div class="order-notif-detail">
                            ${tableInfo}<strong>${order.customer_name}</strong> · ${order.order_type}
                        </div>
                        <div class="order-notif-amount">${order.total}</div>
                        <div class="order-notif-time">Dibayar pukul ${order.paid_at}</div>
                    </div>
                    <button class="order-notif-close" onclick="dismissNotif(this)" title="Tutup">×</button>
                `;

                container.appendChild(toast);

                // Auto dismiss after TOAST_DURATION
                setTimeout(() => {
                    dismissNotif(toast.querySelector('.order-notif-close'));
                }, TOAST_DURATION);
            }

            // Make dismissNotif global
            window.dismissNotif = function(btn) {
                const toast = btn.closest('.order-notif-toast');
                if (!toast || toast.classList.contains('dismissing')) return;
                toast.classList.add('dismissing');
                setTimeout(() => toast.remove(), 400);
            };

            // Animate the bell icon
            function ringBell() {
                const bell = document.getElementById('adminNotifBell');
                if (bell) {
                    bell.classList.add('ringing');
                    setTimeout(() => bell.classList.remove('ringing'), 1000);
                }
            }

            // Update bell badge count
            function updateBellBadge(count) {
                bellBadgeCount += count;
                const badge = document.getElementById('bellBadge');
                if (badge) {
                    badge.textContent = bellBadgeCount;
                    badge.classList.toggle('hidden', bellBadgeCount <= 0);
                }
            }

            // Reset badge on bell button click
            window.resetNotifBadge = function() {
                bellBadgeCount = 0;
                const badge = document.getElementById('bellBadge');
                if (badge) {
                    badge.classList.add('hidden');
                }
            };

            // Poll for new paid orders
            async function pollNewOrders() {
                try {
                    const url = new URL('{{ route("admin.api.newPaidOrders") }}', window.location.origin);
                    if (lastChecked) {
                        url.searchParams.set('last_checked', lastChecked);
                    }

                    const response = await fetch(url.toString(), {
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        },
                        credentials: 'same-origin',
                    });

                    if (!response.ok) {
                        console.warn('Notification poll failed:', response.status);
                        return;
                    }

                    const data = await response.json();

                    // Update server time for next poll
                    if (data.server_time) {
                        lastChecked = data.server_time;
                    }

                    // Show notifications for new orders
                    if (data.count > 0) {
                        console.log(`🔔 ${data.count} new paid order(s) detected!`);

                        // Play sound
                        playNotificationSound();

                        // Ring bell icon
                        ringBell();

                        // Update badge
                        updateBellBadge(data.count);

                        // Show toast for each new order (max 3 to avoid overflow)
                        const ordersToShow = data.new_orders.slice(0, 3);
                        ordersToShow.forEach((order, i) => {
                            setTimeout(() => showOrderNotification(order), i * 300);
                        });

                        // If more than 3, show summary
                        if (data.count > 3) {
                            setTimeout(() => {
                                showOrderNotification({
                                    customer_name: `+${data.count - 3} pesanan lainnya`,
                                    order_type: 'Lihat di halaman Orders',
                                    table_number: null,
                                    total: '',
                                    paid_at: new Date().toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'}),
                                });
                            }, 3 * 300 + 200);
                        }
                    }
                } catch (e) {
                    console.warn('Notification poll error:', e);
                }
            }

            // Initialize Audio Context on first user interaction
            document.addEventListener('click', function initAudio() {
                getAudioContext();
                document.removeEventListener('click', initAudio);
            }, { once: true });

            // Start polling when page loads
            document.addEventListener('DOMContentLoaded', function() {
                // Initial poll (sets lastChecked baseline)
                pollNewOrders();

                // Start interval polling
                setInterval(pollNewOrders, POLL_INTERVAL);

                console.log('🔔 Order notification system active (polling every ' + (POLL_INTERVAL/1000) + 's)');
            });
        })();
    </script>
</body>

</html>