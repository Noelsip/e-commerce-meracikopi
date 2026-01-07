<x-layout title="Welcome - Meracikopi">
    <div class="min-h-screen flex flex-col items-center justify-center">
        <div class="flex justify-center mb-6">
            <x-app-logo-icon class="h-16 w-16" />
        </div>
        <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">
            E-Commerce Meracikopi
        </h1>
        <p class="text-gray-600 dark:text-gray-400 mb-8 text-center max-w-md">
            Sistem pemesanan kopi terbaik untuk dine in, take away, dan delivery
        </p>
        <div class="flex gap-4">
            <a href="{{ route('admin.login') }}" 
               class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                Admin Login
            </a>
            <a href="{{ route('login') }}" 
               class="px-6 py-3 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white border border-gray-300 dark:border-zinc-600 rounded-lg hover:bg-gray-50 dark:hover:bg-zinc-700 transition">
                Customer Login
            </a>
        </div>
    </div>
</x-layout>