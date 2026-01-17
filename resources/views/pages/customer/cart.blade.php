<x-customer.cart-layout>
    <!-- Alpine Data Scope -->
    <div x-data="cartManager" class="cart-page-container">

        <!-- Loading State -->
        <div x-show="loading" class="flex justify-center items-center py-20">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-[#CA7842]"></div>
        </div>

        <!-- Empty State -->
        <div x-show="!loading && items.length === 0" class="text-center py-20" style="display: none;">
            <svg class="w-16 h-16 mx-auto mb-4 text-[#CA7842] opacity-50" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                </path>
            </svg>
            <h3 class="text-white text-xl font-medium mb-2">Keranjang Anda Kosong</h3>
            <p class="text-white/60 mb-8">Silakan pilih menu favorit Anda terlebih dahulu</p>
            <a href="{{ route('catalogs.index') }}"
                class="inline-block px-8 py-3 bg-[#CA7842] text-white rounded-full hover:bg-[#b5693a] transition-colors">
                Lihat Menu
            </a>
        </div>

        <!-- Cart Content -->
        <div x-show="!loading && items.length > 0" style="display: none;">

            <!-- Cart Table Header -->
            <div class="cart-table-header">
                <!-- Checkbox removed for simplicity or handled differently if implementing bulk actions -->
                <div style="width: 24px;"></div>
                <span class="header-produk">Produk</span>
                <span class="header-harga">Harga Satuan</span>
                <span class="header-kuantitas">Kuantitas</span>
                <span class="header-total">Total Harga</span>
                <span class="header-aksi">Aksi</span>
            </div>

            <!-- Cart Items -->
            <div class="cart-items-list">
                <template x-for="item in items" :key="item.id">
                    <div class="cart-item-row">
                        <!-- Checkbox (Optional, currently just placeholder) -->
                        <div class="flex justify-center">
                            <input type="checkbox" class="cart-checkbox item-checkbox" checked disabled>
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

                        <!-- Delete -->
                        <button class="delete-btn" @click="removeItem(item.id)">Hapus</button>
                    </div>
                </template>
            </div>

            <!-- Spacer for fixed footer -->
            <div style="height: 100px;"></div>
        </div>

        <!-- Cart Summary Footer -->
        <div x-show="!loading && items.length > 0" class="cart-summary-wrapper" style="display: none;">
            <div class="cart-summary">
                <div class="cart-summary-left">
                    <!-- Bulk actions can be added here -->
                </div>
                <div class="cart-total-section">
                    <span class="cart-total-label">Total (<span x-text="items.length"></span> Produk):</span>
                    <span class="cart-total-amount" x-text="formatRupiah(totalPrice)"></span>
                </div>
                <button class="checkout-btn" @click="proceedToCheckout">Checkout</button>
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

                        // Add 'updating' state to each item
                        this.items = (result.data.items || []).map(item => ({
                            ...item,
                            updating: false
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
</x-customer.cart-layout>