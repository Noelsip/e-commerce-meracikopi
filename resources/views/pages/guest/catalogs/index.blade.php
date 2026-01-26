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
                padding: 20px 12px 60px 12px !important;
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

        /* Mobile Bottom Sheet Styles */
        .product-bottom-sheet-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            z-index: 9998;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .product-bottom-sheet-overlay.active {
            display: block;
            opacity: 1;
        }

        .product-bottom-sheet {
            display: none;
            position: fixed;
            left: 0;
            right: 0;
            bottom: 0;
            background: #fff;
            border-radius: 24px 24px 0 0;
            z-index: 9999;
            transform: translateY(100%);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            max-height: 85vh;
            overflow: hidden;
        }

        .product-bottom-sheet.active {
            display: block;
            transform: translateY(0);
        }

        .bottom-sheet-handle {
            display: flex;
            justify-content: center;
            padding: 12px 0 8px 0;
        }

        .bottom-sheet-handle-bar {
            width: 40px;
            height: 4px;
            background: #ddd;
            border-radius: 2px;
        }

        .bottom-sheet-content {
            padding: 0 20px 24px 20px;
            overflow-y: auto;
            max-height: calc(85vh - 80px);
        }

        .bottom-sheet-image {
            width: 100%;
            aspect-ratio: 4/3;
            border-radius: 16px;
            overflow: hidden;
            background: #f5f5f5;
            margin-bottom: 16px;
        }

        .bottom-sheet-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .bottom-sheet-title {
            font-size: 20px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 6px;
        }

        .bottom-sheet-desc {
            font-size: 14px;
            color: #666;
            margin-bottom: 12px;
            line-height: 1.5;
        }

        .bottom-sheet-price {
            font-size: 18px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 16px;
        }

        .bottom-sheet-note-wrapper {
            margin-bottom: 16px;
        }

        .bottom-sheet-note-label {
            font-size: 14px;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            display: block;
        }

        .bottom-sheet-note-input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            resize: none;
            outline: none;
            font-family: inherit;
            transition: border-color 0.2s ease;
        }

        .bottom-sheet-note-input:focus {
            border-color: #CA7842;
        }

        .bottom-sheet-note-input::placeholder {
            color: #999;
        }

        .bottom-sheet-quantity-wrapper {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .bottom-sheet-quantity-label {
            font-size: 14px;
            font-weight: 600;
            color: #333;
        }

        .bottom-sheet-quantity-controls {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .bottom-sheet-qty-btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border: 1px solid #ddd;
            background: #fff;
            font-size: 20px;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            color: #333;
        }

        .bottom-sheet-qty-btn:hover:not(:disabled) {
            background: #f5f5f5;
            border-color: #CA7842;
            color: #CA7842;
        }

        .bottom-sheet-qty-btn:disabled {
            opacity: 0.4;
            cursor: not-allowed;
        }

        .bottom-sheet-qty-value {
            font-size: 18px;
            font-weight: 600;
            min-width: 30px;
            text-align: center;
            color: #1a1a1a;
        }

        .bottom-sheet-buttons {
            display: flex;
            gap: 12px;
        }

        .bottom-sheet-detail-btn {
            flex: 1;
            padding: 14px;
            background: #fff;
            color: #CA7842;
            border: 2px solid #CA7842;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            text-align: center;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .bottom-sheet-detail-btn:hover {
            background: #FFF8F0;
        }

        .bottom-sheet-add-btn {
            flex: 2;
            padding: 14px;
            background: #CA7842;
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s ease;
        }

        .bottom-sheet-add-btn:hover {
            background: #b56a38;
        }

        .bottom-sheet-add-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        /* Hide bottom sheet on desktop */
        @media (min-width: 601px) {
            .product-bottom-sheet,
            .product-bottom-sheet-overlay {
                display: none !important;
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
                    Kopi Botol
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
    <div style="background-color: #1a1410; padding-bottom: 40px;">
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
                        <a href="{{ url('/customer/catalogs/' . $menu->id) }}" 
                           class="catalog-card"
                           data-menu-id="{{ $menu->id }}"
                           data-menu-name="{{ $menu->name }}"
                           data-menu-desc="{{ $menu->description }}"
                           data-menu-price="{{ number_format($menu->price, 0, ',', '.') }}"
                           data-menu-image="{{ $menu->image_path ? asset($menu->image_path) : '' }}"
                           data-menu-available="{{ $menu->is_available ? '1' : '0' }}"
                           onclick="handleCardClick(event, this)">
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

    <!-- Mobile Bottom Sheet -->
    <div class="product-bottom-sheet-overlay" id="bottomSheetOverlay" onclick="closeBottomSheet()"></div>
    <div class="product-bottom-sheet" id="productBottomSheet">
        <div class="bottom-sheet-handle" onclick="closeBottomSheet()">
            <div class="bottom-sheet-handle-bar"></div>
        </div>
        <div class="bottom-sheet-content">
            <div class="bottom-sheet-image" id="bottomSheetImage">
                <img src="" alt="Product" id="bottomSheetImgTag">
            </div>
            <h2 class="bottom-sheet-title" id="bottomSheetTitle"></h2>
            <p class="bottom-sheet-desc" id="bottomSheetDesc"></p>
            <p class="bottom-sheet-price" id="bottomSheetPrice"></p>

            <!-- Notes/Catatan -->
            <div class="bottom-sheet-note-wrapper">
                <label class="bottom-sheet-note-label">Catatan</label>
                <textarea 
                    class="bottom-sheet-note-input" 
                    id="bottomSheetNote"
                    rows="2"
                    placeholder="Contoh: less sugar, extra shot, tanpa es, dll"></textarea>
            </div>

            <!-- Quantity Control -->
            <div class="bottom-sheet-quantity-wrapper">
                <span class="bottom-sheet-quantity-label">Jumlah</span>
                <div class="bottom-sheet-quantity-controls">
                    <button type="button" class="bottom-sheet-qty-btn" id="bottomSheetQtyMinus" onclick="updateQuantity(-1)">−</button>
                    <span class="bottom-sheet-qty-value" id="bottomSheetQtyValue">1</span>
                    <button type="button" class="bottom-sheet-qty-btn" id="bottomSheetQtyPlus" onclick="updateQuantity(1)">+</button>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="bottom-sheet-buttons">
                <a href="#" id="bottomSheetLink" class="bottom-sheet-detail-btn">
                    Lihat Detail
                </a>
                <button type="button" class="bottom-sheet-add-btn" id="bottomSheetAddBtn" onclick="addToCart()">
                    Tambah ke Keranjang
                </button>
            </div>
        </div>
    </div>

    <input type="hidden" id="currentMenuId" value="">
    <input type="hidden" id="currentMenuPrice" value="">

    <script>
        let currentQuantity = 1;

        // Reset body overflow on page load to fix scroll issues
        document.addEventListener('DOMContentLoaded', function() {
            document.body.style.overflow = '';
            document.documentElement.style.overflow = '';
        });

        function isMobile() {
            return window.innerWidth <= 600;
        }

        function handleCardClick(event, card) {
            if (isMobile()) {
                event.preventDefault();
                openBottomSheet(card);
            }
            // On desktop, let the default link behavior happen
        }

        function openBottomSheet(card) {
            const overlay = document.getElementById('bottomSheetOverlay');
            const sheet = document.getElementById('productBottomSheet');
            const imgTag = document.getElementById('bottomSheetImgTag');
            const imageContainer = document.getElementById('bottomSheetImage');
            const title = document.getElementById('bottomSheetTitle');
            const desc = document.getElementById('bottomSheetDesc');
            const price = document.getElementById('bottomSheetPrice');
            const link = document.getElementById('bottomSheetLink');
            const addBtn = document.getElementById('bottomSheetAddBtn');

            // Reset values
            currentQuantity = 1;
            document.getElementById('bottomSheetQtyValue').textContent = '1';
            document.getElementById('bottomSheetNote').value = '';
            document.getElementById('bottomSheetQtyMinus').disabled = true;

            // Set data
            const menuId = card.dataset.menuId;
            const menuName = card.dataset.menuName;
            const menuDesc = card.dataset.menuDesc;
            const menuPrice = card.dataset.menuPrice;
            const menuImage = card.dataset.menuImage;
            const menuAvailable = card.dataset.menuAvailable === '1';

            // Store current menu ID and price
            document.getElementById('currentMenuId').value = menuId;
            document.getElementById('currentMenuPrice').value = menuPrice.replace(/\./g, '');

            title.textContent = menuName;
            desc.textContent = menuDesc;
            price.textContent = 'Rp ' + menuPrice;
            link.href = `/customer/catalogs/${menuId}`;

            // Set button state based on availability
            if (!menuAvailable) {
                addBtn.disabled = true;
                addBtn.textContent = 'Stok Habis';
            } else {
                addBtn.disabled = false;
                addBtn.textContent = 'Tambah ke Keranjang';
            }

            if (menuImage) {
                imgTag.src = menuImage;
                imgTag.style.display = 'block';
            } else {
                imgTag.style.display = 'none';
                imageContainer.innerHTML = '<div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 64px; color: #ccc;">☕</div>';
            }

            // Show bottom sheet
            overlay.classList.add('active');
            sheet.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeBottomSheet() {
            const overlay = document.getElementById('bottomSheetOverlay');
            const sheet = document.getElementById('productBottomSheet');

            overlay.classList.remove('active');
            sheet.classList.remove('active');
            document.body.style.overflow = '';
        }

        function updateQuantity(change) {
            currentQuantity += change;
            if (currentQuantity < 1) currentQuantity = 1;
            if (currentQuantity > 99) currentQuantity = 99;

            document.getElementById('bottomSheetQtyValue').textContent = currentQuantity;
            document.getElementById('bottomSheetQtyMinus').disabled = currentQuantity <= 1;
        }

        function addToCart() {
            const menuId = document.getElementById('currentMenuId').value;
            const quantity = currentQuantity;
            const note = document.getElementById('bottomSheetNote').value;
            const addBtn = document.getElementById('bottomSheetAddBtn');

            // Disable button while processing
            addBtn.disabled = true;
            const originalText = addBtn.textContent;
            addBtn.textContent = 'Menambahkan...';

            // Get or create guest token
            let guestToken = localStorage.getItem('guest_token');
            if (!guestToken) {
                guestToken = 'guest_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
                localStorage.setItem('guest_token', guestToken);
            }

            // Send request to add to cart via API
            fetch('/api/customer/cart/items', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Guest-Token': guestToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    menu_id: parseInt(menuId),
                    quantity: quantity,
                    note: note
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Show success toast
                showToast('Berhasil ditambahkan ke keranjang', 'success');
                
                // Trigger cart badge update
                window.dispatchEvent(new Event('cartUpdated'));
                
                // Reset button and close sheet
                setTimeout(() => {
                    closeBottomSheet();
                    addBtn.textContent = originalText;
                    addBtn.disabled = false;
                }, 800);
            })
            .catch(error => {
                console.error('Error:', error);
                // Show error toast
                showToast('Gagal menambahkan ke keranjang. Silakan coba lagi.', 'error');
                
                addBtn.textContent = originalText;
                addBtn.disabled = false;
            });
        }

        // Toast Notification Function
        function showToast(message, type = 'success') {
            let toast = document.getElementById('catalog-toast');
            if (!toast) {
                toast = document.createElement('div');
                toast.id = 'catalog-toast';
                
                // Check if mobile
                const isMobile = window.innerWidth <= 600;
                
                Object.assign(toast.style, {
                    position: 'fixed',
                    top: isMobile ? '100px' : '130px',
                    right: isMobile ? '12px' : '20px',
                    left: isMobile ? '12px' : 'auto',
                    zIndex: '9999',
                    minWidth: isMobile ? 'auto' : '320px',
                    maxWidth: isMobile ? 'calc(100% - 24px)' : '420px',
                    backgroundColor: '#2b211e',
                    borderRadius: isMobile ? '10px' : '12px',
                    boxShadow: '0 8px 24px rgba(0,0,0,0.4)',
                    padding: isMobile ? '12px 14px' : '16px 20px',
                    display: 'flex',
                    alignItems: 'center',
                    gap: isMobile ? '10px' : '12px',
                    transform: 'translateX(120%)',
                    transition: 'transform 0.5s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.5s ease',
                    opacity: '0'
                });

                toast.onclick = function () {
                    toast.style.transform = 'translateX(120%)';
                    toast.style.opacity = '0';
                };

                document.body.appendChild(toast);
            }

            // Check if mobile
            const isMobile = window.innerWidth <= 600;

            // Update Content - using theme colors
            const borderColor = type === 'error' ? '#ef4444' : '#D4A574';
            const iconColor = type === 'error' ? '#ef4444' : '#D4A574';
            const icon = type === 'error' 
                ? '✕' 
                : '✓';
            
            const iconSize = isMobile ? '28px' : '32px';
            const iconFontSize = isMobile ? '18px' : '20px';
            const textSize = isMobile ? '13px' : '14px';
            const closeIconSize = isMobile ? '16px' : '18px';

            toast.style.borderLeft = `4px solid ${borderColor}`;
            toast.innerHTML = `
                <div style="width: ${iconSize}; height: ${iconSize}; border-radius: 50%; background: ${iconColor}20; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <span style="font-size: ${iconFontSize}; font-weight: 700; color: ${iconColor};">${icon}</span>
                </div>
                <p style="margin: 0; font-family: 'Poppins', sans-serif; font-size: ${textSize}; font-weight: 500; color: #ffffff; flex-grow: 1; line-height: 1.4;">${message}</p>
                <span style="cursor: pointer; color: #ffffff; opacity: 0.5; flex-shrink: 0; padding: 4px;" onclick="this.parentElement.style.transform='translateX(120%)'; this.parentElement.style.opacity='0';">
                    <svg width="${closeIconSize}" height="${closeIconSize}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </span>
            `;

            // Show toast
            setTimeout(() => {
                toast.style.transform = 'translateX(0)';
                toast.style.opacity = '1';
            }, 100);

            // Auto hide after 3 seconds
            setTimeout(() => {
                toast.style.transform = 'translateX(120%)';
                toast.style.opacity = '0';
            }, 3500);
        }

        // Close on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeBottomSheet();
            }
        });

        // Handle swipe down to close
        let touchStartY = 0;
        let touchCurrentY = 0;

        document.getElementById('productBottomSheet')?.addEventListener('touchstart', function(e) {
            touchStartY = e.touches[0].clientY;
        });

        document.getElementById('productBottomSheet')?.addEventListener('touchmove', function(e) {
            touchCurrentY = e.touches[0].clientY;
            const diff = touchCurrentY - touchStartY;
            
            if (diff > 0) {
                this.style.transform = `translateY(${diff}px)`;
            }
        });

        document.getElementById('productBottomSheet')?.addEventListener('touchend', function(e) {
            const diff = touchCurrentY - touchStartY;
            
            if (diff > 100) {
                closeBottomSheet();
            }
            
            this.style.transform = '';
            touchStartY = 0;
            touchCurrentY = 0;
        });
    </script>
</x-customer.layout>