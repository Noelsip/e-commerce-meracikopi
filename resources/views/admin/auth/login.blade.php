<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login - Meracikopi</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased" style="background-color: #3a2a1f;">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-md">
            <!-- Logo & Title -->
            <div class="text-center mb-8">
                <div class="flex justify-center mb-4">
                    <x-app-logo-icon class="h-12 w-12" />
                </div>
                <h2 class="text-2xl font-bold" style="color: #f0f2bd;">Admin Login</h2>
                <p class="mt-2 text-sm" style="color: #f0f2bd;">Masuk ke Dashboard Admin Meracikopi</p>
            </div>

            @if($errors->any())
                <div class="rounded-lg p-4 mb-6" style="background-color: #4B352A;">
                    <ul class="text-sm list-disc list-inside" style="color: #f0f2bd;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login') }}" class="space-y-5">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium mb-2" style="color: #f0f2bd;">Email</label>
                    <input type="email" id="email" name="email" required value="{{ old('email') }}"
                        class="w-full px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500"
                        style="background-color: #5a4032; border: 1px solid #6b4d3a; color: #f0f2bd;"
                        placeholder="admin@meracikopi.com">
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium mb-2"
                        style="color: #f0f2bd;">Password</label>
                    <input type="password" id="password" name="password" required
                        class="w-full px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500"
                        style="background-color: #5a4032; border: 1px solid #6b4d3a; color: #f0f2bd;"
                        placeholder="••••••••">
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-3 rounded-lg font-semibold transition-colors hover:opacity-90"
                    style="background-color: #6b4d3a; color: #f0f2bd;">
                    Sign in
                </button>
            </form>

            <div class="mt-6 text-center">
                <a href="{{ route('home') }}" class="text-sm hover:underline" style="color: #f0f2bd;">
                    ← Kembali ke Home
                </a>
            </div>
        </div>
    </div>
</body>

</html>