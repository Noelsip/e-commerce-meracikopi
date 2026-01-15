<x-customer.checkout-layout>
    <div class="checkout-page-container">
        <!-- Order Type Tabs (Display Only - synced with navbar dropdown) -->
        <div class="order-type-tabs">
            <span class="order-type-tab-label">Tipe Pemesanan</span>
            <span class="order-type-tab-value" id="orderTypeDisplay">Dine In</span>
        </div>

        <!-- Main Content -->
        <div class="checkout-content">
            <!-- Left: Order Items -->
            <div class="order-items-section">
                <p class="section-title">Pesanan</p>

                <!-- Order Item 1 -->
                <div class="order-item-card">
                    <input type="checkbox" class="order-item-checkbox" checked>
                    <div class="order-item-image"></div>
                    <div class="order-item-details">
                        <span class="order-item-name">Coffe Beans Arabica</span>
                        <div class="order-item-quantity">
                            <div class="quantity-wrapper">
                                <select class="quantity-dropdown" onchange="updateOrderTotal()">
                                    <option value="1" selected>Jumlah: 1</option>
                                    <option value="2">Jumlah: 2</option>
                                    <option value="3">Jumlah: 3</option>
                                    <option value="4">Jumlah: 4</option>
                                    <option value="5">Jumlah: 5</option>
                                </select>
                            </div>
                            <span class="order-item-delete" onclick="removeOrderItem(this)">×</span>
                        </div>
                    </div>
                    <span class="order-item-price">RP 40.000</span>
                </div>

                <!-- Order Item 2 -->
                <div class="order-item-card">
                    <input type="checkbox" class="order-item-checkbox" checked>
                    <div class="order-item-image"></div>
                    <div class="order-item-details">
                        <span class="order-item-name">Americano</span>
                        <div class="order-item-quantity">
                            <div class="quantity-wrapper">
                                <select class="quantity-dropdown" onchange="updateOrderTotal()">
                                    <option value="1" selected>Jumlah: 1</option>
                                    <option value="2">Jumlah: 2</option>
                                    <option value="3">Jumlah: 3</option>
                                    <option value="4">Jumlah: 4</option>
                                    <option value="5">Jumlah: 5</option>
                                </select>
                            </div>
                            <span class="order-item-delete" onclick="removeOrderItem(this)">×</span>
                        </div>
                    </div>
                    <span class="order-item-price">RP 40.000</span>
                </div>

                <!-- Order Item 3 -->
                <div class="order-item-card">
                    <input type="checkbox" class="order-item-checkbox" checked>
                    <div class="order-item-image"></div>
                    <div class="order-item-details">
                        <span class="order-item-name">Coffe Beans Arabica</span>
                        <div class="order-item-quantity">
                            <div class="quantity-wrapper">
                                <select class="quantity-dropdown" onchange="updateOrderTotal()">
                                    <option value="1" selected>Jumlah: 1</option>
                                    <option value="2">Jumlah: 2</option>
                                    <option value="3">Jumlah: 3</option>
                                    <option value="4">Jumlah: 4</option>
                                    <option value="5">Jumlah: 5</option>
                                </select>
                            </div>
                            <span class="order-item-delete" onclick="removeOrderItem(this)">×</span>
                        </div>
                    </div>
                    <span class="order-item-price">RP 40.000</span>
                </div>
            </div>

            <!-- Right: Order Summary -->
            <div class="order-summary-section">
                <p class="section-title">Jumlah Pesanan</p>
                
                <div class="order-summary-card">
                    <div class="summary-row">
                        <span class="summary-label">Subtotal (3 Produk)</span>
                        <span class="summary-value strikethrough">Rp. 1.000</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Biaya Layanan</span>
                        <span class="summary-value">Rp. 1.000</span>
                    </div>
                    <div class="summary-divider"></div>
                    <div class="summary-total-row">
                        <span class="summary-total-label">Total</span>
                        <span class="summary-total-value">RP 40.000</span>
                    </div>
                    <button class="checkout-btn" onclick="proceedToPayment()">Checkout</button>
                </div>
            </div>
        </div>

        <!-- Payment Methods Section -->
        <div class="payment-methods-section">
            <p class="section-title">Metode Pembayaran</p>

            <!-- DANA -->
            <label class="payment-method-card">
                <input type="radio" name="payment_method" value="dana" class="payment-radio">
                <span class="payment-method-name">DANA</span>
            </label>

            <!-- QRIS -->
            <label class="payment-method-card">
                <input type="radio" name="payment_method" value="qris" class="payment-radio">
                <span class="payment-method-name">Qris</span>
            </label>

            <!-- Transfer Bank -->
            <label class="payment-method-card">
                <input type="radio" name="payment_method" value="transfer_bank" class="payment-radio">
                <span class="payment-method-name">Transfer Bank</span>
            </label>

            <!-- GoPay -->
            <label class="payment-method-card">
                <input type="radio" name="payment_method" value="gopay" class="payment-radio">
                <span class="payment-method-name">GoPay</span>
            </label>

            <!-- ShopeePay -->
            <label class="payment-method-card">
                <input type="radio" name="payment_method" value="shopeepay" class="payment-radio">
                <span class="payment-method-name">ShopeePay</span>
            </label>
        </div>
    </div>

    <script>
        // Remove order item
        function removeOrderItem(btn) {
            const card = btn.closest('.order-item-card');
            card.style.opacity = '0';
            card.style.transform = 'translateX(-20px)';
            setTimeout(() => {
                card.remove();
                updateOrderTotal();
            }, 300);
        }

        // Update order total
        function updateOrderTotal() {
            // Calculate total based on selected items and quantities
            console.log('Updating order total...');
        }

        // Proceed to payment
        function proceedToPayment() {
            const selectedPayment = document.querySelector('input[name="payment_method"]:checked');
            if (!selectedPayment) {
                alert('Silakan pilih metode pembayaran');
                return;
            }
            console.log('Processing payment with:', selectedPayment.value);
            // Process payment logic here
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // Add transition styles to order items
            document.querySelectorAll('.order-item-card').forEach(card => {
                card.style.transition = 'all 0.3s ease';
            });
        });
    </script>
</x-customer.checkout-layout>
