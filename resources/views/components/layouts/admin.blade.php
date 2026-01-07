<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Admin - Meracikopi' }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased" style="background-color: #3a2a1f;">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-64 flex flex-col" style="background-color: #2b211e; border-right: 1px solid #3e302b;">

            <!-- Logo -->
            <div class="p-6">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                    <x-app-logo-icon class="h-8 w-8" />
                    <span class="text-xl font-bold" style="color: #f0f2bd;">Meracikopi</span>
                </a>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 space-y-1">
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
                <a href="#"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors hover:bg-[#3e302b]/50"
                    style="color: #f0f2bd;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                    Table
                </a>

                <!-- Orders -->
                <a href="#"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors hover:bg-[#3e302b]/50"
                    style="color: #f0f2bd;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    Orders
                </a>

                <!-- Users -->
                <a href="#"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors hover:bg-[#3e302b]/50"
                    style="color: #f0f2bd;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    Users
                </a>

                <!-- Settings -->
                <a href="#"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors hover:bg-[#3e302b]/50"
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
                        class="flex items-center gap-3 px-4 py-3 rounded-lg w-full transition-all duration-200 hover:bg-red-700 hover:shadow-lg group"
                        style="color: #f0f2bd;">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Log Out
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            <header class="px-6 py-3" style="background-color: #2b211e;">
                <div class="flex items-center justify-between">
                    <!-- Title -->
                    <h1 class="text-xl font-semibold" style="color: #f0f2bd;">
                        {{ $title ?? 'Dashboard' }}
                    </h1>

                    <!-- Search Bar -->
                    <div class="flex-1 max-w-md mx-8">
                        <div class="relative flex items-center">
                            <svg class="absolute w-5 h-5" style="left: 16px; color: #f0f2bd;" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input type="text" placeholder="Search something"
                                class="w-full py-2 rounded-full border-none focus:ring-2 focus:ring-amber-500 focus:outline-none"
                                style="background-color: #3e302b; padding-left: 48px; padding-right: 16px; color: #f0f2bd;">
                        </div>
                    </div>

                    <!-- User Info -->
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full" style="background-color: #6b4d3a;"></div>
                        <div class="text-right">
                            <p class="font-medium" style="color: #f0f2bd;">{{ Auth::user()->name ?? 'Admin' }}</p>
                            <p class="text-xs" style="color: #f0f2bd;">{{ Auth::user()->email ?? 'admin@meracikopi.com' }}</p>
                        </div>
                        <form method="POST" action="{{ route('admin.logout') }}" class="ml-2">
                            @csrf
                            <button type="submit" class="text-sm transition-colors hover:opacity-80" style="color: #f0f2bd;">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-6" style="background-color: #1e1715;">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>

</html>