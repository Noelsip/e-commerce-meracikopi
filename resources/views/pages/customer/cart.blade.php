<x-customer.cart-layout>
    <div class="cart-page-container">
        <!-- Cart Table Header -->
        <div class="cart-table-header">
            <input type="checkbox" class="cart-checkbox" id="selectAllHeader" onchange="toggleAllCheckboxes(this)">
            <span class="header-produk">Produk</span>
            <span class="header-harga">Harga Satuan</span>
            <span class="header-kuantitas">Kuantitas</span>
            <span class="header-total">Total Harga</span>
            <span class="header-aksi">Aksi</span>
        </div>

        <!-- Cart Items -->
        <div id="cartItems">
            <!-- Cart Item 1 -->
            <div class="cart-item-row" data-item-id="1">
                <input type="checkbox" class="cart-checkbox item-checkbox" onchange="updateTotals()">
                <div class="product-info">
                    <div class="product-image-placeholder"></div>
                    <span class="product-name">Coffe Beans Arabica</span>
                </div>
                <span class="product-price">Rp. 150.000</span>
                <div class="quantity-controls">
                    <button class="quantity-btn quantity-btn-minus" onclick="updateQuantity(this, -1)">−</button>
                    <span class="quantity-value">2</span>
                    <button class="quantity-btn quantity-btn-plus" onclick="updateQuantity(this, 1)">+</button>
                </div>
                <span class="total-price">Rp. 300.000</span>
                <button class="delete-btn" onclick="removeItem(this)">Hapus</button>
            </div>

            <!-- Cart Item 2 -->
            <div class="cart-item-row" data-item-id="2">
                <input type="checkbox" class="cart-checkbox item-checkbox" onchange="updateTotals()">
                <div class="product-info">
                    <div class="product-image-placeholder"></div>
                    <span class="product-name">Coffe Beans Arabica</span>
                </div>
                <span class="product-price">Rp. 150.000</span>
                <div class="quantity-controls">
                    <button class="quantity-btn quantity-btn-minus" onclick="updateQuantity(this, -1)">−</button>
                    <span class="quantity-value">2</span>
                    <button class="quantity-btn quantity-btn-plus" onclick="updateQuantity(this, 1)">+</button>
                </div>
                <span class="total-price">Rp. 300.000</span>
                <button class="delete-btn" onclick="removeItem(this)">Hapus</button>
            </div>

            <!-- Cart Item 3 -->
            <div class="cart-item-row" data-item-id="3">
                <input type="checkbox" class="cart-checkbox item-checkbox" onchange="updateTotals()">
                <div class="product-info">
                    <div class="product-image-placeholder"></div>
                    <span class="product-name">Coffe Beans Arabica</span>
                </div>
                <span class="product-price">Rp. 150.000</span>
                <div class="quantity-controls">
                    <button class="quantity-btn quantity-btn-minus" onclick="updateQuantity(this, -1)">−</button>
                    <span class="quantity-value">2</span>
                    <button class="quantity-btn quantity-btn-plus" onclick="updateQuantity(this, 1)">+</button>
                </div>
                <span class="total-price">Rp. 300.000</span>
                <button class="delete-btn" onclick="removeItem(this)">Hapus</button>
            </div>

            <!-- Cart Item 4 -->
            <div class="cart-item-row" data-item-id="4">
                <input type="checkbox" class="cart-checkbox item-checkbox" onchange="updateTotals()">
                <div class="product-info">
                    <div class="product-image-placeholder"></div>
                    <span class="product-name">Coffe Beans Arabica</span>
                </div>
                <span class="product-price">Rp. 150.000</span>
                <div class="quantity-controls">
                    <button class="quantity-btn quantity-btn-minus" onclick="updateQuantity(this, -1)">−</button>
                    <span class="quantity-value">2</span>
                    <button class="quantity-btn quantity-btn-plus" onclick="updateQuantity(this, 1)">+</button>
                </div>
                <span class="total-price">Rp. 300.000</span>
                <button class="delete-btn" onclick="removeItem(this)">Hapus</button>
            </div>
        </div>

        <!-- Cart Summary/Footer -->
        <div class="cart-summary">
            <div class="cart-summary-left">
                <input type="checkbox" class="cart-checkbox" id="selectAllFooter" onchange="toggleAllCheckboxes(this)">
                <label for="selectAllFooter" class="select-all-label">Pilih Semua (<span id="selectedCount">4</span>)</label>
                <button class="delete-selected-btn" onclick="deleteSelected()">Hapus Produk</button>
            </div>
            <div class="cart-total-section">
                <span class="cart-total-label">Total (<span id="totalItems">4</span> Produk):</span>
                <span class="cart-total-amount">RP <span id="grandTotal">40.000</span></span>
            </div>
            <button class="checkout-btn" onclick="proceedToCheckout()">Checkout</button>
        </div>
    </div>

    <script>
        // Toggle all checkboxes
        function toggleAllCheckboxes(source) {
            const checkboxes = document.querySelectorAll('.item-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = source.checked;
            });
            
            // Sync both select all checkboxes
            document.getElementById('selectAllHeader').checked = source.checked;
            document.getElementById('selectAllFooter').checked = source.checked;
            
            updateTotals();
        }

        // Update quantity
        function updateQuantity(btn, change) {
            const row = btn.closest('.cart-item-row');
            const quantitySpan = row.querySelector('.quantity-value');
            let quantity = parseInt(quantitySpan.textContent);
            
            quantity = Math.max(1, quantity + change);
            quantitySpan.textContent = quantity;
            
            // Update row total
            const priceText = row.querySelector('.product-price').textContent;
            const price = parseInt(priceText.replace(/[^0-9]/g, ''));
            const total = price * quantity;
            row.querySelector('.total-price').textContent = 'Rp. ' + total.toLocaleString('id-ID');
            
            updateTotals();
        }

        // Remove item
        function removeItem(btn) {
            const row = btn.closest('.cart-item-row');
            row.style.opacity = '0';
            row.style.transform = 'translateX(-20px)';
            setTimeout(() => {
                row.remove();
                updateTotals();
            }, 300);
        }

        // Delete selected items
        function deleteSelected() {
            const selectedItems = document.querySelectorAll('.item-checkbox:checked');
            selectedItems.forEach(checkbox => {
                const row = checkbox.closest('.cart-item-row');
                row.style.opacity = '0';
                row.style.transform = 'translateX(-20px)';
                setTimeout(() => row.remove(), 300);
            });
            setTimeout(updateTotals, 350);
        }

        // Update totals
        function updateTotals() {
            const selectedItems = document.querySelectorAll('.item-checkbox:checked');
            const allItems = document.querySelectorAll('.item-checkbox');
            
            let grandTotal = 0;
            selectedItems.forEach(checkbox => {
                const row = checkbox.closest('.cart-item-row');
                const totalText = row.querySelector('.total-price').textContent;
                const total = parseInt(totalText.replace(/[^0-9]/g, ''));
                grandTotal += total;
            });
            
            document.getElementById('selectedCount').textContent = selectedItems.length;
            document.getElementById('totalItems').textContent = selectedItems.length;
            document.getElementById('grandTotal').textContent = grandTotal.toLocaleString('id-ID');
            
            // Update select all checkbox state
            const allChecked = allItems.length > 0 && selectedItems.length === allItems.length;
            document.getElementById('selectAllHeader').checked = allChecked;
            document.getElementById('selectAllFooter').checked = allChecked;
        }

        // Proceed to checkout
        function proceedToCheckout() {
            const selectedItems = document.querySelectorAll('.item-checkbox:checked');
            if (selectedItems.length === 0) {
                alert('Pilih produk terlebih dahulu untuk checkout');
                return;
            }
            // Redirect to checkout page
            window.location.href = '/customer/checkout';
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // Add transition styles to cart items
            document.querySelectorAll('.cart-item-row').forEach(row => {
                row.style.transition = 'all 0.3s ease';
            });
        });
    </script>
</x-customer.cart-layout>
