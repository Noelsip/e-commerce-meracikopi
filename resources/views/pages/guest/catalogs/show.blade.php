<x-customer.layout title="{{ $menu->name }} - Meracikopi">
    <!-- Main Content Wrapper -->
    <div style="min-height: 100vh; position: relative; padding-top: 40px; font-family: 'Inter', sans-serif;">

        <style>
            /* Hide announcement banner on product detail page */
            .announcement-bar {
                display: none !important;
            }

            /* Hide number input spinners */
            .no-spinners::-webkit-outer-spin-button,
            .no-spinners::-webkit-inner-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }

            .no-spinners {
                -moz-appearance: textfield;
            }

            /* Note input styling */
            .note-wrapper {
                transition: all 0.3s ease;
                border: 1px solid transparent;
            }

            .note-wrapper:hover,
            .note-wrapper:focus-within {
                border-color: #fff !important;
                background: rgba(255, 255, 255, 0.1) !important;
            }

            /* Mobile Responsive */
            @media (max-width: 768px) {
                .product-container {
                    padding: 0 16px !important;
                }

                .product-header {
                    margin-bottom: 16px !important;
                }

                .product-header span {
                    display: none;
                }

                .product-header a {
                    font-size: 14px !important;
                }

                .product-box {
                    height: auto !important;
                    min-height: auto;
<<<<<<<<< Temporary merge branch 1
                    padding: 20px 0;
=========
                    padding: 20px 0 24px 0;
                    background: radial-gradient(ellipse at center, rgba(72, 45, 27, 0.9) 0%, transparent 70%) !important;
                }

                .product-box > div:first-child,
                .product-box > div:nth-child(2) {
                    background: radial-gradient(ellipse at center, rgba(72, 45, 27, 0.6) 0%, transparent 60%) !important;
>>>>>>>>> Temporary merge branch 2
                }

                .product-content {
                    flex-direction: column !important;
<<<<<<<<< Temporary merge branch 1
                    gap: 24px !important;
                    padding: 20px !important;
                    align-items: center !important;
                }

                .product-image {
                    width: 250px !important;
                    height: 250px !important;
                    order: 1;
=========
                    gap: 16px !important;
                    padding: 12px 16px !important;
                    align-items: center !important;
                    justify-content: flex-start !important;
                }

                .product-image {
                    width: 140px !important;
                    height: 140px !important;
                    order: 1;
                }

                .product-image img {
                    width: 90% !important;
                    max-width: 130px !important;
>>>>>>>>> Temporary merge branch 2
                }

                .product-details {
                    width: 100% !important;
<<<<<<<<< Temporary merge branch 1
                    max-width: 350px;
=========
                    max-width: 280px;
>>>>>>>>> Temporary merge branch 2
                    text-align: center;
                    order: 2;
                }

                .product-details h1 {
                    font-size: 20px !important;
                    margin-bottom: 2px !important;
                }

                .product-details > div:nth-child(2) {
                    font-size: 14px !important;
                    margin-bottom: 0px !important;
                }

                .product-details > div:nth-child(3) {
                    font-size: 10px !important;
                    margin-bottom: 14px !important;
                }

                .product-quantity {
                    justify-content: center !important;
<<<<<<<<< Temporary merge branch 1
                    margin-bottom: 20px !important;
                }

                .product-details button[type="button"] {
                    max-width: 350px;
                    margin: 0 auto;
=========
                    margin-bottom: 12px !important;
                    gap: 12px !important;
                }

                .product-quantity button {
                    width: 32px !important;
                    height: 32px !important;
                    font-size: 16px !important;
                }

                .product-quantity input {
                    font-size: 14px !important;
                    width: 35px !important;
                }

                .product-details button[type="button"],
                .product-details button:not(.product-quantity button) {
                    width: 100% !important;
                    max-width: 280px;
                    margin: 0 auto;
                    padding: 10px 0 !important;
                    font-size: 14px !important;
                    border-radius: 30px !important;
                }

                .description-section {
                    padding: 20px 16px 40px 16px !important;
>>>>>>>>> Temporary merge branch 2
                }

                .description-grid {
                    grid-template-columns: 1fr !important;
                    gap: 24px !important;
                }

                .description-grid h3 {
                    font-size: 16px !important;
                    margin-bottom: 12px !important;
                    padding-bottom: 8px !important;
                }

                .description-grid p {
                    font-size: 13px !important;
                }

                .note-wrapper {
                    padding: 12px !important;
                    border-radius: 10px !important;
                }

                .note-wrapper textarea {
                    font-size: 13px !important;
                }

                /* Related Menus Mobile */
                .related-menus-section {
                    padding: 0 16px 60px 16px !important;
                }

                .related-menus-section h3 {
                    font-size: 18px !important;
                    margin-bottom: 20px !important;
                }
            }

            /* Small Mobile (< 400px) */
            @media (max-width: 400px) {
                .product-image {
                    width: 150px !important;
                    height: 150px !important;
                }

                .product-image img {
                    max-width: 140px !important;
                }

                .product-details {
                    max-width: 260px;
                }

                .product-details h1 {
                    font-size: 22px !important;
                }

                .product-details > div:nth-child(2) {
                    font-size: 16px !important;
                }

                .product-quantity button {
                    width: 32px !important;
                    height: 32px !important;
                }
            }
        </style>

        <div class="product-container"
            style="max-width: 1440px; margin: 0 auto; padding: 0 40px; position: relative; z-index: 10;">
            <!-- Header / Breadcrumb Area -->
            <div class="product-header" style="display: flex; align-items: center; margin-bottom: 40px;">
                <a href="{{ url('/customer/catalogs') }}"
                    style="color: #fff; text-decoration: none; font-size: 16px; font-weight: 500; display: flex; align-items: center; gap: 8px;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 12H5" />
                        <path d="M12 19l-7-7 7-7" />
                    </svg>
                    Back
                </a>
                <span
                    style="color: #fff; margin-left: 32px; font-size: 16px; letter-spacing: 0.05em; font-weight: 500;">
                    Menu Pilihan, Untuk Setiap Selera
                </span>
            </div>
        </div>

        <!-- Combined Container for Background Box & Content -->
        <div class="product-box" style="
            position: relative; 
            width: 100%;
            max-width: 1440px; 
            height: 350px; 
            margin: 0 auto;
        ">
            <!-- Background Layer -->
            <div style="
                position: absolute;
                inset: 0;
                background: linear-gradient(to right, transparent, rgba(72, 45, 27, 0.8) 20%, rgba(72, 45, 27, 1) 50%, rgba(72, 45, 27, 0.8) 80%, transparent);
                z-index: 1;
                pointer-events: none;
            "></div>

            <!-- Background Layer -->
            <div style="
                position: absolute;
                inset: 0;
                background: linear-gradient(to right, transparent, rgba(72, 45, 27, 0.8) 20%, rgba(72, 45, 27, 1) 50%, rgba(72, 45, 27, 0.8) 80%, transparent);
                z-index: 1;
                pointer-events: none;
            "></div>

            <!-- Content Layer -->
            <div class="product-content" style="
                position: relative;
                z-index: 2;
                width: 100%;
                height: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 60px;
            " x-data="{
                quantity: 1,
                note: '',
                loading: false,
                init() {
                    this.$watch('quantity', (value) => {
                        if (value < 1 || value === '') {
                             this.quantity = 1;
                        }
                    });
                },
                addToCart() {
                    // Double check validation
                    if (this.quantity < 1) this.quantity = 1;

                    this.loading = true;
                    const token = localStorage.getItem('guest_token');
                    
                    fetch('/api/customer/cart/items', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-GUEST-TOKEN': token || '',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                        },
                        body: JSON.stringify({
                             menu_id: {{ $menu->id }},
                             quantity: this.quantity,
                             note: this.note
                        })
                    })
                    .then(response => {
                        const newToken = response.headers.get('X-GUEST-TOKEN');
                        if(newToken) localStorage.setItem('guest_token', newToken);

                        if (!response.ok) throw new Error('Network response was not ok');
                        return response.json();
                    })
                    .then(data => {
                        window.showCustomerToast('Berhasil menambahkan ke keranjang!', 'success');
                        this.quantity = 1;
                        this.note = '';
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        window.showCustomerToast('Gagal menambahkan ke keranjang. Silakan coba lagi.', 'error');
                    })
                    .finally(() => {
                        this.loading = false;
                    });
                }
            }">

                <script>
                    window.showCustomerToast = function (message, type = 'success') {
                        // Create toast container if not exists
                        let toast = document.getElementById('customer-toast');
                        if (!toast) {
                            toast = document.createElement('div');
                            toast.id = 'customer-toast';
                            Object.assign(toast.style, {
                                position: 'fixed',
                                top: '130px',
                                right: '20px',
                                zIndex: '9999',
                                minWidth: '320px',
                                maxWidth: '420px',
                                backgroundColor: '#2b211e',
                                borderRadius: '6px',
                                boxShadow: '0 4px 12px rgba(0,0,0,0.3)',
                                padding: '16px',
                                display: 'flex',
                                alignItems: 'start',
                                gap: '12px',
                                transform: 'translateX(120%)',
                                transition: 'transform 0.6s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.6s ease',
                                opacity: '0'
                            });

                            // Close button inside
                            toast.onclick = function () {
                                toast.style.transform = 'translateX(120%)';
                                toast.style.opacity = '0';
                            };

                            document.body.appendChild(toast);
                        }

                        // Update Content
                        const borderColor = type === 'error' ? '#ef4444' : '#D4A574';
                        toast.style.borderLeft = `4px solid ${borderColor}`;
                        toast.innerHTML = `
                            <p style="margin: 0; font-family: 'Inter', sans-serif; font-size: 14px; font-weight: 500; color: #f0f2bd; flex-grow: 1;">${message}</p>
                            <span style="cursor: pointer; color: #f0f2bd; opacity: 0.7;">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                            </span>
                        `;

                        // Show
                        requestAnimationFrame(() => {
                            toast.style.transform = 'translateX(0)';
                            toast.style.opacity = '1';
                        });

                        // Auto Hide
                        if (window.toastTimeout) clearTimeout(window.toastTimeout);
                        window.toastTimeout = setTimeout(() => {
                            toast.style.transform = 'translateX(120%)';
                            toast.style.opacity = '0';
                        }, 3000);
                    }
                </script>

                <!-- Product Image -->
                <div class="product-image"
                    style="position: relative; width: 300px; height: 300px; display: flex; align-items: center; justify-content: center;">
                    <!-- Shadow -->
                    <div
                        style="position: absolute; bottom: 10%; width: 60%; height: 20px; background: radial-gradient(ellipse at center, rgba(0,0,0,0.6) 0%, transparent 70%); filter: blur(10px);">
                    </div>

                    @if($menu->image_path)
                        <img src="{{ asset($menu->image_path) }}" alt="{{ $menu->name }}"
                            style="width: 80%; height: auto; object-fit: contain; filter: drop-shadow(0 20px 30px rgba(0,0,0,0.5));">
                    @else
                        <div
                            style="width: 100%; height: 100%; background: rgba(255,255,255,0.05); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <span style="font-size: 80px;">☕</span>
                        </div>
                    @endif
                </div>

                <!-- Product Details -->
                <div class="product-details" style="width: 250px;">
                    <h1
                        style="font-family: 'Inter', sans-serif; font-size: 32px; font-weight: 800; color: #F0F2BD; margin: 0 0 4px 0; line-height: 1.2;">
                        {{ $menu->name }}
                    </h1>

                    <div style="font-size: 20px; color: #fff; margin-bottom: 2px; font-weight: 500;">
                        Rp {{ number_format($menu->price, 0, ',', '.') }}
                    </div>

                    <div
                        style="color: #aaa; font-size: 12px; margin-bottom: 24px; font-weight: 400; font-style: italic;">
                        Hot/Ice
                    </div>

                    <div class="product-quantity"
                        style="display: flex; align-items: center; gap: 16px; margin-bottom: 16px;">
                        <button @click="quantity > 1 ? quantity-- : null"
                            style="width: 32px; height: 32px; border-radius: 50%; background: #F0F2BD; border: none; font-size: 20px; font-weight: 600; color: #2a1b14; cursor: pointer; display: flex; align-items: center; justify-content: center;">-</button>

                        <input type="number" x-model.number="quantity" min="1" @input="validateQuantity()"
                            @change="validateQuantity()"
                            style="width: 50px; background: transparent; border: none; font-size: 18px; color: #fff; font-weight: 500; text-align: center; outline: none; -moz-appearance: textfield;"
                            class="no-spinners">

                        <button @click="quantity++"
                            style="width: 32px; height: 32px; border-radius: 50%; background: #F0F2BD; border: none; font-size: 20px; font-weight: 600; color: #2a1b14; cursor: pointer; display: flex; align-items: center; justify-content: center;">+</button>
                    </div>

                    <button @click="addToCart" :disabled="loading" style="
                        width: 100%;
                        background-color: #000; 
                        color: #fff; 
                        border: none; 
                        padding: 10px 0; 
                        border-radius: 25px; 
                        font-size: 16px; 
                        font-weight: 500; 
                        cursor: pointer; 
                        display: flex; 
                        align-items: center; 
                        justify-content: center;
                        gap: 10px;
                        transition: all 0.3s ease;
                    " onmouseover="this.style.backgroundColor='#1a1a1a'"
                        onmouseout="this.style.backgroundColor='#000'">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="9" cy="21" r="1"></circle>
                            <circle cx="20" cy="21" r="1"></circle>
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                        </svg>
                        <span x-text="loading ? 'Memproses...' : 'Tambah'">Tambah</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Description and Notes Section -->
        <div class="description-section"
            style="max-width: 1440px; margin: 0 auto; padding: 40px; position: relative; z-index: 10;">
            <div class="description-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 80px;">
                <!-- Description -->
                <div>
                    <h3
                        style="font-size: 18px; color: #fff; font-weight: 600; margin-bottom: 16px; border-bottom: 1px solid rgba(255,255,255,0.2); padding-bottom: 8px;">
                        Deskripsi Produk
                    </h3>
                    <p style="color: #aaa; font-size: 14px; line-height: 1.6;">
                        {{ $menu->description ?? 'Deskripsi produk belum tersedia.' }}
                    </p>
                </div>

                <!-- Notes -->
                <div>
                    <h3
                        style="font-size: 18px; color: #fff; font-weight: 600; margin-bottom: 16px; border-bottom: 1px solid rgba(255,255,255,0.2); padding-bottom: 8px;">
                        Catatan
                    </h3>
                    <div class="note-wrapper"
                        style="background: rgba(255,255,255,0.05); border-radius: 12px; padding: 16px;">
                        <textarea x-model="note" placeholder="Catatan opsional..."
                            style="width: 100%; background: transparent; border: none; color: #fff; font-size: 14px; outline: none; resize: none; font-family: 'Inter', sans-serif;"
                            rows="6"></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Menus Section -->
        @if(isset($relatedMenus) && $relatedMenus->count() > 0)
            <div class="related-menus-section"
                style="max-width: 1440px; margin: 0 auto; padding: 0 40px 80px 40px; position: relative; z-index: 10;">
                <h3 style="font-size: 24px; color: #fff; font-weight: 600; margin-bottom: 32px; text-align: center;">Menu
                    Lainnya</h3>


                <!-- Horizontal Scroll Container -->
                <div class="other-menus-scroll-container">
                    @foreach($relatedMenus as $related)
                        <a href="{{ route('catalogs.show', $related->id) }}" class="menu-rec-card">
                            <div class="menu-rec-image"
                                style="background-image: url('{{ filter_var($related->image_path, FILTER_VALIDATE_URL) ? $related->image_path : asset($related->image_path) }}');">
                                @if(!$related->image_path)
                                    <span
                                        style="display:flex; height:100%; align-items:center; justify-content:center; font-size:24px;">☕</span>
                                @endif
                            </div>

                            <div class="menu-rec-name" title="{{ $related->name }}">{{ $related->name }}</div>

                            <div class="menu-rec-footer">
                                <div class="menu-rec-price">Rp {{ number_format($related->price, 0, ',', '.') }}</div>
                                @if($related->is_available)
                                    <span class="menu-rec-badge-available">
                                        Tersedia
                                    </span>
                                @else
                                    <span class="menu-rec-badge-unavailable">
                                        Habis
                                    </span>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>

                <style>
                    /* Base Styles (Mobile & General) */
                    .other-menus-scroll-container {
                        display: flex;
                        /* Mobile default: Flex for scrolling */
                        gap: 16px;
                        overflow-x: auto;
                        padding-bottom: 20px;
                        scrollbar-width: none;
                        /* Firefox */
                        -ms-overflow-style: none;
                        /* IE 10+ */
                        margin: 0 -20px;
                        /* Values to hit edges on mobile */
                        padding: 0 20px 20px 20px;
                        /* Padding inside scroll */
                    }

                    .other-menus-scroll-container::-webkit-scrollbar {
                        display: none;
                        /* Chrome/Safari */
                    }

                    .menu-rec-card {
                        flex: 0 0 160px;
                        /* Mobile width */
                        background: linear-gradient(145deg, rgba(42, 27, 20, 0.6), rgba(42, 27, 20, 0.3));
                        border: 1px solid rgba(202, 120, 66, 0.1);
                        border-radius: 12px;
                        padding: 12px;
                        display: flex;
                        flex-direction: column;
                        justify-content: space-between;
                        gap: 8px;
                        transition: transform 0.2s ease, box-shadow 0.2s ease;
                        text-decoration: none;
                        position: relative;
                        overflow: hidden;
                    }

                    .menu-rec-card:hover {
                        transform: translateY(-4px);
                        background: linear-gradient(145deg, rgba(62, 37, 26, 0.7), rgba(42, 27, 20, 0.4));
                        border-color: rgba(202, 120, 66, 0.2);
                        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
                    }

                    .menu-rec-image {
                        width: 100%;
                        aspect-ratio: 1/1;
                        border-radius: 8px;
                        background-size: cover;
                        background-position: center;
                        background-color: #1a1410;
                    }

                    .menu-rec-name {
                        font-size: 14px;
                        font-weight: 600;
                        color: white;
                        white-space: nowrap;
                        overflow: hidden;
                        text-overflow: ellipsis;
                        margin-top: 4px;
                        margin-bottom: 4px;
                        /* Space before footer */
                    }

                    .menu-rec-footer {
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                        margin-top: auto;
                        gap: 8px;
                    }

                    .menu-rec-price {
                        font-size: 13px;
                        font-weight: 700;
                        color: #CA7842;
                        white-space: nowrap;
                    }

                    .menu-rec-badge-available {
                        padding: 4px 12px;
                        background: linear-gradient(135deg, #CA7842, #8B5E3C);
                        color: #f0f2ae;
                        font-size: 11px;
                        font-weight: 600;
                        border-radius: 20px;
                        box-shadow: 0 2px 8px rgba(202, 120, 66, 0.3);
                        white-space: nowrap;
                    }

                    .menu-rec-badge-unavailable {
                        padding: 4px 12px;
                        background-color: #3e302b;
                        color: #a89890;
                        font-size: 11px;
                        font-weight: 500;
                        border-radius: 20px;
                        white-space: nowrap;
                    }

                    /* Desktop Styles (Grid Layout) */
                    @media (min-width: 769px) {
                        .other-menus-scroll-container {
                            display: grid;
                            /* Switch to Grid */
                            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
                            /* Responsive columns */
                            gap: 32px;
                            overflow: visible;
                            /* No scroll */
                            margin: 0;
                            padding: 0;
                        }

                        .menu-rec-card {
                            flex: none;
                            /* Disable flex behavior */
                            width: auto;
                            padding: 16px;
                            /* Larger padding */
                            border-radius: 16px;
                            gap: 12px;
                        }

                        .menu-rec-name {
                            font-size: 18px;
                            /* Larger font */
                            margin-bottom: 0;
                        }

                        .menu-rec-price {
                            font-size: 18px;
                            /* Larger price */
                        }

                        .menu-rec-btn {
                            font-size: 13px;
                            padding: 8px 18px;
                        }
                    }
                </style>

                <!-- Mobile padding fix style -->
                <style>
                    @media (max-width: 768px) {
                        .related-menus-section {
                            padding: 0 20px 80px 20px !important;
                        }
                    }
                </style>
            </div>
        @endif
    </div>
</x-customer.layout>