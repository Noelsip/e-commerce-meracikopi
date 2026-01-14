<x-customer.layout title="{{ $menu->name }} - Meracikopi">
    <!-- Main Content -->
    <div style="max-width: 1280px; margin: 0 auto; padding: 40px 16px;">
        <!-- Breadcrumb -->
        <nav style="margin-bottom: 24px;">
            <a href="{{ url('/customer/catalogs') }}" style="color: #CA7842; font-size: 14px; text-decoration: none; display: flex; align-items: center; gap: 4px;">
                ← Kembali ke Menu
            </a>
        </nav>

        <!-- Product Detail Card -->
        <div style="background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <div style="display: flex; flex-wrap: wrap;">
                <!-- Image -->
                <div style="width: 50%; min-width: 300px;">
                    <div style="aspect-ratio: 1; background-color: #F0F2BD;">
                        @if($menu->image_path)
                            <img src="{{ asset($menu->image_path) }}" 
                                 alt="{{ $menu->name }}"
                                 style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 120px;">☕</div>
                        @endif
                    </div>
                </div>
                
                <!-- Details -->
                <div style="width: 50%; min-width: 300px; padding: 48px; box-sizing: border-box;">
                    <!-- Availability Badge -->
                    <div style="margin-bottom: 16px;">
                        @if($menu->is_available)
                            <span style="padding: 8px 16px; background-color: #B2CD9C; color: #4B352A; font-size: 14px; font-weight: 500; border-radius: 20px;">
                                ✓ Tersedia
                            </span>
                        @else
                            <span style="padding: 8px 16px; background-color: #ffebee; color: #c62828; font-size: 14px; font-weight: 500; border-radius: 20px;">
                                ✗ Tidak Tersedia
                            </span>
                        @endif
                    </div>
                    
                    <h1 style="font-size: 32px; font-weight: bold; color: #4B352A; margin-bottom: 16px;">
                        {{ $menu->name }}
                    </h1>
                    
                    <p style="color: #666; font-size: 16px; line-height: 1.6; margin-bottom: 32px;">
                        {{ $menu->description ?? 'Kopi nikmat dari biji pilihan terbaik, disangrai dengan sempurna untuk menghasilkan cita rasa yang kaya dan aroma yang menggoda.' }}
                    </p>
                    
                    <div style="font-size: 36px; font-weight: bold; color: #CA7842; margin-bottom: 32px;">
                        Rp {{ number_format($menu->price, 0, ',', '.') }}
                    </div>
                    
                    <!-- Order Options -->
                    <div>
                        <p style="font-size: 14px; color: #888; margin-bottom: 16px;">Pilih metode pemesanan:</p>
                        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px;">
                            <button style="padding: 12px 16px; background-color: #CA7842; color: white; border: none; border-radius: 20px; cursor: pointer; font-weight: 500;">
                                🍽️ Dine In
                            </button>
                            <button style="padding: 12px 16px; background-color: #CA7842; color: white; border: none; border-radius: 20px; cursor: pointer; font-weight: 500;">
                                🥤 Take Away
                            </button>
                            <button style="padding: 12px 16px; background-color: #CA7842; color: white; border: none; border-radius: 20px; cursor: pointer; font-weight: 500;">
                                🛵 Delivery
                            </button>
                        </div>
                        <p style="font-size: 12px; color: #aaa; margin-top: 12px;">* Login diperlukan untuk melakukan pemesanan</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Menu -->
        @if($relatedMenus->count() > 0)
            <div style="margin-top: 64px;">
                <h2 style="font-size: 24px; font-weight: bold; color: #4B352A; margin-bottom: 24px;">Menu Lainnya</h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 16px;">
                    @foreach($relatedMenus as $related)
                        <a href="{{ url('/customer/catalogs/' . $related->id) }}" 
                           style="background: white; border-radius: 12px; overflow: hidden; text-decoration: none; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                            <div style="aspect-ratio: 1; background-color: #F0F2BD; overflow: hidden;">
                                @if($related->image_path)
                                    <img src="{{ asset($related->image_path) }}" 
                                         alt="{{ $related->name }}"
                                         style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 48px;">☕</div>
                                @endif
                            </div>
                            <div style="padding: 16px;">
                                <h3 style="font-weight: 500; color: #4B352A; font-size: 14px; margin-bottom: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    {{ $related->name }}
                                </h3>
                                <p style="color: #CA7842; font-weight: 600; font-size: 14px;">
                                    Rp {{ number_format($related->price, 0, ',', '.') }}
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-customer.layout>