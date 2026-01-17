<x-customer.layout title="Menu - Meracikopi">
    <style>
        /* Mobile Catalog Styles */
        .catalog-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 24px;
        }
        
        .catalog-card {
            background: #2b211e;
            border-radius: 16px;
            overflow: hidden;
            text-decoration: none;
            transition: transform 0.3s, box-shadow 0.3s;
            border: 1px solid #3e302b;
        }
        
        .catalog-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.3);
        }
        
        .catalog-card-image {
            aspect-ratio: 1;
            background-color: #3e302b;
            overflow: hidden;
        }
        
        .catalog-card-content {
            padding: 20px;
        }
        
        .catalog-card-title {
            font-weight: 600;
            color: #f0f2bd;
            font-size: 18px;
            margin-bottom: 4px;
        }
        
        .catalog-card-desc {
            color: #a89890;
            font-size: 14px;
            margin-bottom: 16px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .catalog-card-price {
            font-size: 20px;
            font-weight: bold;
            color: #CA7842;
        }
        
        .catalog-card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .catalog-hero-title {
            font-size: 42px;
        }
        
        .catalog-hero-desc {
            font-size: 16px;
        }
        
        .catalog-filter-pills {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 12px;
        }
        
        .catalog-filter-pill {
            padding: 10px 24px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        /* Catalog Search Input Styling */
        .catalog-search-input:hover {
            background-color: #3a2d26 !important;
            border-color: rgba(240, 242, 189, 0.4) !important;
        }
        
        .catalog-search-input:focus {
            background-color: #3a2d26 !important;
            border-color: #f0f2bd !important;
            transform: scale(1.01);
        }
        
        .catalog-search-wrapper:focus-within .catalog-search-icon {
            color: #f0f2bd !important;
            transform: scale(1.1);
        }
        
        /* iPad/Tablet Responsive (601px - 1024px) */
        @media (min-width: 601px) and (max-width: 1024px) {
            .catalog-filter-pills {
                justify-content: center;
            }
            
            .catalog-hero-title {
                font-size: 32px;
            }
        }
        
        /* Mobile Responsive - 2 Column Grid */
        @media (max-width: 600px) {
            .catalog-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }
            
            .catalog-card {
                border-radius: 12px;
            }
            
            .catalog-card-image {
                aspect-ratio: 1;
                border-radius: 8px;
                margin: 8px;
                background-color: #f5f0eb;
            }
            
            .catalog-card-content {
                padding: 0 12px 12px 12px;
            }
            
            .catalog-card-title {
                font-size: 14px;
                margin-bottom: 2px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
            
            .catalog-card-desc {
                font-size: 12px;
                margin-bottom: 8px;
                -webkit-line-clamp: 1;
                color: #7a7068;
            }
            
            .catalog-card-price {
                font-size: 14px;
            }
            
            .catalog-card-footer {
                flex-direction: column;
                align-items: flex-start;
                gap: 6px;
            }
            
            .catalog-hero-title {
                font-size: 28px;
            }
            
            .catalog-hero-desc {
                font-size: 14px;
                margin-bottom: 24px !important;
            }
            
            .catalog-filter-pills {
                gap: 8px;
                justify-content: flex-start;
                overflow-x: auto;
                flex-wrap: nowrap;
                padding-bottom: 8px;
            }
            
            .catalog-filter-pill {
                padding: 8px 16px;
                font-size: 13px;
                white-space: nowrap;
                flex-shrink: 0;
            }
            
            .catalog-hero-section {
                padding: 40px 16px 24px 16px !important;
            }
            
            .catalog-main-content {
                padding: 20px 12px !important;
            }

            .catalog-rating {
                display: flex;
                align-items: center;
                gap: 4px;
                font-size: 12px;
                color: #a89890;
            }
            
            .catalog-rating svg {
                width: 12px;
                height: 12px;
                color: #fbbf24;
            }

            .catalog-favorite-btn {
                position: absolute;
                top: 12px;
                right: 12px;
                background: rgba(255,255,255,0.9);
                border-radius: 50%;
                padding: 6px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .catalog-card {
                position: relative;
            }
        }
        
        @media (max-width: 480px) {
            .catalog-grid {
                gap: 10px;
            }
            
            .catalog-card-image {
                margin: 6px;
            }
            
            .catalog-card-content {
                padding: 0 10px 10px 10px;
            }
            
            .catalog-card-title {
                font-size: 13px;
            }
            
            .catalog-card-price {
                font-size: 13px;
            }
            
            .catalog-badge-available,
            .catalog-badge-unavailable {
                padding: 3px 8px !important;
                font-size: 10px !important;
            }
        }
    </style>

    <!-- Hero Section -->
    <div class="catalog-hero-section" style="background-color: #1a1410; padding: 60px 20px 40px 20px;">
        <div style="max-width: 1280px; margin: 0 auto; text-align: center;">
            <!-- Title -->
            <h1 class="catalog-hero-title" style="font-weight: 700; color: #f0f2bd; margin-bottom: 16px;">
                Menu & Produk Kami
            </h1>
            <p class="catalog-hero-desc" style="color: #a89890; margin-bottom: 40px;">
                Menu dan biji kopi pilihan dengan cita rasa terbaik
            </p>

            <!-- Search Bar -->
            <form method="GET" action="{{ url('/customer/catalogs') }}" class="catalog-search-wrapper" style="max-width: 600px; margin: 0 auto 30px auto;">
                <div style="position: relative; display: flex; align-items: center;">
                    <svg class="catalog-search-icon" style="position: absolute; left: 20px; width: 20px; height: 20px; color: #888; transition: all 0.3s ease;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Cari Pilihanmu Disini..."
                           class="catalog-search-input"
                           style="width: 100%; padding: 16px 20px 16px 52px; background-color: #2b211e; border: 2px solid #3e302b; border-radius: 50px; color: #f0f2bd; font-size: 15px; outline: none; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);">
                </div>
            </form>

            <!-- Category Filter Pills -->
            <div class="catalog-filter-pills">
                <a href="{{ url('/customer/catalogs') }}" 
                   class="catalog-filter-pill"
                   style="{{ !request('category') ? 'background-color: transparent; border: 1px solid #f0f2bd; color: #f0f2bd;' : 'background-color: #2b211e; border: 1px solid #2b211e; color: #a89890;' }}">
                    Semua Produk
                </a>
                <a href="{{ url('/customer/catalogs?category=food') }}" 
                   class="catalog-filter-pill"
                   style="{{ request('category') == 'food' ? 'background-color: transparent; border: 1px solid #f0f2bd; color: #f0f2bd;' : 'background-color: #2b211e; border: 1px solid #2b211e; color: #a89890;' }}">
                    Food
                </a>
                <a href="{{ url('/customer/catalogs?category=drink') }}" 
                   class="catalog-filter-pill"
                   style="{{ request('category') == 'drink' ? 'background-color: transparent; border: 1px solid #f0f2bd; color: #f0f2bd;' : 'background-color: #2b211e; border: 1px solid #2b211e; color: #a89890;' }}">
                    Drink
                </a>
                <a href="{{ url('/customer/catalogs?category=coffee_beans') }}" 
                   class="catalog-filter-pill"
                   style="{{ request('category') == 'coffee_beans' ? 'background-color: transparent; border: 1px solid #f0f2bd; color: #f0f2bd;' : 'background-color: #2b211e; border: 1px solid #2b211e; color: #a89890;' }}">
                    Coffee Beans
                </a>
                <a href="{{ url('/customer/catalogs?category=kopi_botolan') }}" 
                   class="catalog-filter-pill"
                   style="{{ request('category') == 'kopi_botolan' ? 'background-color: transparent; border: 1px solid #f0f2bd; color: #f0f2bd;' : 'background-color: #2b211e; border: 1px solid #2b211e; color: #a89890;' }}">
                    Kopi Botolan
                </a>
                <a href="{{ url('/customer/catalogs?category=sachet-drip') }}" 
                   class="catalog-filter-pill"
                   style="{{ request('category') == 'sachet-drip' ? 'background-color: transparent; border: 1px solid #f0f2bd; color: #f0f2bd;' : 'background-color: #2b211e; border: 1px solid #2b211e; color: #a89890;' }}">
                    Sachet Drip
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div style="background-color: #1a1410; min-height: 60vh;">
        <div class="catalog-main-content" style="max-width: 1280px; margin: 0 auto; padding: 40px 16px;">
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
                <div class="catalog-grid">
                    @foreach($menus as $menu)
                        <a href="{{ url('/customer/catalogs/' . $menu->id) }}" class="catalog-card">
                            <!-- Favorite Button (Mobile) -->
                            <div class="catalog-favorite-btn" style="display: none;">
                                <svg width="16" height="16" fill="none" stroke="#888" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                            </div>
                            
                            <!-- Image -->
                            <div class="catalog-card-image">
                                @if($menu->image_path)
                                    <img src="{{ asset($menu->image_path) }}" 
                                         alt="{{ $menu->name }}"
                                         style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 64px;">☕</div>
                                @endif
                            </div>
                            
                            <!-- Content -->
                            <div class="catalog-card-content">
                                <h3 class="catalog-card-title">
                                    {{ $menu->name }}
                                </h3>
                                <p class="catalog-card-desc">
                                    {{ $menu->description }}
                                </p>
                                <div class="catalog-card-footer">
                                    <div style="display: flex; align-items: center; justify-content: space-between; width: 100%;">
                                        <span class="catalog-card-price">
                                            Rp {{ number_format($menu->price, 0, ',', '.') }}
                                        </span>
                                        @if($menu->is_available)
                                            <span class="catalog-badge-available" style="padding: 4px 12px; background: linear-gradient(135deg, #CA7842, #8B5E3C); color: #f0f2ae; font-size: 12px; font-weight: 600; border-radius: 20px; box-shadow: 0 2px 8px rgba(202, 120, 66, 0.3);">
                                                Tersedia
                                            </span>
                                        @else
                                            <span class="catalog-badge-unavailable" style="padding: 4px 12px; background-color: #3e302b; color: #a89890; font-size: 12px; font-weight: 500; border-radius: 20px;">
                                                Habis
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-customer.layout>