<x-customer.cart-layout>
    <!-- Error Modal -->
    <div id="errorModal" class="error-modal-overlay">
        <div class="error-modal">
            <div class="error-modal-icon">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
            </div>
            <h3 class="error-modal-title">Tidak Ada Produk Dipilih</h3>
            <p class="error-modal-message">Pilih produk terlebih dahulu untuk melanjutkan ke checkout</p>
            <button class="error-modal-btn" onclick="closeErrorModal()">OK, Mengerti</button>
        </div>
    </div>

    <style>
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
    </div>

    <!-- Cart Summary/Footer - Fixed at bottom -->
    <div class="cart-summary-wrapper">
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
                showErrorModal();
                return;
            }
            // Redirect to checkout page
            window.location.href = '/customer/checkout';
        }

        // Show error modal
        function showErrorModal() {
            const modal = document.getElementById('errorModal');
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        // Close error modal
        function closeErrorModal() {
            const modal = document.getElementById('errorModal');
            modal.classList.remove('show');
            document.body.style.overflow = '';
        }

        // Close modal when clicking outside
        document.getElementById('errorModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeErrorModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeErrorModal();
            }
        });

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // Add transition styles to cart items
            document.querySelectorAll('.cart-item-row').forEach(row => {
                row.style.transition = 'all 0.3s ease';
            });
        });
    </script>
</x-customer.cart-layout>
