<x-customer.cart-layout>
    <!-- Alpine Data Scope -->
    <div x-data="cartManager" class="cart-page-container">

        <!-- Loading State -->
        <div x-show="loading" class="flex justify-center items-center py-20">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-[#CA7842]"></div>
        </div>

        <!-- Empty State -->
        <div x-show="!loading && items.length === 0" class="empty-cart-container" style="display: none;">
            <svg class="empty-cart-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                </path>
            </svg>
            <h3 class="empty-cart-title">Keranjang Anda Kosong</h3>
            <p class="empty-cart-subtitle">Silakan pilih menu favorit Anda terlebih dahulu</p>
            <a href="{{ route('catalogs.index') }}" class="empty-cart-btn">
                Lihat Menu
            </a>
        </div>

        <!-- Cart Content -->
        <div x-show="!loading && items.length > 0" style="display: none;">

            <!-- Cart Table Header -->
            <div class="cart-table-header">
                <!-- Select All Checkbox -->
                <div class="header-checkbox">
                    <input type="checkbox" class="cart-checkbox select-all-checkbox" :checked="allSelected"
                        @change="toggleSelectAll">
                </div>
                <span class="header-produk">Produk</span>
                <span class="header-harga">Harga Satuan</span>
                <span class="header-kuantitas">Kuantitas</span>
                <span class="header-total">Total Harga</span>
                <span class="header-aksi">Aksi</span>
            </div>

            <!-- Cart Items -->
            <div class="cart-items-list">
                <template x-for="item in items" :key="item.id">
                    <div class="cart-item-wrapper" x-data="{ swiped: false, startX: 0, currentX: 0 }"
                        @touchstart="startX = $event.touches[0].clientX; currentX = 0"
                        @touchmove="currentX = $event.touches[0].clientX - startX"
                        @touchend="if(currentX < -50) { swiped = true } else if(currentX > 50) { swiped = false }">
                        <div class="cart-item-row" :class="{ 'swiped': swiped }">
                            <!-- Item Checkbox -->
                            <div class="flex justify-center">
                                <input type="checkbox" class="cart-checkbox item-checkbox" :checked="item.selected"
                                    @change="toggleItemSelection(item.id)">
                            </div>

                            <!-- Product Info -->
                            <div class="product-info">
                                <template x-if="item.menu_image">
                                    <img :src="item.menu_image" alt="Product" class="product-image">
                                </template>
                                <template x-if="!item.menu_image">
                                    <div class="product-image-placeholder"></div>
                                </template>
                                <span class="product-name" x-text="item.menu_name"></span>
                            </div>

                            <!-- Price -->
                            <span class="product-price" x-text="formatRupiah(item.price)"></span>

                            <!-- Quantity -->
                            <div class="quantity-controls">
                                <button type="button" class="quantity-btn quantity-btn-minus"
                                    @click="updateQuantity(item.id, item.quantity - 1)" :disabled="item.updating">
                                    −
                                </button>
                                <span class="quantity-value" x-text="item.quantity"></span>
                                <button type="button" class="quantity-btn quantity-btn-plus"
                                    @click="updateQuantity(item.id, item.quantity + 1)" :disabled="item.updating">
                                    +
                                </button>
                            </div>

                            <!-- Subtotal -->
                            <span class="total-price" x-text="formatRupiah(item.subtotal)"></span>

                            <!-- Delete (Desktop only) -->
                            <button class="delete-btn delete-btn-desktop" @click="removeItem(item.id)">Hapus</button>
                        </div>

                        <!-- Swipe Delete Button (Mobile only) -->
                        <button class="swipe-delete-btn" @click="removeItem(item.id); swiped = false">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path
                                    d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                </path>
                                <line x1="10" y1="11" x2="10" y2="17"></line>
                                <line x1="14" y1="11" x2="14" y2="17"></line>
                            </svg>
                            <span>Hapus</span>
                        </button>
                    </div>
                </template>
            </div>

            <!-- Spacer for fixed footer -->
            <div style="height: 100px;"></div>
        </div>

        <!-- Cart Summary Footer -->
        <div x-show="!loading && items.length > 0" class="cart-summary-wrapper" style="display: none;">
            <div class="cart-summary-container">
                <div class="cart-summary">
                    <!-- Left side: Checkbox + Pilih Semua -->
                    <div class="cart-summary-left">
                        <input type="checkbox" class="cart-checkbox select-all-checkbox" :checked="allSelected"
                            @change="toggleSelectAll">
                        <span class="select-all-text">Pilih Semua</span>
                    </div>

                    <!-- Right side: Total + Checkout -->
                    <div class="cart-summary-right">
                        <div class="cart-total-section">
                            <span class="cart-total-label">Total (<span x-text="selectedCount"></span> Produk):</span>
                            <span class="cart-total-amount" x-text="formatRupiah(selectedTotal)"></span>
                        </div>
                        <button class="checkout-btn" @click="proceedToCheckout"
                            :disabled="selectedCount === 0">Checkout</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Alpine Logic -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('cartManager', () => ({
                items: [],
                loading: true,
                totalPrice: 0,
                token: localStorage.getItem('guest_token') || '',

                init() {
                    this.fetchCart();
                },

                // Computed: Check if all items are selected
                get allSelected() {
                    return this.items.length > 0 && this.items.every(item => item.selected);
                },

                // Computed: Count of selected items
                get selectedCount() {
                    return this.items.filter(item => item.selected).length;
                },

                // Computed: Total price of selected items only
                get selectedTotal() {
                    return this.items
                        .filter(item => item.selected)
                        .reduce((sum, item) => sum + item.subtotal, 0);
                },

                // Toggle all items selection
                toggleSelectAll() {
                    const newState = !this.allSelected;
                    this.items = this.items.map(item => ({
                        ...item,
                        selected: newState
                    }));
                },

                // Toggle individual item selection
                toggleItemSelection(itemId) {
                    const itemIndex = this.items.findIndex(i => i.id === itemId);
                    if (itemIndex !== -1) {
                        this.items[itemIndex].selected = !this.items[itemIndex].selected;
                    }
                },

                formatRupiah(amount) {
                    return 'Rp. ' + new Intl.NumberFormat('id-ID').format(amount);
                },

                async fetchCart(showLoading = true) {
                    if (showLoading) this.loading = true;
                    try {
                        const response = await fetch('/api/customer/cart', {
                            headers: {
                                'X-GUEST-TOKEN': this.token,
                                'Accept': 'application/json'
                            }
                        });

                        // Update token if rotated
                        const newToken = response.headers.get('X-GUEST-TOKEN');
                        if (newToken) {
                            this.token = newToken;
                            localStorage.setItem('guest_token', newToken);
                        }

                        const result = await response.json();

                        // Add 'updating' and 'selected' state to each item
                        this.items = (result.data.items || []).map(item => ({
                            ...item,
                            updating: false,
                            selected: true // Default selected
                        }));

                        this.totalPrice = result.data.total_price || 0;
                    } catch (error) {
                        console.error('Error fetching cart:', error);
                    } finally {
                        if (showLoading) this.loading = false;
                    }
                },

                async updateQuantity(itemId, newQty) {
                    if (newQty < 1) return; // Prevent < 1

                    // Find item and set updating state
                    const itemIndex = this.items.findIndex(i => i.id === itemId);
                    if (itemIndex === -1) return;
                    this.items[itemIndex].updating = true;

                    try {
                        const response = await fetch(`/api/customer/cart/items/${itemId}`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-GUEST-TOKEN': this.token,
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                            },
                            body: JSON.stringify({ quantity: newQty })
                        });

                        if (response.ok) {
                            await this.fetchCart(false); // Background update
                        } else {
                            this.items[itemIndex].updating = false;
                        }
                    } catch (error) {
                        console.error('Error updating quantity:', error);
                        this.items[itemIndex].updating = false;
                    }
                },

                async removeItem(itemId) {
                    if (!confirm('Apakah Anda yakin ingin menghapus produk ini?')) return;

                    try {
                        const response = await fetch(`/api/customer/cart/items/${itemId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-GUEST-TOKEN': this.token,
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                            }
                        });

                        if (response.ok) {
                            this.fetchCart();
                        }
                    } catch (error) {
                        console.error('Error removing item:', error);
                    }
                },

                proceedToCheckout() {
                    // Check if any item is selected
                    if (this.selectedCount === 0) {
                        // Show error modal
                        const modal = document.getElementById('errorModal');
                        if (modal) {
                            modal.classList.add('show');
                        }
                        return;
                    }

                    // Store selected item IDs in localStorage for checkout
                    const selectedIds = this.items
                        .filter(item => item.selected)
                        .map(item => item.id);
                    localStorage.setItem('selected_cart_items', JSON.stringify(selectedIds));

                    window.location.href = '/customer/checkout';
                }
            }));
        });
    </script>

    <!-- Error Modal (Reused) -->
    <div id="errorModal" class="error-modal-overlay">
        <!-- ... existing modal content ... -->
    </div>

    <!-- Keep existing Styles -->
    <style>
        /* ... existing styles ... */
        /* Error Modal Styles */
        .error-modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            z-index: 9999;
            justify-content: center;
            align-items: center;
            animation: fadeIn 0.3s ease;
        }

        .error-modal-overlay.show {
            display: flex;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: scale(0.9) translateY(-20px);
            }

            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .error-modal {
            background: linear-gradient(145deg, #3a2a22, #2a1b14);
            border: 1px solid rgba(202, 120, 66, 0.3);
            border-radius: 16px;
            padding: 40px 50px;
            text-align: center;
            max-width: 420px;
            width: 90%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
            animation: slideIn 0.3s ease;
        }

        .error-modal-icon {
            width: 80px;
            height: 80px;
            background: rgba(202, 120, 66, 0.15);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
        }

        .error-modal-icon svg {
            color: #CA7842;
        }

        .error-modal-title {
            color: white;
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 12px;
            font-family: 'Poppins', sans-serif;
        }

        .error-modal-message {
            color: rgba(255, 255, 255, 0.7);
            font-size: 14px;
            font-weight: 400;
            margin-bottom: 28px;
            line-height: 1.6;
            font-family: 'Poppins', sans-serif;
        }

        .error-modal-btn {
            background: linear-gradient(145deg, #CA7842, #b5693a);
            color: white;
            border: none;
            padding: 14px 40px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Poppins', sans-serif;
        }

        .error-modal-btn:hover {
            background: linear-gradient(145deg, #d88a52, #CA7842);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(202, 120, 66, 0.3);
        }

        .error-modal-btn:active {
            transform: translateY(0);
        }
    </style>

    <!-- Empty Cart Styles -->
    <style>
        /* Empty Cart Container */
        .empty-cart-container {
            text-align: center;
            padding: 80px 20px;
            max-width: 500px;
            margin: 0 auto;
            min-height: calc(100vh - 200px);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .empty-cart-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 24px;
            color: #CA7842;
            opacity: 0.6;
        }

        .empty-cart-title {
            color: #ffffff;
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 12px;
            line-height: 1.3;
        }

        .empty-cart-subtitle {
            color: rgba(255, 255, 255, 0.75);
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 32px;
        }

        .empty-cart-btn {
            display: inline-block;
            padding: 14px 32px;
            background-color: #CA7842;
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(202, 120, 66, 0.3);
        }

        .empty-cart-btn:hover {
            background-color: #b5693a;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(202, 120, 66, 0.4);
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .empty-cart-container {
                padding: 60px 24px;
                min-height: calc(100vh - 180px);
            }

            .empty-cart-icon {
                width: 64px;
                height: 64px;
                margin-bottom: 20px;
            }

            .empty-cart-title {
                font-size: 20px;
                margin-bottom: 10px;
                padding: 0 10px;
            }

            .empty-cart-subtitle {
                font-size: 15px;
                line-height: 1.5;
                margin-bottom: 28px;
                padding: 0 10px;
                color: rgba(255, 255, 255, 0.8);
            }

            .empty-cart-btn {
                padding: 12px 28px;
                font-size: 15px;
            }
        }

        @media (max-width: 480px) {
            .empty-cart-container {
                padding: 50px 16px;
            }

            .empty-cart-icon {
                width: 56px;
                height: 56px;
            }

            .empty-cart-title {
                font-size: 18px;
            }

            .empty-cart-subtitle {
                font-size: 14px;
                margin-bottom: 24px;
            }

            .empty-cart-btn {
                padding: 11px 24px;
                font-size: 14px;
                width: auto;
                max-width: 200px;
            }
        }
    </style>
</x-customer.cart-layout>