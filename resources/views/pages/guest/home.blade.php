<x-layout title="Home - Meracikopi">
    <div class="min-h-screen flex flex-col items-center justify-center" style="padding-top: 0; margin-top: -60px;">
        <div class="flex justify-center mb-6">
            <img src="{{ asset('meracik-logo1.png') }}" alt="Meracikopi Logo" class="h-20 w-auto">
        </div>
        <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">
            Selamat Datang di Meracikopi
        </h1>
        <p class="text-gray-600 dark:text-gray-400 mb-8">
            Nikmati kopi terbaik untuk Anda
        </p>
        <div class="flex gap-4">
            <a href="{{ route('admin.login') }}"
                class="px-6 py-3 bg-indigo-600 text-white rounded-lg transition duration-300 hover:bg-indigo-700 hover:shadow-lg hover:scale-105"
                style="text-decoration: none;">
                Admin Login
            </a>
        </div>
    </div>
</x-layout>