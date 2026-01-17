<x-customer.checkout-layout>
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
            <h3 class="error-modal-title" id="errorModalTitle">Metode Pembayaran Belum Dipilih</h3>
            <p class="error-modal-message" id="errorModalMessage">Silahkan pilih metode pembayaran terlebih dahulu</p>
            <button class="error-modal-btn" onclick="closeErrorModal()">OK, Mengerti</button>
        </div>
    </div>

    <!-- Success Modal - Pesanan Berhasil -->
    <div id="successModal" class="success-modal-overlay">
        <div class="success-modal-container">
            <!-- Header with Checkmark -->
            <div class="success-header">
                <div class="success-checkmark">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 6L9 17l-5-5"/>
                    </svg>
                </div>
                <h2 class="success-title">Pesanan Berhasil</h2>
                <p class="success-subtitle">Terima Kasih sudah order</p>
            </div>

            <!-- Order Receipt Card -->
            <div class="receipt-card">
                <!-- Cart Icon and Order Number -->
                <div class="receipt-header">
                    <div class="receipt-cart-icon">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#FFF4D6" stroke-width="1.5">
                            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <path d="M16 10a4 4 0 0 1-8 0"></path>
                        </svg>
                    </div>
                    <p class="receipt-label">Nomor Order</p>
                    <p class="receipt-order-number" id="orderNumber">MRK-20260116-001</p>
                    <p class="receipt-date" id="orderDate">16 Januari 2026 pukul 10:30</p>
                </div>

                <!-- Order Type & Payment Method -->
                <div class="receipt-info-row">
                    <div class="receipt-info-col">
                        <p class="receipt-info-label">Order Type</p>
                        <p class="receipt-info-value" id="receiptOrderType">Dine In</p>
                        <p class="receipt-info-sub" id="receiptTableInfo">Meja 10</p>
                    </div>
                    <div class="receipt-info-col">
                        <p class="receipt-info-label">Payment Method</p>
                        <p class="receipt-info-value payment-method-display" id="receiptPaymentMethod">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                <line x1="1" y1="10" x2="23" y2="10"></line>
                            </svg>
                            <span id="paymentMethodName">Credit Card</span>
                        </p>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="receipt-items-section">
                    <p class="receipt-section-title">Order Items</p>
                    <div class="receipt-items-list" id="receiptItemsList">
                        <div class="receipt-item">
                            <div class="receipt-item-info">
                                <span class="receipt-item-name">Americano</span>
                                <span class="receipt-item-variant">Iced | Qty: 1</span>
                            </div>
                            <span class="receipt-item-price">RP 20.000</span>
                        </div>
                        <div class="receipt-item">
                            <div class="receipt-item-info">
                                <span class="receipt-item-name">Americano</span>
                                <span class="receipt-item-variant">Hot | Qty: 1</span>
                            </div>
                            <span class="receipt-item-price">RP 20.000</span>
                        </div>
                    </div>
                </div>

                <!-- Total -->
                <div class="receipt-total-section">
                    <span class="receipt-total-label">Total</span>
                    <span class="receipt-total-value" id="receiptTotal">RP 40.000</span>
                </div>

                <!-- Note for Barista -->
                <div class="receipt-note-section">
                    <p class="receipt-note-label">Note For Barista</p>
                    <p class="receipt-note-text" id="receiptNote">No sugar ya mas</p>
                </div>
            </div>

            <!-- Berhasil Button -->
            <button class="berhasil-btn" onclick="closeSuccessModal()">Berhasil</button>

            <!-- Action Buttons -->
            <div class="success-action-buttons">
                <button class="btn-riwayat" onclick="goToRiwayat()">
                    Lihat Riwayat<br>Pesanan
                </button>
                <button class="btn-kembali" onclick="goBackToHome()">Kembali</button>
            </div>
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

        /* Success Modal Styles */
        .success-modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(26, 20, 16, 0.95);
            z-index: 10000;
            justify-content: center;
            align-items: flex-start;
            overflow-y: auto;
            padding: 40px 20px;
        }

        .success-modal-overlay.show {
            display: flex;
        }

        .success-modal-container {
            width: 100%;
            max-width: 400px;
            display: flex;
            flex-direction: column;
            align-items: center;
            animation: slideIn 0.4s ease;
        }

        /* Success Header */
        .success-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .success-checkmark {
            width: 60px;
            height: 60px;
            border: 2px solid #FFF4D6;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
        }

        .success-checkmark svg {
            color: #FFF4D6;
        }

        .success-title {
            color: #FFF4D6;
            font-size: 24px;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            font-style: italic;
            margin-bottom: 8px;
        }

        .success-subtitle {
            color: #FFF4D6;
            font-size: 14px;
            font-weight: 400;
            font-family: 'Poppins', sans-serif;
            font-style: italic;
            opacity: 0.8;
        }

        /* Receipt Card */
        .receipt-card {
            width: 100%;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 244, 214, 0.2);
            border-radius: 12px;
            padding: 24px 20px;
            margin-bottom: 24px;
        }

        .receipt-header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(255, 244, 214, 0.15);
            margin-bottom: 16px;
        }

        .receipt-cart-icon {
            margin-bottom: 12px;
        }

        .receipt-label {
            color: #FFF4D6;
            font-size: 11px;
            font-weight: 400;
            opacity: 0.7;
            margin-bottom: 4px;
        }

        .receipt-order-number {
            color: #FFF4D6;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .receipt-date {
            color: #FFF4D6;
            font-size: 11px;
            font-weight: 400;
            opacity: 0.7;
        }

        /* Order Info Row */
        .receipt-info-row {
            display: flex;
            justify-content: space-between;
            padding: 16px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 244, 214, 0.1);
            border-radius: 8px;
            margin-bottom: 16px;
        }

        .receipt-info-col {
            flex: 1;
        }

        .receipt-info-col:first-child {
            border-right: 1px solid rgba(255, 244, 214, 0.1);
            padding-right: 16px;
        }

        .receipt-info-col:last-child {
            padding-left: 16px;
        }

        .receipt-info-label {
            color: #FFF4D6;
            font-size: 10px;
            font-weight: 400;
            opacity: 0.6;
            margin-bottom: 6px;
        }

        .receipt-info-value {
            color: #FFF4D6;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 2px;
        }

        .receipt-info-value.payment-method-display {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .receipt-info-value.payment-method-display svg {
            color: #FFF4D6;
        }

        .receipt-info-sub {
            color: #FFF4D6;
            font-size: 11px;
            font-weight: 400;
            opacity: 0.7;
        }

        /* Order Items Section */
        .receipt-items-section {
            padding: 16px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 244, 214, 0.1);
            border-radius: 8px;
            margin-bottom: 16px;
        }

        .receipt-section-title {
            color: #FFF4D6;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .receipt-items-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .receipt-item {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .receipt-item-info {
            display: flex;
            flex-direction: column;
        }

        .receipt-item-name {
            color: #FFF4D6;
            font-size: 12px;
            font-weight: 500;
        }

        .receipt-item-variant {
            color: #FFF4D6;
            font-size: 10px;
            font-weight: 400;
            opacity: 0.6;
        }

        .receipt-item-price {
            color: #FFF4D6;
            font-size: 12px;
            font-weight: 500;
        }

        /* Total Section */
        .receipt-total-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 244, 214, 0.1);
            border-radius: 8px;
            margin-bottom: 16px;
        }

        .receipt-total-label {
            color: #FFF4D6;
            font-size: 14px;
            font-weight: 600;
        }

        .receipt-total-value {
            color: #FFF4D6;
            font-size: 16px;
            font-weight: 700;
        }

        /* Note Section */
        .receipt-note-section {
            padding: 16px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 244, 214, 0.1);
            border-radius: 8px;
        }

        .receipt-note-label {
            color: #FFF4D6;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .receipt-note-text {
            color: #FFF4D6;
            font-size: 11px;
            font-weight: 400;
            opacity: 0.7;
        }

        /* Berhasil Button */
        .berhasil-btn {
            width: 140px;
            height: 36px;
            background: #6B4526;
            color: #FFFFFF;
            border: none;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 30px;
        }

        .berhasil-btn:hover {
            background: #7d5330;
            transform: translateY(-2px);
        }

        /* Action Buttons */
        .success-action-buttons {
            display: flex;
            gap: 16px;
            width: 100%;
            max-width: 620px;
        }

        .btn-riwayat {
            width: 350px;
            height: 40px;
            background: #C89B6D;
            color: #1E1E1E;
            border: none;
            border-radius: 30px;
            font-size: 14px;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            line-height: 1.3;
        }

        .btn-riwayat:hover {
            background: #d4a97a;
            transform: translateY(-2px);
        }

        .btn-kembali {
            width: 250px;
            height: 40px;
            background: transparent;
            color: #FFF4D6;
            border: 1px solid #FFF4D6;
            border-radius: 30px;
            font-size: 14px;
            font-weight: 500;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-kembali:hover {
            background: rgba(255, 244, 214, 0.1);
            transform: translateY(-2px);
        }

        @media (max-width: 600px) {
            .success-action-buttons {
                flex-direction: column;
                align-items: center;
            }

            .btn-riwayat,
            .btn-kembali {
                width: 100%;
                max-width: 300px;
            }
        }
    </style>

    <div class="checkout-page-container">
        <!-- Order Type Tabs (Display Only - synced with navbar dropdown) -->
        <div class="order-type-tabs">
            <span class="order-type-tab-label">Tipe Pemesanan</span>
            <span class="order-type-tab-value" id="orderTypeDisplay">Dine In</span>
        </div>

        <!-- Delivery Address Section (Hidden by default, shown when Delivery is selected) -->
        <div class="delivery-address-section" id="deliveryAddressSection" style="display: none;">
            <div class="address-header">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                    <circle cx="12" cy="10" r="3"></circle>
                </svg>
                <span class="address-title">Alamat Pengiriman</span>
            </div>
            <div class="address-card">
                <div class="address-info">
                    <div class="address-name-phone">
                        <span class="recipient-name">Adwin Ahmad</span>
                        <span class="recipient-divider">|</span>
                        <span class="recipient-phone">(+62) 822 54554411</span>
                    </div>
                    <p class="address-detail">Jl. Murakata No.107, Batu Ampar, Kec. Balikpapan Utara, Kota Balikpapan, Kalimantan Timur 76614</p>
                </div>
                <button class="address-edit-btn" onclick="editAddress()">Ubah</button>
            </div>
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

        <!-- Delivery Methods Section (Hidden by default, shown when Delivery is selected) -->
        <div class="delivery-methods-section" id="deliveryMethodsSection" style="display: none;">
            <p class="section-title">Metode Pengiriman</p>

            <!-- J&T Express -->
            <label class="delivery-method-card" onclick="toggleRadio(event, 'delivery_method', 'jnt')">
                <input type="radio" name="delivery_method" value="jnt" class="delivery-radio">
                <span class="delivery-method-name">J&T Express</span>
            </label>

            <!-- JNE -->
            <label class="delivery-method-card" onclick="toggleRadio(event, 'delivery_method', 'jne')">
                <input type="radio" name="delivery_method" value="jne" class="delivery-radio">
                <span class="delivery-method-name">JNE</span>
            </label>

            <!-- Grab Express -->
            <label class="delivery-method-card" onclick="toggleRadio(event, 'delivery_method', 'grab_express')">
                <input type="radio" name="delivery_method" value="grab_express" class="delivery-radio">
                <span class="delivery-method-name">Grab Express</span>
            </label>

            <!-- Go Send -->
            <label class="delivery-method-card" onclick="toggleRadio(event, 'delivery_method', 'gosend')">
                <input type="radio" name="delivery_method" value="gosend" class="delivery-radio">
                <span class="delivery-method-name">Go Send</span>
            </label>

            <!-- SiCepat Express -->
            <label class="delivery-method-card" onclick="toggleRadio(event, 'delivery_method', 'sicepat')">
                <input type="radio" name="delivery_method" value="sicepat" class="delivery-radio">
                <span class="delivery-method-name">SiCepat Express</span>
            </label>
        </div>

        <!-- Payment Methods Section (Shown for Dine In and Takeaway) -->
        <div class="payment-methods-section" id="paymentMethodsSection">
            <p class="section-title">Metode Pembayaran</p>

            <!-- DANA -->
            <label class="payment-method-card" onclick="toggleRadio(event, 'payment_method', 'dana')">
                <input type="radio" name="payment_method" value="dana" class="payment-radio">
                <span class="payment-method-name">DANA</span>
            </label>

            <!-- QRIS -->
            <label class="payment-method-card" onclick="toggleRadio(event, 'payment_method', 'qris')">
                <input type="radio" name="payment_method" value="qris" class="payment-radio">
                <span class="payment-method-name">Qris</span>
            </label>

            <!-- Transfer Bank -->
            <label class="payment-method-card" onclick="toggleRadio(event, 'payment_method', 'transfer_bank')">
                <input type="radio" name="payment_method" value="transfer_bank" class="payment-radio">
                <span class="payment-method-name">Transfer Bank</span>
            </label>

            <!-- GoPay -->
            <label class="payment-method-card" onclick="toggleRadio(event, 'payment_method', 'gopay')">
                <input type="radio" name="payment_method" value="gopay" class="payment-radio">
                <span class="payment-method-name">GoPay</span>
            </label>

            <!-- ShopeePay -->
            <label class="payment-method-card" onclick="toggleRadio(event, 'payment_method', 'shopeepay')">
                <input type="radio" name="payment_method" value="shopeepay" class="payment-radio">
                <span class="payment-method-name">ShopeePay</span>
            </label>
        </div>
    </div>

    <!-- Address Modal -->
    <div class="address-modal" id="addressModal" style="display: none;">
        <div class="address-modal-content">
            <div class="address-modal-header">
                <h3>Ubah Alamat Pengiriman</h3>
                <button class="modal-close-btn" onclick="closeAddressModal()">×</button>
            </div>
            <div class="address-modal-body">
                <div class="form-group">
                    <label>Nama Penerima</label>
                    <input type="text" class="form-input" id="recipientName" value="Adwin Ahmad">
                </div>
                <div class="form-group">
                    <label>Nomor Telepon</label>
                    <input type="text" class="form-input" id="recipientPhone" value="(+62) 822 54554411">
                </div>
                <div class="form-group">
                    <label>Alamat Lengkap</label>
                    <textarea class="form-textarea" id="fullAddress">Jl. Murakata No.107, Batu Ampar, Kec. Balikpapan Utara, Kota Balikpapan, Kalimantan Timur 76614</textarea>
                </div>
                <div class="form-actions">
                    <button class="btn-cancel" onclick="closeAddressModal()">Batal</button>
                    <button class="btn-save" onclick="saveAddress()">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Track last selected values for toggle functionality
        let lastSelectedPayment = null;
        let lastSelectedDelivery = null;

        // Toggle radio button (allow uncheck when clicking the same option)
        function toggleRadio(event, name, value) {
            event.preventDefault();
            const radio = document.querySelector(`input[name="${name}"][value="${value}"]`);
            
            if (name === 'payment_method') {
                if (lastSelectedPayment === value) {
                    // Uncheck if clicking the same option
                    radio.checked = false;
                    lastSelectedPayment = null;
                } else {
                    // Select new option
                    radio.checked = true;
                    lastSelectedPayment = value;
                    scrollToPayment();
                }
            } else if (name === 'delivery_method') {
                if (lastSelectedDelivery === value) {
                    // Uncheck if clicking the same option
                    radio.checked = false;
                    lastSelectedDelivery = null;
                } else {
                    // Select new option
                    radio.checked = true;
                    lastSelectedDelivery = value;
                    scrollToPayment();
                }
            }
        }

        // Scroll to top of page
        function scrollToPayment() {
            // Small delay to let the selection complete visually
            setTimeout(() => {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }, 150);
        }

        // Show error modal
        function showErrorModal(title, message) {
            document.getElementById('errorModalTitle').textContent = title;
            document.getElementById('errorModalMessage').textContent = message;
            document.getElementById('errorModal').classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        // Close error modal
        function closeErrorModal() {
            document.getElementById('errorModal').classList.remove('show');
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
            const orderType = document.getElementById('orderTypeDisplay').textContent;
            
            if (orderType === 'Delivery') {
                const selectedDelivery = document.querySelector('input[name="delivery_method"]:checked');
                if (!selectedDelivery) {
                    showErrorModal('Metode Pengiriman Belum Dipilih', 'Silahkan pilih metode pengiriman terlebih dahulu');
                    return;
                }
                // Show success modal with delivery info
                showSuccessModal(orderType, selectedDelivery.value);
            } else {
                const selectedPayment = document.querySelector('input[name="payment_method"]:checked');
                if (!selectedPayment) {
                    showErrorModal('Metode Pembayaran Belum Dipilih', 'Silahkan pilih metode pembayaran terlebih dahulu');
                    return;
                }
                // Show success modal with payment info
                showSuccessModal(orderType, selectedPayment.value);
            }
        }

        // Show success modal
        function showSuccessModal(orderType, paymentMethod) {
            // Generate order number
            const now = new Date();
            const orderNumber = 'MRK-' + now.getFullYear() + 
                String(now.getMonth() + 1).padStart(2, '0') + 
                String(now.getDate()).padStart(2, '0') + '-' + 
                String(Math.floor(Math.random() * 999) + 1).padStart(3, '0');
            
            // Format date
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                           'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            const orderDate = now.getDate() + ' ' + months[now.getMonth()] + ' ' + now.getFullYear() + 
                             ' pukul ' + String(now.getHours()).padStart(2, '0') + ':' + 
                             String(now.getMinutes()).padStart(2, '0');

            // Payment method display names
            const paymentNames = {
                'dana': 'DANA',
                'qris': 'QRIS',
                'transfer_bank': 'Transfer Bank',
                'gopay': 'GoPay',
                'shopeepay': 'ShopeePay',
                'jnt': 'J&T Express',
                'jne': 'JNE',
                'grab_express': 'Grab Express',
                'gosend': 'Go Send',
                'sicepat': 'SiCepat Express'
            };

            // Update modal content
            document.getElementById('orderNumber').textContent = orderNumber;
            document.getElementById('orderDate').textContent = orderDate;
            document.getElementById('receiptOrderType').textContent = orderType;
            document.getElementById('paymentMethodName').textContent = paymentNames[paymentMethod] || paymentMethod;

            // Update table info based on order type
            const tableInfo = document.getElementById('receiptTableInfo');
            if (orderType === 'Dine In') {
                tableInfo.textContent = 'Meja 10';
                tableInfo.style.display = 'block';
            } else if (orderType === 'Delivery') {
                tableInfo.textContent = '';
                tableInfo.style.display = 'none';
            } else {
                tableInfo.textContent = '';
                tableInfo.style.display = 'none';
            }

            // Show modal
            document.getElementById('successModal').classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        // Close success modal
        function closeSuccessModal() {
            document.getElementById('successModal').classList.remove('show');
            document.body.style.overflow = '';
        }

        // Go to order history
        function goToRiwayat() {
            window.location.href = '/customer/orders';
        }

        // Go back to home/catalogs
        function goBackToHome() {
            window.location.href = '/customer/catalogs';
        }

        // Edit address
        function editAddress() {
            document.getElementById('addressModal').style.display = 'flex';
        }

        // Close address modal
        function closeAddressModal() {
            document.getElementById('addressModal').style.display = 'none';
        }

        // Save address
        function saveAddress() {
            const name = document.getElementById('recipientName').value;
            const phone = document.getElementById('recipientPhone').value;
            const address = document.getElementById('fullAddress').value;

            // Update display
            document.querySelector('.recipient-name').textContent = name;
            document.querySelector('.recipient-phone').textContent = phone;
            document.querySelector('.address-detail').textContent = address;

            closeAddressModal();
        }

        // Toggle sections based on order type (called from navbar)
        window.toggleDeliverySection = function(isDelivery) {
            const deliveryAddressSection = document.getElementById('deliveryAddressSection');
            const deliveryMethodsSection = document.getElementById('deliveryMethodsSection');
            const paymentMethodsSection = document.getElementById('paymentMethodsSection');

            if (isDelivery) {
                deliveryAddressSection.style.display = 'block';
                deliveryMethodsSection.style.display = 'block';
                paymentMethodsSection.style.display = 'none';
            } else {
                deliveryAddressSection.style.display = 'none';
                deliveryMethodsSection.style.display = 'none';
                paymentMethodsSection.style.display = 'block';
            }
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
