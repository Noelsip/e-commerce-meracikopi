<x-customer.checkout-layout>
    <!-- Error Modal -->
    <div id="errorModal" class="error-modal-overlay">
        <div class="error-modal compact-modal">
            <div class="error-modal-icon">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10" />
                    <line x1="12" y1="8" x2="12" y2="12" />
                    <line x1="12" y1="16" x2="12.01" y2="16" />
                </svg>
            </div>
            <h3 class="error-modal-title" id="errorModalTitle">Metode Pembayaran Belum Dipilih</h3>
            <p class="error-modal-message" id="errorModalMessage">Silahkan pilih metode pembayaran terlebih dahulu</p>
            <button class="error-modal-btn" onclick="closeErrorModal()">OK, Mengerti</button>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteConfirmModal" class="error-modal-overlay">
        <div class="error-modal compact-modal">
            <div class="error-modal-icon">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 6h18"></path>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    <line x1="10" y1="11" x2="10" y2="17"></line>
                    <line x1="14" y1="11" x2="14" y2="17"></line>
                </svg>
            </div>
            <h3 class="error-modal-title">Hapus Produk?</h3>
            <p class="error-modal-message">Apakah Anda yakin ingin menghapus produk ini dari pesanan?</p>
            <div class="delete-modal-actions">
                <button class="btn-cancel" onclick="closeDeleteConfirm()">Batal</button>
                <button class="btn-confirm-delete" onclick="confirmDelete()">Iya</button>
            </div>
        </div>
    </div>

    <!-- Success Modal - Pesanan Berhasil -->
    <div id="successModal" class="success-modal-overlay">
        <div class="success-modal-container">
            <!-- Header with Checkmark -->
            <div class="success-header">
                <div class="success-checkmark">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 6L9 17l-5-5" />
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
                        <p class="receipt-info-value" id="receiptOrderType">Takeaway</p>
                        <p class="receipt-info-sub" id="receiptTableInfo">Meja 10</p>
                    </div>
                    <div class="receipt-info-col">
                        <p class="receipt-info-label">Payment Method</p>
                        <p class="receipt-info-value payment-method-display" id="receiptPaymentMethod">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                <line x1="1" y1="10" x2="23" y2="10"></line>
                            </svg>
                            <span id="paymentMethodName">QRIS</span>
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
            <button class="berhasil-btn" onclick="window.location.href = '/customer/catalogs'">Berhasil</button>

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

        /* Compact Modal Variant */
        .compact-modal {
            max-width: 320px;
            padding: 30px 24px;
        }

        .compact-modal .error-modal-icon {
            width: 56px;
            height: 56px;
            margin-bottom: 16px;
        }

        .compact-modal .error-modal-icon svg {
            width: 24px;
            height: 24px;
        }

        .compact-modal .error-modal-title {
            font-size: 16px;
            margin-bottom: 8px;
        }

        .compact-modal .error-modal-message {
            font-size: 12px;
            margin-bottom: 20px;
            opacity: 0.6;
            line-height: 1.5;
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

        /* Delete Modal Extensions */
        .delete-modal-actions {
            display: flex;
            gap: 16px;
            width: 100%;
            justify-content: center;
        }

        .btn-cancel {
            background: transparent;
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 10px 30px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Poppins', sans-serif;
        }

        .btn-cancel:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .btn-confirm-delete {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 10px 30px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Poppins', sans-serif;
            box-shadow: 0 4px 15px rgba(231, 76, 60, 0.3);
        }

        .btn-confirm-delete:hover {
            background: #c0392b;
            transform: translateY(-2px);
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
            display: flex;
            justify-content: center;
            align-items: center;
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

        /* Spinner animation */
        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>

    <div class="checkout-page-container">
        <!-- Order Type Tabs (Display Only - synced with navbar dropdown) -->
        <div class="order-type-tabs">
            <span class="order-type-tab-label">Tipe Pemesanan</span>
            <span class="order-type-tab-value" id="orderTypeDisplay">Takeaway</span>
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
                        <span class="recipient-name" id="deliveryRecipientName">-</span>
                        <span class="recipient-divider">|</span>
                        <span class="recipient-phone" id="deliveryRecipientPhone">-</span>
                    </div>
                    <p class="address-detail" id="deliveryAddressDetail">Belum ada alamat pengiriman. Klik "Ubah" untuk mengisi.</p>
                </div>
                <button class="address-edit-btn" onclick="editAddress()">Ubah</button>
            </div>
        </div>

        <!-- Customer Info Section (Shown for Dine In and Takeaway) -->
        <div class="customer-info-section" id="customerInfoSection" style="margin-top: 24px; margin-bottom: 20px;">
            <div class="address-header" style="margin-bottom: 16px; display: flex; align-items: center; gap: 10px;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#CA7842" stroke-width="2">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
                <span class="address-title" style="color: #FFF4D6; font-size: 16px; font-weight: 600;">Informasi
                    Pemesan</span>
            </div>
            <div class="customer-form-card"
                style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 244, 214, 0.1); border-radius: 12px; padding: 20px;">
                <style>
                    .customer-info-row {
                        display: flex;
                        gap: 20px;
                    }

                    @media (max-width: 600px) {
                        .customer-info-row {
                            flex-direction: column;
                            gap: 16px;
                        }
                    }
                </style>
                <div class="customer-info-row">
                    <div class="form-group" style="flex: 1; margin-bottom: 0;">
                        <label
                            style="display: block; color: rgba(255, 244, 214, 0.7); font-size: 12px; margin-bottom: 8px;">Nama
                            Pemesan <span style="color: #e74c3c;">*</span></label>
                        <input type="text" id="dineInName" class="form-input" placeholder="Masukkan nama Anda"
                            style="width: 100%; background: rgba(0, 0, 0, 0.2); border: 1px solid rgba(255, 244, 214, 0.15); color: #FFF4D6; padding: 12px 14px; border-radius: 8px; outline: none; transition: all 0.3s ease;">
                    </div>
                    <div class="form-group" style="flex: 1; margin-bottom: 0;">
                        <label
                            style="display: block; color: rgba(255, 244, 214, 0.7); font-size: 12px; margin-bottom: 8px;">Nomor
                            Telepon <span style="opacity: 0.5;">(Opsional)</span></label>
                        <input type="tel" id="dineInPhone" class="form-input" placeholder="08xxxxxxxxxx"
                            inputmode="numeric" pattern="[0-9]*"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                            style="width: 100%; background: rgba(0, 0, 0, 0.2); border: 1px solid rgba(255, 244, 214, 0.15); color: #FFF4D6; padding: 12px 14px; border-radius: 8px; outline: none; transition: all 0.3s ease;">
                    </div>
                </div>
                <div class="form-group" style="margin-top: 16px; margin-bottom: 0;">
                    <label
                        style="display: block; color: rgba(255, 244, 214, 0.7); font-size: 12px; margin-bottom: 8px;">Catatan
                        untuk Barista <span style="opacity: 0.5;">(Opsional)</span></label>
                    <textarea id="orderNotes" class="form-input"
                        placeholder="Contoh: Gula sedikit, tanpa es, extra shot, dll." rows="3"
                        style="width: 100%; background: rgba(0, 0, 0, 0.2); border: 1px solid rgba(255, 244, 214, 0.15); color: #FFF4D6; padding: 12px 14px; border-radius: 8px; outline: none; transition: all 0.3s ease; resize: vertical; min-height: 80px;"></textarea>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="checkout-content">
            <!-- Left: Order Items + Payment/Delivery Options -->
            <div class="order-items-section-wrapper">
                <div class="order-items-section">
                    <p class="section-title">Pesanan</p>

                    <!-- Dynamic order items will be loaded here by JavaScript -->
                    <div id="checkoutItemsContainer">
                        <!-- Items will load instantly from cache -->
                    </div>
                </div>

                <!-- Payment Methods Section (Shown for Dine In and Takeaway) -->
                <div class="payment-methods-section" id="paymentMethodsSection" style="margin-top: 40px;">
                    <p class="section-title">Metode Pembayaran</p>

                    <!-- QRIS -->
                    <label class="payment-method-card" onclick="toggleRadio(event, 'payment_method', 'qris')"
                        style="display: flex; align-items: center; justify-content: space-between;">
                        <div style="display: flex; align-items: center;">
                            <input type="radio" name="payment_method" value="qris" class="payment-radio" checked>
                            <span class="payment-method-name" style="color: #FFFFFF;">QRIS</span>
                        </div>
                        <img src="https://upload.wikimedia.org/wikipedia/commons/a/a2/Logo_QRIS.svg" alt="QRIS"
                            class="payment-logo" style="height: 22px; width: auto; filter: brightness(0) invert(1);">
                    </label>
                </div>
            </div>

            <!-- Right: Order Summary -->
            <div class="order-summary-section">
                <p class="section-title">Jumlah Pesanan</p>

                <div class="order-summary-card">
                    <div class="summary-row">
                        <span class="summary-label" id="summarySubtotalLabel">Subtotal (0 Produk)</span>
                        <span class="summary-value" id="summarySubtotalValue">Rp 0</span>
                    </div>
                    <div class="summary-divider"></div>
                    <div class="summary-total-row">
                        <span class="summary-total-label">Total</span>
                        <span class="summary-total-value">RP 0</span>
                    </div>
                    <button class="checkout-btn" onclick="proceedToPayment()">Checkout</button>
                </div>
            </div>
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
                    <label>Nama Penerima <span style="color: #e74c3c;">*</span></label>
                    <input type="text" class="form-input" id="recipientName" value=""
                        placeholder="Masukkan nama penerima">
                </div>
                <div class="form-group">
                    <label>Nomor Telepon <span style="color: #e74c3c;">*</span></label>
                    <input type="tel" class="form-input" id="recipientPhone" value="" placeholder="08xxxxxxxxxx"
                        inputmode="numeric" pattern="[0-9+() -]*"
                        oninput="this.value = this.value.replace(/[^0-9+() -]/g, '');">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Provinsi <span style="color: #e74c3c;">*</span></label>
                        <select class="form-input form-select" id="province">
                            <option value="">Pilih Provinsi</option>
                            <option value="kaltim">Kalimantan Timur</option>
                            <option value="kalteng">Kalimantan Tengah</option>
                            <option value="kalsel">Kalimantan Selatan</option>
                            <option value="kalbar">Kalimantan Barat</option>
                            <option value="kaltara">Kalimantan Utara</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Kota/Kabupaten <span style="color: #e74c3c;">*</span></label>
                        <select class="form-input form-select" id="city">
                            <option value="">Pilih Kota</option>
                            <option value="balikpapan">Kota Balikpapan</option>
                            <option value="samarinda">Kota Samarinda</option>
                            <option value="bontang">Kota Bontang</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Kecamatan <span style="color: #e74c3c;">*</span></label>
                        <select class="form-input form-select" id="district">
                            <option value="">Pilih Kecamatan</option>
                            <option value="balikpapan_utara">Balikpapan Utara</option>
                            <option value="balikpapan_selatan">Balikpapan Selatan</option>
                            <option value="balikpapan_timur">Balikpapan Timur</option>
                            <option value="balikpapan_barat">Balikpapan Barat</option>
                            <option value="balikpapan_tengah">Balikpapan Tengah</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Kode Pos <span style="color: #e74c3c;">*</span></label>
                        <input type="text" class="form-input" id="postalCode" value="" placeholder="Masukkan kode pos"
                            maxlength="5">
                    </div>
                </div>
                <div class="form-group">
                    <label>Alamat Lengkap <span style="color: #e74c3c;">*</span></label>
                    <textarea class="form-textarea" id="fullAddress" placeholder="Masukkan alamat lengkap"></textarea>
                </div>
                <div class="form-group">
                    <label>Detail Lainnya <span class="optional-label">(Opsional)</span></label>
                    <input type="text" class="form-input" id="addressDetail" placeholder="Patokan, warna rumah, dll">
                </div>
                <div class="form-group">
                    <label>Label Alamat</label>
                    <div class="address-label-options">
                        <button type="button" class="label-btn active" onclick="selectAddressLabel(this, 'rumah')">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                            </svg>
                            Rumah
                        </button>
                        <button type="button" class="label-btn" onclick="selectAddressLabel(this, 'kantor')">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                            </svg>
                            Kantor
                        </button>
                    </div>
                    <input type="hidden" id="addressLabel" value="rumah">
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
                }
            }
        }



        // Show error modal
        function showErrorModal(title, message) {
            document.getElementById('errorModalTitle').textContent = title;
            document.getElementById('errorModalMessage').textContent = message;
            document.getElementById('errorModal').classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        // Show payment method error (for when DOKU fails but cart is kept)
        function showPaymentMethodError(message, orderId) {
            const modal = document.getElementById('errorModal');
            const title = document.getElementById('errorModalTitle');
            const msgEl = document.getElementById('errorModalMessage');

            title.textContent = 'Metode Pembayaran Tidak Tersedia';
            msgEl.innerHTML = `
                <p style="margin-bottom: 15px;">${message}</p>
                <p style="font-size: 14px; color: #6b7280;">
                    Silahkan pilih metode pembayaran lain di bawah, lalu klik Checkout kembali.
                </p>
            `;

            modal.classList.add('show');
            document.body.style.overflow = 'hidden';

            // Scroll to payment methods section after modal is closed
            const closeBtn = modal.querySelector('.close-btn') || modal.querySelector('button');
            if (closeBtn) {
                closeBtn.onclick = function () {
                    closeErrorModal();
                    // Scroll to payment methods
                    const paymentSection = document.querySelector('.payment-method');
                    if (paymentSection) {
                        paymentSection.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                };
            }
        }

        // Close error modal
        function closeErrorModal() {
            document.getElementById('errorModal').classList.remove('show');
            document.body.style.overflow = '';
        }

        // Close modal when clicking outside
        document.getElementById('errorModal').addEventListener('click', function (e) {
            if (e.target === this) {
                closeErrorModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function (e) {
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
        async function proceedToPayment() {
            const orderType = document.getElementById('orderTypeDisplay').textContent.toLowerCase().replace(' ', '_'); // dine_in, take_away
            const token = localStorage.getItem('guest_token');

            // 1. Get Selected Item IDs
            const selectedCheckbox = document.querySelectorAll('.order-item-checkbox:checked');
            if (selectedCheckbox.length === 0) {
                showErrorModal('Pesanan Belum Dipilih', 'Silahkan pilih minimal satu pesanan untuk checkout');
                return;
            }
            const selectedItemIds = Array.from(selectedCheckbox)
                .map(cb => {
                    const itemId = cb.dataset.itemId || cb.getAttribute('data-item-id');
                    return itemId ? parseInt(itemId, 10) : null;
                })
                .filter(id => id !== null && !isNaN(id));

            console.log('Selected item IDs:', selectedItemIds);

            // 2. Validate Payment Method (required for all order types)
            const selectedPayment = document.querySelector('input[name="payment_method"]:checked');
            if (!selectedPayment) {
                showErrorModal('Metode Pembayaran Belum Dipilih', 'Silahkan pilih metode pembayaran terlebih dahulu');
                return;
            }
            let paymentMethod = selectedPayment.value;

            // Map order type text to enum value expected by backend
            const orderTypeMap = {
                'dine_in': 'dine_in',
                'take_away': 'take_away',
                'delivery': 'delivery'
            };
            const backendOrderType = orderTypeMap[orderType] || 'take_away';

            if (orderType === 'delivery') {
                if (!recipientPhone) {
                    showErrorModal('Alamat Belum Lengkap', 'Silahkan isi nomor telepon penerima terlebih dahulu');
                    editAddress();
                    return;
                }
                if (!province || !city || !district) {
                    showErrorModal('Alamat Belum Lengkap', 'Silahkan lengkapi provinsi, kota, dan kecamatan');
                    editAddress();
                    return;
                }
                if (!postalCode) {
                    showErrorModal('Alamat Belum Lengkap', 'Silahkan isi kode pos terlebih dahulu');
                    editAddress();
                    return;
                }
                if (!fullAddress) {
                    showErrorModal('Alamat Belum Lengkap', 'Silahkan isi alamat lengkap terlebih dahulu');
                    editAddress();
                    return;
                }
            }

            // 3. Prepare Payload
            let customerName, customerPhone;

            if (orderType === 'delivery') {
                // For delivery, use the address modal inputs (or display values)
                customerName = document.getElementById('recipientName').value || 'Guest';
                customerPhone = document.getElementById('recipientPhone').value || '-';
            } else {
                // For dine in / takeaway, use the new inputs
                customerName = document.getElementById('dineInName').value;
                customerPhone = document.getElementById('dineInPhone').value;

                if (!customerName || customerName.trim() === '') {
                    showErrorModal('Nama Pemesan Kosong', 'Silahkan isi nama pemesan terlebih dahulu');
                    // Focus on the input
                    document.getElementById('dineInName').focus();

                    // Reset button state
                    const checkoutBtn = document.querySelector('.checkout-btn');
                    checkoutBtn.innerText = 'Checkout';
                    checkoutBtn.disabled = false;
                    return;
                }
            }

            const tableId = localStorage.getItem('table_id') || 1; // Fallback to 1 for testing if not set

            const payload = {
                order_type: backendOrderType,
                customer_name: customerName,
                customer_phone: customerPhone || null,
                notes: document.getElementById('orderNotes')?.value || '',
                selected_item_ids: selectedItemIds,
                // Delivery specific fields (only for delivery order type)
                ...(orderType === 'delivery' ? {
                    address: {
                        receiver_name: customerName,
                        phone: customerPhone,
                        full_address: document.getElementById('fullAddress').value,
                        city: document.getElementById('city').value,
                        postal_code: document.getElementById('postalCode').value,
                        notes: document.getElementById('addressDetail').value || ''
                    },
                    shipping_quote_id: localStorage.getItem('shipping_quote_id') || '',
                    shipping_option_id: localStorage.getItem('shipping_option_id') || ''
                } : {}),
                // Table ID only for dine_in
                ...(orderType === 'dine_in' && tableId ? { table_id: tableId } : {})
            };

            // 4. Submit Order
            const checkoutBtn = document.querySelector('.checkout-btn');
            const originalText = checkoutBtn.innerText;
            checkoutBtn.innerText = 'Memproses...';
            checkoutBtn.disabled = true;

            console.log('Sending order payload:', payload);

            try {
                const response = await fetch('/api/customer/orders', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-GUEST-TOKEN': token,
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    },
                    body: JSON.stringify(payload)
                });

                const data = await response.json();

                if (!response.ok) {
                    // Handle validation errors
                    if (data.errors) {
                        const firstError = Object.values(data.errors)[0];
                        throw new Error(Array.isArray(firstError) ? firstError[0] : firstError);
                    }
                    throw new Error(data.message || 'Gagal memproses pesanan');
                }

                console.log('Order created successfully:', data);
                const orderId = data.data.id;

                // Step 2: Call payment API to get DOKU payment data
                checkoutBtn.innerText = 'Memproses Pembayaran...';

                const paymentResponse = await fetch(`/api/customer/orders/${orderId}/pay`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-GUEST-TOKEN': token,
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    },
                    body: JSON.stringify({
                        payment_method: paymentMethod // Pass selected payment method
                    })
                });

                const paymentData = await paymentResponse.json();

                if (!paymentResponse.ok) {
                    // Check if payment gateway error with can_retry flag
                    if (paymentData.error === 'payment_gateway_error' || paymentData.error === 'invalid_payment_response') {
                        // Show error but keep cart and allow retry with different method
                        showPaymentMethodError(
                            paymentData.error_detail || paymentData.message,
                            paymentData.order_id
                        );
                        checkoutBtn.innerText = originalText;
                        checkoutBtn.disabled = false;
                        return; // Exit without throwing - user can retry
                    }
                    throw new Error(paymentData.message || 'Gagal memproses pembayaran');
                }

                console.log('Payment initiated:', paymentData);

                // Step 3: Handle DOKU payment
                checkoutBtn.innerText = originalText;
                checkoutBtn.disabled = false;

                // DO NOT clear cart here - wait until payment is successful
                // Cart will be cleared by backend when payment succeeds
                localStorage.removeItem('selected_cart_items');

                // Check if DOKU payment handler is available
                if (typeof window.dokuPayment === 'undefined') {
                    throw new Error('Payment gateway belum siap. Silahkan refresh halaman dan coba lagi.');
                }

                // Setup payment success/error callbacks for DOKU
                window.onPaymentSuccess = function (result) {
                    console.log('DOKU Payment success:', result);
                    showSuccessModal(data.data, paymentMethod);
                };

                window.onPaymentError = function (result) {
                    console.error('DOKU Payment error:', result);
                    showErrorModal('Pembayaran Gagal', 'Terjadi kesalahan saat memproses pembayaran. Silahkan coba lagi.');
                };

                window.onPaymentPending = function (result) {
                    console.log('DOKU Payment pending:', result);
                    showErrorModal('Pembayaran Pending', 'Silahkan selesaikan pembayaran Anda. Status akan diupdate otomatis.');
                    // Redirect to order history after 3 seconds
                    setTimeout(() => {
                        window.location.href = '/customer/order-history';
                    }, 3000);
                };

                // Handle DOKU payment using the existing handler
                try {
                    window.dokuPayment.handlePayment(paymentData.data);
                } catch (paymentHandlerError) {
                    console.error('DOKU Payment handler error:', paymentHandlerError);
                    showErrorModal('Gagal Memuat Payment',
                        'Gateway pembayaran tidak dapat dimuat. Pesanan Anda tetap tersimpan. ' +
                        'Silahkan coba lagi atau lihat di riwayat pesanan untuk melanjutkan pembayaran.');

                    // Optional: Redirect to order history
                    setTimeout(() => {
                        window.location.href = '/customer/order-history';
                    }, 3000);
                }

            } catch (error) {
                console.error('Checkout error:', error);
                showErrorModal('Gagal Checkout', error.message || 'Terjadi kesalahan. Silahkan coba lagi.');
                checkoutBtn.innerText = originalText;
                checkoutBtn.disabled = false;
            }
        }

        // Show success modal with real order data
        function showSuccessModal(orderData, paymentMethod) {
            console.log('Order data for receipt:', orderData);

            // Use real order number from API response
            const orderNumber = orderData.order_number || orderData.id;

            // Format date from order created_at or use current time
            const now = orderData.created_at ? new Date(orderData.created_at) : new Date();
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            const orderDate = now.getDate() + ' ' + months[now.getMonth()] + ' ' + now.getFullYear() +
                ' pukul ' + String(now.getHours()).padStart(2, '0') + ':' +
                String(now.getMinutes()).padStart(2, '0');

            // Payment method display names
            const paymentNames = {
                'qris': 'QRIS',
            };

            // Format order type
            const orderType = orderData.order_type ?
                orderData.order_type.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase()) :
                'Takeaway';

            // Update modal content with real data
            document.getElementById('orderNumber').textContent = orderNumber;
            document.getElementById('orderDate').textContent = orderDate;
            document.getElementById('receiptOrderType').textContent = orderType;
            document.getElementById('paymentMethodName').textContent = paymentNames[paymentMethod] || paymentMethod;

            // Update table info based on order type
            const tableInfo = document.getElementById('receiptTableInfo');
            if (orderData.order_type === 'dine_in' && orderData.table) {
                tableInfo.textContent = 'Meja ' + (orderData.table.table_number || orderData.table_id);
                tableInfo.style.display = 'block';
            } else if (orderData.order_type === 'dine_in' && orderData.table_id) {
                tableInfo.textContent = 'Meja ' + orderData.table_id;
                tableInfo.style.display = 'block';
            } else {
                tableInfo.textContent = '';
                tableInfo.style.display = 'none';
            }

            // Update order items list with real data
            const itemsList = document.getElementById('receiptItemsList');
            itemsList.innerHTML = ''; // Clear existing items

            // Try to get items from order data, otherwise get from page cart
            let items = orderData.items || orderData.order_items || [];
            let totalAmount = orderData.total_amount || orderData.total || 0;
            let calculatedTotal = 0;

            // Fallback: get items from page if API doesn't return them
            if (items.length === 0) {
                const cartItems = document.querySelectorAll('.order-item-card');
                cartItems.forEach(cartItem => {
                    const checkbox = cartItem.querySelector('.order-item-checkbox');
                    // Only include checked items
                    if (checkbox && checkbox.checked) {
                        const nameEl = cartItem.querySelector('.order-item-name');
                        const subtotalEl = cartItem.querySelector('.order-item-subtotal');
                        const qtyEl = cartItem.querySelector('.checkout-qty-value');

                        if (nameEl) {
                            const subtotal = subtotalEl ? parseInt(subtotalEl.textContent.replace(/[^0-9]/g, '')) || 0 : 0;
                            items.push({
                                menu_name: nameEl.textContent.trim(),
                                variant: '',
                                quantity: qtyEl ? parseInt(qtyEl.textContent) || 1 : 1,
                                subtotal: subtotal
                            });
                            calculatedTotal += subtotal;
                        }
                    }
                });
            }

            // Always get total from page as backup if totalAmount is 0
            if (totalAmount === 0) {
                const totalEl = document.querySelector('.summary-row.total .summary-value');
                if (totalEl) {
                    totalAmount = parseInt(totalEl.textContent.replace(/[^0-9]/g, '')) || 0;
                }
                // If still 0, use calculated total from items
                if (totalAmount === 0 && calculatedTotal > 0) {
                    totalAmount = calculatedTotal;
                }
                // Last resort - calculate from items array
                if (totalAmount === 0 && items.length > 0) {
                    totalAmount = items.reduce((sum, item) => sum + (item.subtotal || item.sub_total || 0), 0);
                }
            }

            if (items.length > 0) {
                items.forEach(item => {
                    const note = item.note || '';
                    let variantLabel = '';
                    if (note.startsWith('[Hot]')) variantLabel = 'Hot';
                    else if (note.startsWith('[Ice]')) variantLabel = 'Ice';
                    else variantLabel = item.variant || item.options || note;
                    const itemName = item.menu_name || item.name || item.menu?.name || 'Item';
                    const qty = item.quantity || 1;
                    const subtotal = item.subtotal || item.sub_total || (item.price * qty) || 0;

                    const itemHtml = `
                        <div class="receipt-item">
                            <div class="receipt-item-info">
                                <span class="receipt-item-name">${itemName}</span>
                                <span class="receipt-item-variant">${variantLabel ? variantLabel + ' | ' : ''}Qty: ${qty}</span>
                            </div>
                            <span class="receipt-item-price">RP ${formatRupiah(subtotal)}</span>
                        </div>
                    `;
                    itemsList.innerHTML += itemHtml;
                });
            } else {
                itemsList.innerHTML = '<p style="color: #FFF4D6; opacity: 0.7; text-align: center;">Pesanan sedang diproses</p>';
            }

            // Update total with real data
            document.getElementById('receiptTotal').textContent = 'RP ' + formatRupiah(totalAmount);

            // Update notes with real data
            const noteElement = document.getElementById('receiptNote');
            const noteSection = noteElement.closest('.receipt-note-section');
            if (orderData.notes && orderData.notes.trim()) {
                noteElement.textContent = orderData.notes;
                noteSection.style.display = 'block';
            } else {
                noteSection.style.display = 'none';
            }

            // Show modal
            document.getElementById('successModal').classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        // Helper function to format number to Rupiah format
        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID').format(number);
        }

        // Close success modal
        function closeSuccessModal() {
            document.getElementById('successModal').classList.remove('show');
            document.body.style.overflow = '';
        }

        // Go to order history
        function goToRiwayat() {
            window.location.href = '/customer/order-history';
        }

        // Go back to home
        function goBackToHome() {
            window.location.href = '/';
        }

        // Restore draft alamat dari localStorage saat page load
        function restoreAddressDraft() {
            const draft = JSON.parse(localStorage.getItem('delivery_address_draft') || 'null');
            if (!draft) return;

            // Set form fields
            if (draft.name) document.getElementById('recipientName').value = draft.name;
            if (draft.phone) document.getElementById('recipientPhone').value = draft.phone;
            if (draft.province) document.getElementById('province').value = draft.province;
            if (draft.city) document.getElementById('city').value = draft.city;
            if (draft.district) document.getElementById('district').value = draft.district;
            if (draft.postalCode) document.getElementById('postalCode').value = draft.postalCode;
            if (draft.address) document.getElementById('fullAddress').value = draft.address;
            if (draft.detail) document.getElementById('addressDetail').value = draft.detail;
            if (draft.label) {
                document.getElementById('addressLabel').value = draft.label;
                document.querySelectorAll('.label-btn').forEach(b => {
                    b.classList.toggle('active', b.textContent.trim().toLowerCase() === draft.label);
                });
            }

            // Rebuild display text
            const province = document.getElementById('province');
            const city = document.getElementById('city');
            const district = document.getElementById('district');
            const provinceName = province.options[province.selectedIndex]?.text || '';
            const cityName = city.options[city.selectedIndex]?.text || '';
            const districtName = district.options[district.selectedIndex]?.text || '';

            let fullAddressText = draft.address || '';
            if (districtName) fullAddressText += ', Kec. ' + districtName;
            if (cityName) fullAddressText += ', ' + cityName;
            if (provinceName) fullAddressText += ', ' + provinceName;
            if (draft.postalCode) fullAddressText += ' ' + draft.postalCode;

            document.getElementById('deliveryRecipientName').textContent = draft.name;
            document.getElementById('deliveryRecipientPhone').textContent = draft.phone;
            document.getElementById('deliveryAddressDetail').textContent = fullAddressText;
        }

        // Edit address
        function editAddress() {
            // Load draft ke form sebelum buka modal
            const draft = JSON.parse(localStorage.getItem('delivery_address_draft') || '{}');
            if (draft.name) document.getElementById('recipientName').value = draft.name;
            if (draft.phone) document.getElementById('recipientPhone').value = draft.phone;
            if (draft.province) document.getElementById('province').value = draft.province;
            if (draft.city) document.getElementById('city').value = draft.city;
            if (draft.district) document.getElementById('district').value = draft.district;
            if (draft.postalCode) document.getElementById('postalCode').value = draft.postalCode;
            if (draft.address) document.getElementById('fullAddress').value = draft.address;
            if (draft.detail) document.getElementById('addressDetail').value = draft.detail;
            if (draft.label) {
                document.getElementById('addressLabel').value = draft.label;
                document.querySelectorAll('.label-btn').forEach(b => {
                    b.classList.toggle('active', b.textContent.trim().toLowerCase() === draft.label);
                });
            }
            document.getElementById('addressModal').style.display = 'flex';
        }

        // Close address modal
        function closeAddressModal() {
            document.getElementById('addressModal').style.display = 'none';
        }

        // Select address label
        function selectAddressLabel(btn, label) {
            // Remove active from all buttons
            document.querySelectorAll('.label-btn').forEach(b => b.classList.remove('active'));
            // Add active to clicked button
            btn.classList.add('active');
            // Update hidden input
            document.getElementById('addressLabel').value = label;
        }

        // Save address
        function saveAddress() {
            const name = document.getElementById('recipientName').value.trim();
            const phone = document.getElementById('recipientPhone').value.trim();
            const province = document.getElementById('province');
            const city = document.getElementById('city');
            const district = document.getElementById('district');
            const postalCode = document.getElementById('postalCode').value.trim();
            const address = document.getElementById('fullAddress').value.trim();
            const detail = document.getElementById('addressDetail').value;
            const label = document.getElementById('addressLabel').value;

            // Validate required fields
            if (!name) {
                alert('Nama penerima wajib diisi');
                document.getElementById('recipientName').focus();
                return;
            }
            if (!phone) {
                alert('Nomor telepon wajib diisi');
                document.getElementById('recipientPhone').focus();
                return;
            }
            if (!province.value) {
                alert('Provinsi wajib dipilih');
                province.focus();
                return;
            }
            if (!city.value) {
                alert('Kota/Kabupaten wajib dipilih');
                city.focus();
                return;
            }
            if (!district.value) {
                alert('Kecamatan wajib dipilih');
                district.focus();
                return;
            }
            if (!postalCode) {
                alert('Kode pos wajib diisi');
                document.getElementById('postalCode').focus();
                return;
            }
            if (!address) {
                alert('Alamat lengkap wajib diisi');
                document.getElementById('fullAddress').focus();
                return;
            }

            // Build full address string
            const provinceName = province.options[province.selectedIndex]?.text || '';
            const cityName = city.options[city.selectedIndex]?.text || '';
            const districtName = district.options[district.selectedIndex]?.text || '';

            let fullAddressText = address;
            if (districtName) fullAddressText += ', Kec. ' + districtName;
            if (cityName) fullAddressText += ', ' + cityName;
            if (provinceName) fullAddressText += ', ' + provinceName;
            if (postalCode) fullAddressText += ' ' + postalCode;

            // Simpan draft ke localStorage
            localStorage.setItem('delivery_address_draft', JSON.stringify({
                name, phone,
                province: province.value,
                city: city.value,
                district: district.value,
                postalCode, address, detail, label
            }));

            // Update display - using specific IDs
            document.getElementById('deliveryRecipientName').textContent = name;
            document.getElementById('deliveryRecipientPhone').textContent = phone;
            document.getElementById('deliveryAddressDetail').textContent = fullAddressText;

            closeAddressModal();
        }

        // Toggle sections based on order type (called from navbar)
        window.toggleDeliverySection = function (isDelivery) {
            const deliveryAddressSection = document.getElementById('deliveryAddressSection');
            const paymentMethodsSection = document.getElementById('paymentMethodsSection');
            const customerInfoSection = document.getElementById('customerInfoSection');

            if (isDelivery) {
                deliveryAddressSection.style.display = 'block';
                paymentMethodsSection.style.display = 'block'; // Metode pembayaran selalu tampil
                if (customerInfoSection) customerInfoSection.style.display = 'none';
            } else {
                deliveryAddressSection.style.display = 'none';
                paymentMethodsSection.style.display = 'block';
                if (customerInfoSection) customerInfoSection.style.display = 'block';
            }
        }


        // Prevent multiple simultaneous loads
        let isLoadingCheckoutItems = false;

        // Function to render checkout items
        function renderCheckoutItems(items) {
            const container = document.getElementById('checkoutItemsContainer');

            console.log('renderCheckoutItems called with', items.length, 'items');

            if (!items || items.length === 0) {
                console.log('No items to render, showing empty state');
                container.innerHTML = `
                    <div style="text-align: center; padding: 60px 40px; color: rgba(255,255,255,0.6);">
                        <p style="font-size: 16px; margin-bottom: 12px;">Tidak ada produk yang dipilih</p>
                        <p style="font-size: 13px; opacity: 0.7;">Silakan kembali ke cart dan pilih produk</p>
                        <a href="/customer/cart" style="display: inline-block; background: #CA7842; color: white; padding: 10px 24px; border-radius: 8px; margin-top: 16px; text-decoration: none;">Kembali ke Cart</a>
                    </div>
                `;
                updateOrderTotal();
                return;
            }

            console.log('Building HTML for', items.length, 'items');

            // Helper: extract variant from note
            function getVariantFromNote(note) {
                if (!note) return null;
                if (note.startsWith('[Hot]')) return 'Hot';
                if (note.startsWith('[Ice]')) return 'Ice';
                return null;
            }

            // Build HTML
            let html = '';
            items.forEach((item, index) => {
                console.log(`  - Item ${index + 1}:`, item.menu_name, 'x', item.quantity, '= Rp', item.subtotal);
                const variant = getVariantFromNote(item.note);
                const variantBadge = variant
                    ? `<span class="checkout-variant-badge ${variant === 'Hot' ? 'variant-hot' : 'variant-ice'}">${variant}</span>`
                    : '';
                html += `
                    <div class="order-item-card">
                        <input type="checkbox" class="order-item-checkbox" checked
                            data-item-id="${item.id}"
                            data-subtotal="${item.subtotal}"
                            data-quantity="${item.quantity}"
                            onchange="updateOrderTotal()">
                        <div class="order-item-info">
                            ${item.menu_image ?
                        `<img src="${item.menu_image}" alt="${item.menu_name}" class="order-item-image">` :
                        `<img src="/images/placeholders/placeholder-${item.menu_category === 'food' ? 'food' : item.menu_category === 'coffee_beans' ? 'coffee-beans' : 'drink'}.svg" alt="${item.menu_name}" class="order-item-image" style="object-fit: contain; padding: 8px; background: rgba(100,80,70,0.3);">`
                    }
                            <div class="order-item-details">
                                <p class="order-item-name">${item.menu_name}</p>
                                ${variantBadge}
                                <p class="order-item-price">Rp ${new Intl.NumberFormat('id-ID').format(item.price)}</p>
                            </div>
                        </div>
                        <div class="order-item-actions">
                            <div class="checkout-quantity-controls">
                                <button type="button" class="checkout-qty-btn checkout-qty-minus" onclick="updateQuantityCheckout(${item.id}, ${item.quantity - 1})" ${item.quantity <= 1 ? 'disabled' : ''}>−</button>
                                <span class="checkout-qty-value">${item.quantity}</span>
                                <button type="button" class="checkout-qty-btn checkout-qty-plus" onclick="updateQuantityCheckout(${item.id}, ${item.quantity + 1})">+</button>
                            </div>
                            <p class="order-item-subtotal">Rp ${new Intl.NumberFormat('id-ID').format(item.subtotal)}</p>
                        </div>
                        <button class="order-item-delete" onclick="showDeleteConfirm(${item.id})">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            </svg>
                        </button>
                    </div>
                `;
            });

            console.log('Setting innerHTML to container');
            container.innerHTML = html;

            console.log('Calling updateOrderTotal');
            updateOrderTotal();

            // Collect notes per item: variant + custom note linked together
            const noteParts = [];

            items.forEach(item => {
                if (!item.note) return;
                const note = item.note.trim();
                const hotMatch = note.match(/^\[Hot\]\s*(.*)/);
                const iceMatch = note.match(/^\[Ice\]\s*(.*)/);

                if (hotMatch) {
                    const custom = hotMatch[1].trim();
                    noteParts.push(custom ? `Hot - ${custom}` : 'Hot');
                } else if (iceMatch) {
                    const custom = iceMatch[1].trim();
                    noteParts.push(custom ? `Ice - ${custom}` : 'Ice');
                } else if (note) {
                    noteParts.push(note);
                }
            });

            const orderNotesField = document.getElementById('orderNotes');
            if (orderNotesField && noteParts.length > 0) {
                orderNotesField.value = noteParts.join('\n');
                console.log('Filled orderNotes:', noteParts.join('\n'));
            }

            console.log('renderCheckoutItems completed');
        }

        // Fetch and render cart items
        async function loadCheckoutItems() {
            // Prevent multiple simultaneous calls
            if (isLoadingCheckoutItems) {
                console.log('Already loading checkout items, skipping...');
                return;
            }

            isLoadingCheckoutItems = true;
            console.log('Starting loadCheckoutItems...');
            const startTime = Date.now();
            const token = localStorage.getItem('guest_token') || '';
            const container = document.getElementById('checkoutItemsContainer');

            if (!token) {
                console.warn('No guest token found');
                container.innerHTML = `
                    <div style="text-align: center; padding: 40px; color: #ef4444;">
                        <p>Session tidak ditemukan. Silakan kembali ke cart.</p>
                        <a href="/customer/cart" class="back-to-cart-btn" style="display: inline-block; background: #CA7842; padding: 10px 20px; border-radius: 8px; margin-top: 10px;">Back to Cart</a>
                    </div>
                `;
                isLoadingCheckoutItems = false;
                return;
            }

            try {
                console.log('Fetching cart from API...');

                const response = await fetch('/api/customer/cart', {
                    headers: {
                        'X-GUEST-TOKEN': token,
                        'Accept': 'application/json'
                    }
                });

                const fetchTime = Date.now() - startTime;
                console.log(`API response received in ${fetchTime}ms`);

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }

                const result = await response.json();
                console.log('Cart data from API:', result);

                let items = result.data.items || [];
                console.log(`Found ${items.length} items in cart`);

                // Filter items based on selection from cart page
                const selectedIdsString = localStorage.getItem('selected_cart_items');
                console.log('Selected IDs from localStorage:', selectedIdsString);

                if (selectedIdsString) {
                    try {
                        const selectedIds = JSON.parse(selectedIdsString);
                        console.log('Parsed selected IDs:', selectedIds);

                        const selectedIdStrings = selectedIds.map(id => String(id));
                        items = items.filter(item => selectedIdStrings.includes(String(item.id)));
                        console.log(`Filtered to ${items.length} selected items`);
                    } catch (e) {
                        console.error('Error parsing selected_cart_items:', e);
                    }
                } else {
                    console.warn('No selected items found in localStorage, showing all items');
                }

                // Render items
                console.log('Rendering items...');
                renderCheckoutItems(items);

                const renderTime = Date.now() - startTime;
                console.log(`Checkout items loaded successfully in ${renderTime}ms`);

                isLoadingCheckoutItems = false;
            } catch (error) {
                const errorTime = Date.now() - startTime;
                console.error(`Error loading checkout items after ${errorTime}ms:`, error);

                let errorMessage = error.message;
                if (error.name === 'AbortError') {
                    errorMessage = 'Request timeout. Server tidak merespons dalam 30 detik.';
                }

                container.innerHTML = `
                    <div style="text-align: center; padding: 40px; color: #ef4444;">
                        <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-bottom: 16px; opacity: 0.6;">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                        <p style="font-size: 16px; font-weight: 600;">Gagal memuat items</p>
                        <p style="font-size: 14px; margin-top: 8px; color: rgba(239, 68, 68, 0.8);">${errorMessage}</p>
                        <div style="margin-top: 20px; display: flex; gap: 12px; justify-content: center;">
                            <button onclick="loadCheckoutItems()" style="padding: 10px 24px; background: #CA7842; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 500;">Coba Lagi</button>
                            <a href="/customer/cart" style="padding: 10px 24px; background: rgba(255,255,255,0.1); color: white; border: none; border-radius: 8px; cursor: pointer; text-decoration: none; display: inline-block;">Kembali ke Cart</a>
                        </div>
                    </div>
                `;
                isLoadingCheckoutItems = false;
            }
        }

        // Update quantity via API
        async function updateQuantityCheckout(itemId, newQty) {
            const token = localStorage.getItem('guest_token') || '';
            try {
                const response = await fetch(`/api/customer/cart/items/${itemId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-GUEST-TOKEN': token,
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    },
                    body: JSON.stringify({ quantity: parseInt(newQty) })
                });

                if (response.ok) {
                    await loadCheckoutItems(); // Reload to update prices
                }
            } catch (error) {
                console.error('Error updating quantity:', error);
            }
        }

        // Delete Logic
        let itemToDelete = null;

        function showDeleteConfirm(itemId) {
            itemToDelete = itemId;
            document.getElementById('deleteConfirmModal').classList.add('show'); // Using same class 'show' as error modal logic
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteConfirm() {
            itemToDelete = null;
            document.getElementById('deleteConfirmModal').classList.remove('show');
            document.body.style.overflow = '';
        }

        async function confirmDelete() {
            if (!itemToDelete) return;

            const token = localStorage.getItem('guest_token') || '';

            // Show loading state on button
            const confirmBtn = document.querySelector('.btn-confirm-delete');
            const originalText = confirmBtn.textContent;
            confirmBtn.textContent = 'Menghapus...';
            confirmBtn.disabled = true;

            try {
                const response = await fetch(`/api/customer/cart/items/${itemToDelete}`, {
                    method: 'DELETE',
                    headers: {
                        'X-GUEST-TOKEN': token,
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    }
                });

                if (response.ok) {
                    await loadCheckoutItems();
                    closeDeleteConfirm();
                } else {
                    alert('Gagal menghapus produk');
                }
            } catch (error) {
                console.error('Error removing item:', error);
                alert('Terjadi kesalahan saat menghapus produk');
            } finally {
                confirmBtn.textContent = originalText;
                confirmBtn.disabled = false;
            }
        }

        // Global variable for service fee from backend
        let checkoutServiceFee = 0;

        // Fetch checkout settings from backend (service fee, etc.)
        async function loadCheckoutSettings() {
            try {
                const response = await fetch('/api/customer/checkout/settings', {
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    const result = await response.json();
                    checkoutServiceFee = result.data.service_fee || 0;

                    // Show/hide service fee row based on value
                    const serviceFeeRow = document.getElementById('serviceFeeRow');
                    if (serviceFeeRow) {
                        serviceFeeRow.style.display = checkoutServiceFee > 0 ? 'flex' : 'none';
                    }

                    // Update totals with new service fee
                    updateOrderTotal();
                }
            } catch (error) {
                console.error('Error loading checkout settings:', error);
                // Keep service fee as 0 if failed to load
                checkoutServiceFee = 0;
            }
        }

        // Calculate and update order total
        function updateOrderTotal() {
            let subtotal = 0;
            let totalQty = 0;

            document.querySelectorAll('.order-item-checkbox:checked').forEach(checkbox => {
                subtotal += parseFloat(checkbox.getAttribute('data-subtotal') || 0);
                totalQty += parseInt(checkbox.getAttribute('data-quantity') || 0);
            });

            // Update labels
            const subtotalLabel = document.getElementById('summarySubtotalLabel');
            const subtotalValue = document.getElementById('summarySubtotalValue');
            const totalElement = document.querySelector('.summary-total-value');

            if (subtotalLabel) subtotalLabel.textContent = `Subtotal (${totalQty} Produk)`;
            if (subtotalValue) subtotalValue.textContent = 'Rp ' + formatRupiah(subtotal);

            if (totalElement) {
                totalElement.textContent = 'Rp ' + formatRupiah(subtotal);
            }
        }

        // Format number to Rupiah
        function formatRupiah(amount) {
            return new Intl.NumberFormat('id-ID').format(amount);
        }

        // Hide order summary on mobile when user focuses on input fields
        function setupMobileInputHandlers() {
            const dineInName = document.getElementById('dineInName');
            const dineInPhone = document.getElementById('dineInPhone');
            const orderNotes = document.getElementById('orderNotes');
            const orderSummary = document.querySelector('.order-summary-section');

            if (!dineInName || !dineInPhone || !orderSummary) return;

            // Check if mobile (window width <= 600px)
            function isMobile() {
                return window.innerWidth <= 600;
            }

            function hideOrderSummary() {
                if (isMobile()) {
                    orderSummary.style.display = 'none';
                }
            }

            function showOrderSummary() {
                if (isMobile()) {
                    orderSummary.style.display = 'block';
                }
            }

            // Check if active element is one of the inputs
            function isActiveInputField() {
                return document.activeElement === dineInName ||
                    document.activeElement === dineInPhone ||
                    document.activeElement === orderNotes;
            }

            // Add event listeners for focus (ketika input di-klik)
            dineInName.addEventListener('focus', hideOrderSummary);
            dineInPhone.addEventListener('focus', hideOrderSummary);
            if (orderNotes) {
                orderNotes.addEventListener('focus', hideOrderSummary);
            }

            // Add event listeners for blur (ketika keluar dari input)
            dineInName.addEventListener('blur', function () {
                // Delay to check if user is moving to another input
                setTimeout(() => {
                    if (!isActiveInputField()) {
                        showOrderSummary();
                    }
                }, 100);
            });

            dineInPhone.addEventListener('blur', function () {
                setTimeout(() => {
                    if (!isActiveInputField()) {
                        showOrderSummary();
                    }
                }, 100);
            });

            if (orderNotes) {
                orderNotes.addEventListener('blur', function () {
                    setTimeout(() => {
                        if (!isActiveInputField()) {
                            showOrderSummary();
                        }
                    }, 100);
                });
            }
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function () {
            // Load cart items for checkout
            loadCheckoutItems();

            // Setup mobile input handlers
            setupMobileInputHandlers();

            // Restore draft alamat pengiriman dari localStorage
            restoreAddressDraft();

            // Listen for table selection changes
            window.addEventListener('tableSelected', function (event) {
                console.log('Table selected event received:', event.detail);
                validateCheckoutButton();
            });

            window.addEventListener('tableCleared', function () {
                console.log('Table cleared event received');
                validateCheckoutButton();
            });

            // Initial validation
            validateCheckoutButton();
        });

        // Validate checkout button state
        function validateCheckoutButton() {
            const checkoutBtn = document.querySelector('.checkout-btn');
            if (!checkoutBtn) return;

            const orderType = document.getElementById('orderTypeDisplay')?.textContent.toLowerCase().replace(' ', '_');
            console.log('Validating checkout button for order type:', orderType);

            if (orderType === 'dine_in') {
                const selectedTableId = localStorage.getItem('selected_table_id');
                const tableNumber = localStorage.getItem('selected_table_number');

                if (!selectedTableId || !tableNumber) {
                    // Disable checkout button if no table selected for dine in
                    checkoutBtn.disabled = true;
                    checkoutBtn.title = 'Silahkan pilih meja terlebih dahulu';
                    checkoutBtn.style.cursor = 'not-allowed';
                    checkoutBtn.style.opacity = '0.6';
                    console.log('Checkout disabled: No table selected for Dine In');
                } else {
                    // Enable checkout button
                    checkoutBtn.disabled = false;
                    checkoutBtn.title = '';
                    checkoutBtn.style.cursor = 'pointer';
                    checkoutBtn.style.opacity = '1';
                    console.log('Checkout enabled: Table', tableNumber, 'selected');
                }
            } else {
                // Enable checkout button for non-dine-in orders
                checkoutBtn.disabled = false;
                checkoutBtn.title = '';
                checkoutBtn.style.cursor = 'pointer';
                checkoutBtn.style.opacity = '1';
                console.log('✓ Checkout enabled for', orderType);
            }
        }
    </script>

</x-customer.checkout-layout>