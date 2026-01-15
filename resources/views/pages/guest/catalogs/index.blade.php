<x-customer.layout title="Menu - Meracikopi">
    <!-- Hero Section -->
    <div style="background-color: #1a1410; padding: 60px 20px 40px 20px;">
        <div style="max-width: 1280px; margin: 0 auto; text-align: center;">
            <!-- Title -->
            <h1 style="font-size: 42px; font-weight: 700; color: #f0f2bd; margin-bottom: 16px;">
                Menu & Produk Kami
            </h1>
            <p style="color: #a89890; font-size: 16px; margin-bottom: 40px;">
                Menu dan biji kopi pilihan dengan cita rasa terbaik
            </p>

            <!-- Search Bar -->
            <form method="GET" action="{{ url('/customer/catalogs') }}" style="max-width: 600px; margin: 0 auto 30px auto;">
                <div style="position: relative; display: flex; align-items: center;">
                    <svg style="position: absolute; left: 20px; width: 20px; height: 20px; color: #888;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Cari Pilihanmu Disini..."
                           style="width: 100%; padding: 16px 20px 16px 52px; background-color: #2b211e; border: 1px solid #3e302b; border-radius: 50px; color: #f0f2bd; font-size: 15px; outline: none;">
                </div>
            </form>

            <!-- Category Filter Pills -->
            <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 12px;">
                <a href="{{ url('/customer/catalogs') }}" 
                   style="padding: 10px 24px; border-radius: 50px; font-size: 14px; font-weight: 500; text-decoration: none; transition: all 0.3s;
                          {{ !request('category') ? 'background-color: transparent; border: 1px solid #f0f2bd; color: #f0f2bd;' : 'background-color: #2b211e; border: 1px solid #2b211e; color: #a89890;' }}">
                    Semua Produk
                </a>
                <a href="{{ url('/customer/catalogs?category=food') }}" 
                   style="padding: 10px 24px; border-radius: 50px; font-size: 14px; font-weight: 500; text-decoration: none; transition: all 0.3s;
                          {{ request('category') == 'food' ? 'background-color: transparent; border: 1px solid #f0f2bd; color: #f0f2bd;' : 'background-color: #2b211e; border: 1px solid #2b211e; color: #a89890;' }}">
                    Food
                </a>
                <a href="{{ url('/customer/catalogs?category=drink') }}" 
                   style="padding: 10px 24px; border-radius: 50px; font-size: 14px; font-weight: 500; text-decoration: none; transition: all 0.3s;
                          {{ request('category') == 'drink' ? 'background-color: transparent; border: 1px solid #f0f2bd; color: #f0f2bd;' : 'background-color: #2b211e; border: 1px solid #2b211e; color: #a89890;' }}">
                    Drink
                </a>
                <a href="{{ url('/customer/catalogs?category=coffee_beans') }}" 
                   style="padding: 10px 24px; border-radius: 50px; font-size: 14px; font-weight: 500; text-decoration: none; transition: all 0.3s;
                          {{ request('category') == 'coffee_beans' ? 'background-color: transparent; border: 1px solid #f0f2bd; color: #f0f2bd;' : 'background-color: #2b211e; border: 1px solid #2b211e; color: #a89890;' }}">
                    Coffee Beans
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div style="background-color: #1a1410; min-height: 60vh;">
        <div style="max-width: 1280px; margin: 0 auto; padding: 40px 16px;">
            @if(request('search'))
                <div style="margin-bottom: 24px; display: flex; align-items: center; gap: 8px;">
                    <span style="padding: 6px 16px; background-color: #2b211e; color: #f0f2bd; border-radius: 20px; font-size: 14px; font-weight: 500;">
                        Hasil: "{{ request('search') }}"
                    </span>
                    <a href="{{ url('/customer/catalogs') }}" style="color: #CA7842; font-size: 14px; text-decoration: none;">
                        × Reset
                    </a>
                </div>
            @endif

            @if($menus->isEmpty())
                <!-- Empty State -->
                <div style="text-align: center; padding: 64px 20px; background: #2b211e; border-radius: 16px;">
                    <div style="font-size: 64px; margin-bottom: 16px;">☕</div>
                    <h3 style="font-size: 20px; font-weight: 600; color: #f0f2bd; margin-bottom: 8px;">Menu tidak ditemukan</h3>
                    <p style="color: #a89890; margin-bottom: 24px;">Coba kata kunci lain atau lihat semua menu</p>
                    <a href="{{ url('/customer/catalogs') }}" 
                       style="display: inline-block; padding: 12px 24px; background-color: #CA7842; color: white; border-radius: 50px; text-decoration: none; font-weight: 500;">
                        Lihat Semua Menu
                    </a>
                </div>
            @else
                <!-- Menu Grid -->
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 24px;">
                    @foreach($menus as $menu)
                        <a href="{{ url('/customer/catalogs/' . $menu->id) }}" 
                           style="background: #2b211e; border-radius: 16px; overflow: hidden; text-decoration: none; transition: transform 0.3s, box-shadow 0.3s; border: 1px solid #3e302b;"
                           onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 24px rgba(0,0,0,0.3)';"
                           onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                            <!-- Image -->
                            <div style="aspect-ratio: 1; background-color: #3e302b; overflow: hidden;">
                                @if($menu->image_path)
                                    <img src="{{ asset($menu->image_path) }}" 
                                         alt="{{ $menu->name }}"
                                         style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 64px;">☕</div>
                                @endif
                            </div>
                            
                            <!-- Content -->
                            <div style="padding: 20px;">
                                <h3 style="font-weight: 600; color: #f0f2bd; font-size: 18px; margin-bottom: 4px;">
                                    {{ $menu->name }}
                                </h3>
                                <p style="color: #a89890; font-size: 14px; margin-bottom: 16px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    {{ $menu->description }}
                                </p>
                                <div style="display: flex; align-items: center; justify-content: space-between;">
                                    <span style="font-size: 20px; font-weight: bold; color: #CA7842;">
                                        Rp {{ number_format($menu->price, 0, ',', '.') }}
                                    </span>
                                    @if($menu->is_available)
                                        <span style="padding: 4px 12px; background-color: rgba(34, 197, 94, 0.2); color: #22c55e; font-size: 12px; font-weight: 500; border-radius: 20px;">
                                            Tersedia
                                        </span>
                                    @else
                                        <span style="padding: 4px 12px; background-color: rgba(239, 68, 68, 0.2); color: #ef4444; font-size: 12px; font-weight: 500; border-radius: 20px;">
                                            Habis
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-customer.layout>