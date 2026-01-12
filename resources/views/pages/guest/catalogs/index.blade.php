<x-customer.layout title="Menu - Meracikopi">
    <!-- Hero Section -->
    <div style="background-color: #4B352A; padding: 40px 0;">
        <div style="max-width: 1280px; margin: 0 auto; padding: 0 16px; text-align: center;">
            <h1 style="font-size: 32px; font-weight: bold; color: white; margin-bottom: 12px;">Menu Kami</h1>
            <p style="color: #ccc;">Temukan kopi favoritmu hari ini</p>
        </div>
    </div>

    <!-- Main Content -->
    <div style="max-width: 1280px; margin: 0 auto; padding: 40px 16px;">
        @if(request('search'))
            <div style="margin-bottom: 24px; display: flex; align-items: center; gap: 8px;">
                <span style="padding: 6px 12px; background-color: #B2CD9C; color: #4B352A; border-radius: 20px; font-size: 14px; font-weight: 500;">
                    Hasil: "{{ request('search') }}"
                </span>
                <a href="{{ url('/customer/catalogs') }}" style="color: #CA7842; font-size: 14px;">
                    × Reset
                </a>
            </div>
        @endif

        @if($menus->isEmpty())
            <!-- Empty State -->
            <div style="text-align: center; padding: 64px 0; background: white; border-radius: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <div style="font-size: 64px; margin-bottom: 16px;">☕</div>
                <h3 style="font-size: 20px; font-weight: 600; color: #4B352A; margin-bottom: 8px;">Menu tidak ditemukan</h3>
                <p style="color: #666; margin-bottom: 24px;">Coba kata kunci lain atau lihat semua menu</p>
                <a href="{{ url('/customer/catalogs') }}" 
                   style="display: inline-block; padding: 12px 24px; background-color: #CA7842; color: white; border-radius: 20px; text-decoration: none; font-weight: 500;">
                    Lihat Semua Menu
                </a>
            </div>
        @else
            <!-- Menu Grid -->
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 24px;">
                @foreach($menus as $menu)
                    <a href="{{ url('/customer/catalogs/' . $menu->id) }}" 
                       style="background: white; border-radius: 16px; overflow: hidden; text-decoration: none; box-shadow: 0 2px 8px rgba(0,0,0,0.1); transition: transform 0.3s, box-shadow 0.3s;"
                       onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 24px rgba(0,0,0,0.15)';"
                       onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.1)';">
                        <!-- Image -->
                        <div style="aspect-ratio: 1; background-color: #F0F2BD; overflow: hidden;">
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
                            <h3 style="font-weight: 600; color: #4B352A; font-size: 18px; margin-bottom: 4px;">
                                {{ $menu->name }}
                            </h3>
                            <p style="color: #666; font-size: 14px; margin-bottom: 16px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                {{ $menu->description ?? 'Kopi nikmat dari biji pilihan' }}
                            </p>
                            <div style="display: flex; align-items: center; justify-content: space-between;">
                                <span style="font-size: 20px; font-weight: bold; color: #CA7842;">
                                    Rp {{ number_format($menu->price, 0, ',', '.') }}
                                </span>
                                <span style="padding: 4px 12px; background-color: #B2CD9C; color: #4B352A; font-size: 12px; font-weight: 500; border-radius: 20px;">
                                    Tersedia
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</x-customer.layout>