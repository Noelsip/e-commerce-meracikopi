<x-customer.layout title="{{ $menu->name }} - Meracikopi">
    <!-- Main Content Wrapper -->
    <div
        style="min-height: 100vh; position: relative; overflow: hidden; padding-top: 40px; font-family: 'Inter', sans-serif;">

        <style>
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
                    padding: 0 20px !important;
                }

                .product-header {
                    margin-bottom: 20px !important;
                }

                .product-header span {
                    display: none;
                }

                .product-box {
                    height: auto !important;
                    min-height: 500px;
                }

                .product-content {
                    flex-direction: column !important;
                    gap: 30px !important;
                    padding: 30px 20px !important;
                }

                .product-image {
                    width: 200px !important;
                    height: 200px !important;
                }

                .product-details {
                    width: 100% !important;
                    text-align: center;
                }

                .product-details h1 {
                    font-size: 28px !important;
                }

                .product-quantity {
                    justify-content: center !important;
                }

                .description-grid {
                    grid-template-columns: 1fr !important;
                    gap: 30px !important;
                }

                .description-section {
                    padding: 20px !important;
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

                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 24px;">
                    @foreach($relatedMenus as $related)
                        <a href="{{ route('catalogs.show', $related->id) }}"
                            style="background: #2b211e; border-radius: 16px; overflow: hidden; text-decoration: none; border: 1px solid #3e302b; display: block; transition: transform 0.3s;"
                            onmouseover="this.style.transform='translateY(-4px)'"
                            onmouseout="this.style.transform='translateY(0)'">

                            <!-- Image -->
                            <!-- Image -->
                            <div style="aspect-ratio: 1; background-color: #3e302b; overflow: hidden; position: relative; display: flex; align-items: center; justify-content: center;">
                                <img src="{{ filter_var($related->image_path, FILTER_VALIDATE_URL) ? $related->image_path : asset($related->image_path) }}"
                                    alt="{{ $related->name }}"
                                    style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s;"
                                    onerror="this.style.display='none'; this.parentElement.innerHTML='<span style=\'font-size:40px;\'>☕</span>'">
                            </div>

                            <!-- Content -->
                            <div style="padding: 20px;">
                                <h4
                                    style="font-weight: 600; color: #f0f2bd; font-size: 18px; margin-bottom: 4px; margin-top: 0;">
                                    {{ $related->name }}</h4>
                                <p
                                    style="color: #a89890; font-size: 14px; margin-bottom: 16px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; margin-top: 0;">
                                    {{ $related->description }}
                                </p>
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <span style="font-size: 18px; font-weight: bold; color: #CA7842;">Rp
                                        {{ number_format($related->price, 0, ',', '.') }}</span>
                                    
                                    @if($related->is_available)
                                        <span style="background: #C27C4E; color: #fff; padding: 6px 16px; border-radius: 20px; font-size: 12px; font-weight: 600; box-shadow: 0 4px 10px rgba(194, 124, 78, 0.3);">
                                            Tersedia
                                        </span>
                                    @else
                                        <span style="background: #3e302b; color: #aaa; padding: 6px 16px; border-radius: 20px; font-size: 12px; font-weight: 600; border: 1px solid #555;">
                                            Habis
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

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