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
                    <img src="{{ asset('meracik-logo1.png') }}" alt="Meracikopi Logo" class="h-16 w-auto">
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
                    <div class="relative">
                        <input type="password" id="password" name="password" required
                            class="w-full px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500"
                            style="background-color: #5a4032; border: 1px solid #6b4d3a; color: #f0f2bd; padding-right: 48px;"
                            placeholder="••••••••">
                        <button type="button" onclick="togglePassword()" 
                            class="absolute right-0 top-0 h-full px-4 flex items-center justify-center hover:opacity-80"
                            style="color: #8b7355;">
                            <!-- Eye Icon (show password) -->
                            <svg id="eye-open" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <!-- Eye Slash Icon (hide password) -->
                            <svg id="eye-closed" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                </div>

                <script>
                    function togglePassword() {
                        const passwordInput = document.getElementById('password');
                        const eyeOpen = document.getElementById('eye-open');
                        const eyeClosed = document.getElementById('eye-closed');
                        
                        if (passwordInput.type === 'password') {
                            passwordInput.type = 'text';
                            eyeOpen.classList.remove('hidden');
                            eyeClosed.classList.add('hidden');
                        } else {
                            passwordInput.type = 'password';
                            eyeOpen.classList.add('hidden');
                            eyeClosed.classList.remove('hidden');
                        }
                    }
                </script>

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