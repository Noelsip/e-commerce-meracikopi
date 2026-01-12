<x-layout>
    <x-slot name="title">Dashboard</x-slot>

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold mb-6">Welcome to MeraciKopi</h1>

            <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-md p-6">
                <p class="text-gray-600 dark:text-gray-300 mb-4">Selamat datang di dashboard customer!</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                    <a href="/catalogs"
                        class="block p-6 bg-amber-50 dark:bg-amber-900/20 rounded-lg border border-amber-200 dark:border-amber-800 hover:bg-amber-100 dark:hover:bg-amber-900/40 transition">
                        <h3 class="text-lg font-semibold text-amber-800 dark:text-amber-200">📋 Lihat Katalog</h3>
                        <p class="text-amber-600 dark:text-amber-400 text-sm mt-2">Jelajahi menu kopi kami</p>
                    </a>

                    <a href="/orders"
                        class="block p-6 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-800 hover:bg-green-100 dark:hover:bg-green-900/40 transition">
                        <h3 class="text-lg font-semibold text-green-800 dark:text-green-200">📦 Pesanan Saya</h3>
                        <p class="text-green-600 dark:text-green-400 text-sm mt-2">Lihat status pesanan Anda</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layout>