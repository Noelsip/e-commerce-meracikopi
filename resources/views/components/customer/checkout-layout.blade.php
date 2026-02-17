<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Ringkasan Pesanan - Meracikopi</title>

    <!-- Fonts - Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Alpine.js -->
    <script defer src="{{ asset('js/alpine.min.js') }}"></script>

    <!-- DOKU Payment Gateway -->
    <script>
        // DOKU payment handling functions
        window.appEnv = '{{ config("app.env") }}';

        window.dokuPayment = {
            simulatePayment: function (invoiceNumber) {
                if (!confirm('Simulasikan pembayaran BERHASIL untuk invoice ' + invoiceNumber + '?')) return;

                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                const button = document.getElementById('simulate-btn-' + invoiceNumber);
                if (button) button.disabled = true;

                fetch(`/api/customer/orders/${invoiceNumber}/simulate-payment`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-GUEST-TOKEN': localStorage.getItem('guest_token')
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Pembayaran berhasil disimulasikan! Tunggu sebentar...');
                            if (button) button.innerHTML = 'Berhasil! Redirecting...';
                            this.checkPaymentStatus(invoiceNumber);
                        } else {
                            alert('Gagal simulasi: ' + (data.message || 'Unknown error'));
                            if (button) button.disabled = false;
                        }
                    })
                    .catch(error => {
                        console.error('Error simulating payment:', error);
                        alert('Error simulating payment');
                        if (button) button.disabled = false;
                    });
            },
            handlePayment: function (data) {
                console.log('Handling DOKU Payment:', data);

                // Set current invoice for status checking
                this.currentInvoiceNumber = data.invoice_number;

                // Show modal
                this.showPaymentModal(data);

                // Dispatch based on display_type
                if (data.display_type === 'popup') {
                    this.handlePopupPayment(data);
                } else if (data.display_type === 'on_page' && data.payment_method === 'qris') {
                    this.handleQRPayment(data.qr_code_data, data.instructions, data.invoice_number);
                } else {
                    // Fallback to legacy handling
                    if (data.payment_method === 'qris' && data.qr_code_data) {
                        this.handleQRPayment(data.qr_code_data, data.instructions, data.invoice_number);
                    } else if (data.virtual_account_info) {
                        this.handleVAPayment(data.virtual_account_info, data.instructions);
                    } else if (data.ewallet_info) {
                        this.handleEWalletPayment(data.ewallet_info, data.instructions);
                    } else if (data.payment_url) {
                        this.handlePopupPayment(data); // Default to popup if URL exists
                    } else {
                        console.error('Unknown payment data structure:', data);
                        alert('Format data pembayaran tidak dikenakan.');
                    }
                }

                // Start polling status
                if (data.invoice_number) {
                    this.startPaymentStatusCheck(data.invoice_number);
                }
            },

            handlePopupPayment: function (data) {
                if (!data.payment_url) {
                    alert('Maaf, URL pembayaran tidak ditemukan. Silakan coba metode lain.');
                    console.error('Missing payment_url for popup', data);
                    return;
                }

                const width = 600;
                const height = 700;
                const left = (window.screen.width / 2) - (width / 2);
                const top = (window.screen.height / 2) - (height / 2);

                const popup = window.open(
                    data.payment_url,
                    'DOKU Payment',
                    `width=${width},height=${height},top=${top},left=${left},scrollbars=yes,resizable=yes`
                );

                const content = `
                    <div class="popup-payment-waiting">
                        <div style="text-align: center; padding: 40px;">
                            <div class="loading-spinner" style="margin: 0 auto 20px;"></div>
                            <h3>Menunggu Pembayaran</h3>
                            <p class="payment-instructions">Scan QR Code yang muncul di jendela pembayaran.</p>
                            
                            <div style="margin-top: 20px; padding: 15px; background: rgba(255,255,255,0.05); border-radius: 8px;">
                                <p style="margin-bottom: 10px; font-size: 0.9em; opacity: 0.8;">Jendela pembayaran tertutup/terblokir?</p>
                                <button onclick="window.open('${data.payment_url}', 'DOKU Payment', 'width=600,height=700,scrollbars=yes,resizable=yes')" 
                                        style="padding: 10px 24px; font-size: 0.9em; background: #D4A574; color: #1e1715; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; transition: opacity 0.2s;"
                                        onmouseover="this.style.opacity='0.85'" onmouseout="this.style.opacity='1'">
                                    Buka Ulang Jendela Pembayaran
                                </button>
                            </div>

                            <div class="payment-details" style="margin-top: 30px;">
                                <p><strong>Invoice:</strong> ${data.invoice_number}</p>
                                <p style="font-size: 1.2em; margin-top: 8px;"><strong>Total:</strong> ${this.formatCurrency(data.amount || 0)}</p>
                            </div>
                        </div>
                    </div>
                `;
                document.getElementById('paymentModalContent').innerHTML = content;

                // Check if popup closed by user before payment
                const timer = setInterval(() => {
                    if (popup && popup.closed) {
                        clearInterval(timer);
                        console.log('Payment popup closed by user');
                        // We continue polling status in background via startPaymentStatusCheck
                    }
                }, 1000);
            },
            showPaymentModal: function (paymentData) {
                // Create and show payment modal
                const modalHtml = `
                    <div id="paymentModal" class="payment-modal-overlay">
                        <div class="payment-modal">
                            <div class="payment-modal-header">
                                <h3>Pembayaran ${this.getPaymentMethodName(paymentData.payment_method)}</h3>
                                <button onclick="window.dokuPayment.closePaymentModal()" class="close-btn">&times;</button>
                            </div>
                            <div class="payment-modal-content" id="paymentModalContent">
                                <div class="loading">Memuat...</div>
                            </div>
                            <div class="payment-modal-footer">
                                <p class="payment-status" id="paymentStatus">Menunggu pembayaran...</p>
                            </div>
                        </div>
                    </div>
                `;
                document.body.insertAdjacentHTML('beforeend', modalHtml);
            },

            handleQRPayment: function (qrData, instructions, invoiceNumber) {
                // Support both URL-based QR (from Direct API) and base64 QR (from fallback)
                const qrUrl = qrData.qr_url || qrData.qr_image || qrData.qr_code || '';
                const qrString = qrData.qr_string || '';
                console.log('QR data:', { qrUrl: qrUrl ? qrUrl.substring(0, 80) + '...' : 'none', qrString: qrString ? 'present' : 'none' });

                // Determine image src: if it starts with http, use directly; otherwise assume base64
                let imgSrc = '';
                if (qrUrl) {
                    if (qrUrl.startsWith('http')) {
                        imgSrc = qrUrl; // Direct URL from DOKU API
                    } else {
                        imgSrc = `data:image/png;base64,${qrUrl}`; // Base64 from fallback
                    }
                }

                const content = `
                    <div class="qr-payment">
                        <div class="qr-code-container">
                            ${imgSrc ?
                        `<img src="${imgSrc}" 
                                     alt="QR Code QRIS" class="qr-code-image" 
                                     style="max-width: 280px; background: white; padding: 16px; border-radius: 12px;"
                                     onload="console.log('QR image loaded successfully')"
                                     onerror="console.error('QR image load error'); this.style.display='none'; this.parentNode.innerHTML='<div style=\\'padding: 40px; text-align: center; border: 2px dashed #ccc;\\'>QR Code Error<br><small>Gagal memuat gambar</small></div>';" />` :
                        `<div style="padding: 40px; text-align: center; border: 2px dashed #ccc;">
                                    QR Code tidak tersedia<br><small>Silahkan coba lagi</small>
                                 </div>`
                    }
                        </div>
                        <p class="payment-instructions">${instructions}</p>
                        <div class="payment-details">
                            <p><strong>Berlaku hingga:</strong> ${this.formatDate(qrData.expired_at)}</p>
                            ${window.appEnv === 'local' ?
                        `<button id="simulate-btn-${invoiceNumber}" 
                                    onclick="window.dokuPayment.simulatePayment('${invoiceNumber}')"
                                    style="margin-top: 15px; width: 100%; padding: 10px; background: #28a745; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: bold;">
                                    [DEV] Simulate Payment Success
                                 </button>` : ''
                    }
                        </div>
                    </div>
                `;
                document.getElementById('paymentModalContent').innerHTML = content;
            },

            handleVAPayment: function (vaData, instructions) {
                const content = `
                    <div class="va-payment">
                        <div class="va-info">
                            <div class="va-bank">${vaData.bank_name}</div>
                            <div class="va-number-container">
                                <label>Nomor Virtual Account:</label>
                                <div class="va-number-display">
                                    <span class="va-number">${vaData.va_number}</span>
                                    <button onclick="window.dokuPayment.copyToClipboard('${vaData.va_number}')" 
                                            class="copy-btn">Salin</button>
                                </div>
                            </div>
                            <div class="va-amount">
                                <label>Jumlah:</label>
                                <span class="amount">Rp ${this.formatCurrency(vaData.amount)}</span>
                            </div>
                        </div>
                        <p class="payment-instructions">${instructions}</p>
                        <div class="payment-details">
                            <p><strong>Berlaku hingga:</strong> ${this.formatDate(vaData.expired_at)}</p>
                        </div>
                    </div>
                `;
                document.getElementById('paymentModalContent').innerHTML = content;
            },

            handleEWalletPayment: function (ewalletData, instructions) {
                const content = `
                    <div class="ewallet-payment">
                        <p class="payment-instructions">${instructions}</p>
                        <div class="ewallet-actions">
                            <a href="${ewalletData.payment_url}" 
                               target="_blank" 
                               class="ewallet-btn">
                                Buka Aplikasi E-Wallet
                            </a>
                        </div>
                        <div class="payment-details">
                            <p><strong>Berlaku hingga:</strong> ${this.formatDate(ewalletData.expired_at)}</p>
                        </div>
                    </div>
                `;
                document.getElementById('paymentModalContent').innerHTML = content;
            },

            handleURLPayment: function (paymentUrl) {
                // Redirect to payment URL
                window.open(paymentUrl, '_blank');
            },

            startPaymentStatusCheck: function (invoiceNumber) {
                // Poll payment status every 10 seconds
                this.statusInterval = setInterval(() => {
                    this.checkPaymentStatus(invoiceNumber);
                }, 10000);
            },

            checkPaymentStatus: function (invoiceNumber) {
                fetch(`/api/customer/orders/${invoiceNumber}/payment-status`, {
                    headers: {
                        'X-GUEST-TOKEN': localStorage.getItem('guest_token'),
                        'Accept': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('paymentStatus').innerText = this.getStatusText(data.status);

                        if (data.status === 'paid') {
                            clearInterval(this.statusInterval);
                            this.onPaymentSuccess(data);
                        } else if (data.status === 'failed') {
                            clearInterval(this.statusInterval);
                            this.onPaymentError(data);
                        }
                    })
                    .catch(error => {
                        console.error('Payment status check error:', error);
                    });
            },

            onPaymentSuccess: function (data) {
                // Update main content to show success animation
                const successContent = `
                    <div class="payment-success-animation" style="padding: 40px; text-align: center;">
                        <div style="width: 80px; height: 80px; background: #28a745; border-radius: 50%; margin: 0 auto 20px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        </div>
                        <h3 style="color: #28a745; margin-bottom: 10px; font-size: 24px;">Pembayaran Berhasil!</h3>
                        <p style="color: #666; font-size: 16px;">Terima kasih, pesanan Anda telah kami terima.</p>
                        <p style="margin-top: 20px; font-size: 14px; color: #888;">Mengalihkan ke struk...</p>
                    </div>
                `;
                document.getElementById('paymentModalContent').innerHTML = successContent;

                // Hide footer status if it exists
                const statusEl = document.getElementById('paymentStatus');
                if (statusEl) statusEl.style.display = 'none';

                setTimeout(() => {
                    this.closePaymentModal();
                    if (typeof window.onPaymentSuccess === 'function') {
                        window.onPaymentSuccess(data);
                    }
                }, 2000);
            },

            onPaymentError: function (data) {
                document.getElementById('paymentStatus').innerHTML =
                    '<span style="color: red;">✗ Pembayaran Gagal!</span>';
                if (typeof window.onPaymentError === 'function') {
                    window.onPaymentError(data);
                }
            },

            closePaymentModal: function () {
                const modal = document.getElementById('paymentModal');
                if (modal) {
                    modal.remove();
                }
                if (this.statusInterval) {
                    clearInterval(this.statusInterval);
                }
            },

            // Helper functions
            getPaymentMethodName: function (method) {
                const names = {
                    'qris': 'QRIS',
                    'dana': 'DANA',
                    'gopay': 'GoPay',
                    'shopeepay': 'ShopeePay',
                    'ovo': 'OVO',
                    'bca_va': 'Virtual Account BCA',
                    'bni_va': 'Virtual Account BNI',
                    'bri_va': 'Virtual Account BRI',
                    'mandiri_va': 'Virtual Account Mandiri'
                };
                return names[method] || method.toUpperCase();
            },

            getStatusText: function (status) {
                const texts = {
                    'pending': 'Menunggu pembayaran...',
                    'paid': 'Pembayaran berhasil!',
                    'failed': 'Pembayaran gagal!',
                    'expired': 'Pembayaran kedaluwarsa'
                };
                return texts[status] || 'Status tidak diketahui';
            },

            formatDate: function (dateString) {
                if (!dateString) return '-';
                const date = new Date(dateString);
                return date.toLocaleDateString('id-ID', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            },

            formatCurrency: function (amount) {
                return new Intl.NumberFormat('id-ID').format(amount);
            },

            copyToClipboard: function (text) {
                navigator.clipboard.writeText(text).then(() => {
                    alert('Nomor Virtual Account berhasil disalin!');
                });
            }
        };
    </script>

    <style>
        .payment-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .payment-modal {
            background: white;
            border-radius: 8px;
            max-width: 500px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
        }

        .payment-modal-header {
            padding: 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .payment-modal-content {
            padding: 20px;
            text-align: center;
        }

        .qr-code-image {
            max-width: 250px;
            width: 100%;
            height: auto;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            background: white;
        }

        .qr-code-container {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 12px;
            margin-bottom: 15px;
        }

        .va-number-display {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 10px 0;
        }

        .va-number {
            font-family: monospace;
            font-size: 18px;
            font-weight: bold;
            background: #f5f5f5;
            padding: 10px;
            border-radius: 4px;
            flex: 1;
        }

        .copy-btn,
        .ewallet-btn {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
        }

        .payment-modal-footer {
            padding: 20px;
            border-top: 1px solid #eee;
            text-align: center;
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
        }
    </style>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #2A1B14;
            --secondary: #CA7842;
            --accent: #B2CD9C;
            --background: #1a1410;
            --stroke-color: #D9D9D9;
        }

        html,
        body {
            overflow-x: hidden;
            max-width: 100vw;
        }

        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            min-height: 100vh;
            /* Background linear gradient */
            background: linear-gradient(to right,
                    rgba(42, 27, 20, 0.75) 0%,
                    rgba(42, 27, 20, 0.45) 50%,
                    rgba(42, 27, 20, 0.75) 100%);
            background-color: #1a1410;
        }

        /* Spacer for fixed navbar */
        .navbar-spacer {
            height: 115px;
        }

        /* Back Button Container (Below Navbar) */
        .back-button-container {
            max-width: 1239px;
            margin: 0 auto;
            padding: 20px 20px 0 20px;
        }

        .back-to-cart-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: white;
            text-decoration: none;
            font-size: 14px;
            font-weight: 400;
            transition: all 0.2s ease;
        }

        .back-to-cart-btn:hover {
            color: #CA7842;
        }

        .back-to-cart-btn svg {
            flex-shrink: 0;
        }

        /* Checkout Page Container */
        .checkout-page-container {
            width: 100%;
            max-width: 1239px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        /* Order Type Tabs */
        .order-type-tabs {
            display: flex;
            gap: 40px;
            margin-bottom: 30px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            padding-bottom: 12px;
        }

        .order-type-tab-label {
            color: rgba(255, 255, 255, 0.6);
            font-size: 14px;
            font-weight: 500;
        }

        .order-type-tab-value {
            color: white;
            font-size: 14px;
            font-weight: 500;
        }

        /* Main Content Grid */
        .checkout-content {
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            gap: 30px;
            align-items: start;
        }

        /* Section Title */
        .section-title {
            color: rgba(255, 255, 255, 0.7);
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 16px;
        }

        /* Order Items Section */
        .order-items-section {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        /* Order Summary Section - Sticky */
        .order-summary-section {
            position: sticky;
            top: 20px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        /* Order Item Card - Desktop seperti mobile */
        .order-item-card {
            display: grid;
            grid-template-columns: auto 80px 1fr auto;
            grid-template-rows: auto auto;
            gap: 8px 16px;
            padding: 16px 20px;
            border: 1px solid #D9D9D9;
            border-radius: 8px;
            background: #241813;
            align-items: start;
        }

        /* Checkbox - Column 1, Row 1-2 span */
        .order-item-checkbox {
            grid-column: 1;
            grid-row: 1 / span 2;
            align-self: center;
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255, 255, 255, 0.4);
            border-radius: 4px;
            background: transparent;
            cursor: pointer;
            appearance: none;
            -webkit-appearance: none;
            position: relative;
            transition: all 0.2s ease;
            flex-shrink: 0;
        }

        .order-item-checkbox:checked {
            background-color: var(--secondary);
            border-color: var(--secondary);
        }

        .order-item-checkbox:checked::after {
            content: '✓';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 12px;
            font-weight: bold;
        }

        /* Image - Column 2, Row 1-2 span */
        .order-item-image {
            grid-column: 2;
            grid-row: 1 / span 2;
            width: 80px;
            height: 80px;
            background-color: rgba(100, 80, 70, 0.5);
            border-radius: 8px;
            object-fit: cover;
            align-self: center;
        }

        /* Product Info - Unwrap untuk grid */
        .order-item-info {
            display: contents;
        }

        /* Details container - Column 3, Row 1 */
        .order-item-details {
            grid-column: 3;
            grid-row: 1;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        /* Name */
        .order-item-name {
            color: white;
            font-size: 14px;
            font-weight: 500;
        }

        /* Hide individual price in details, show in actions */
        .order-item-details .order-item-price {
            display: none;
        }

        /* Actions - Column 3, Row 2 */
        .order-item-actions {
            grid-column: 3;
            grid-row: 2;
            display: flex;
            align-items: center;
            gap: 16px;
        }

        /* Price in actions area */
        .order-item-subtotal {
            color: #ca7842;
            font-size: 16px;
            font-weight: 600;
        }

        /* Delete button - Column 4, Row 1-2 span */
        .order-item-delete {
            grid-column: 4;
            grid-row: 1 / span 2;
            align-self: center;
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            padding: 8px;
            transition: color 0.2s ease;
        }

        .order-item-delete:hover {
            color: #e74c3c;
        }

        .order-item-price {
            color: #ca7842;
            font-size: 15px;
            font-weight: 600;
        }

        .order-item-quantity {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .quantity-dropdown {
            appearance: none;
            -webkit-appearance: none;
            background-color: rgba(75, 60, 53, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 4px;
            color: white;
            font-size: 12px;
            padding: 6px 24px 6px 10px;
            cursor: pointer;
            outline: none;
        }

        .quantity-wrapper {
            position: relative;
            display: inline-block;
        }

        .quantity-wrapper::after {
            content: '▼';
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            color: white;
            font-size: 8px;
            pointer-events: none;
        }

        /* Checkout Quantity Controls */
        .checkout-quantity-controls {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .checkout-qty-btn {
            width: 24px;
            height: 24px;
            border-radius: 4px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .checkout-qty-minus {
            background-color: rgba(255, 255, 255, 0.08);
            color: rgba(255, 255, 255, 0.8);
        }

        .checkout-qty-minus:disabled {
            background-color: rgba(75, 60, 53, 0.5);
            cursor: not-allowed;
            opacity: 0.5;
        }

        .checkout-qty-plus {
            background-color: rgba(202, 120, 66, 0.3);
            color: #CA7842;
            border-color: rgba(202, 120, 66, 0.4);
        }

        .checkout-qty-btn:hover:not(:disabled) {
            transform: scale(1.05);
        }

        .checkout-qty-value {
            color: white;
            font-size: 14px;
            min-width: 24px;
            text-align: center;
        }

        .order-summary-card {
            padding: 24px;
            border: 1px solid #D9D9D9;
            border-radius: 8px;
            background: transparent;
        }

        .summary-title {
            color: white;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .summary-label {
            color: rgba(255, 255, 255, 0.7);
            font-size: 14px;
            font-weight: 400;
        }

        .summary-value {
            color: white;
            font-size: 14px;
            font-weight: 500;
        }

        .summary-value.strikethrough {
            text-decoration: line-through;
            color: rgba(255, 255, 255, 0.5);
        }

        .summary-divider {
            height: 1px;
            background-color: rgba(255, 255, 255, 0.1);
            margin: 16px 0;
        }

        .summary-total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .summary-total-label {
            color: rgba(255, 255, 255, 0.7);
            font-size: 16px;
            font-weight: 500;
        }

        .summary-total-value {
            color: #CA7842;
            font-size: 20px;
            font-weight: 700;
        }

        /* Checkout Button */
        .checkout-btn {
            width: 100%;
            background-color: #CA7842;
            color: white;
            border: none;
            border-radius: 24px;
            padding: 14px 32px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(202, 120, 66, 0.3);
            margin-top: 8px;
        }

        .checkout-btn:hover:not(:disabled) {
            background-color: #d4864c;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(202, 120, 66, 0.4);
        }

        .checkout-btn:disabled {
            background-color: #6b5c54;
            cursor: not-allowed;
            opacity: 0.6;
            box-shadow: none;
        }

        /* Payment Methods Section */
        .payment-methods-section {
            margin-top: 40px;
        }

        .payment-method-card {
            display: flex;
            align-items: center;
            padding: 16px 20px;
            border: 1px solid #D9D9D9;
            border-radius: 8px;
            background: transparent;
            margin-bottom: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .payment-method-card:hover {
            background: rgba(255, 255, 255, 0.02);
        }

        .payment-method-card.selected {
            border-color: var(--secondary);
            background: rgba(202, 120, 66, 0.1);
        }

        .payment-radio {
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255, 255, 255, 0.4);
            border-radius: 50%;
            background: transparent;
            cursor: pointer;
            appearance: none;
            -webkit-appearance: none;
            position: relative;
            transition: all 0.2s ease;
            margin-right: 16px;
            flex-shrink: 0;
        }

        .payment-radio:checked {
            border-color: var(--secondary);
        }

        .payment-radio:checked::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 10px;
            height: 10px;
            background-color: var(--secondary);
            border-radius: 50%;
        }

        .payment-method-name {
            color: white;
            font-size: 14px;
            font-weight: 500;
        }

        .payment-method-icon {
            margin-left: auto;
            width: 40px;
            height: 24px;
            object-fit: contain;
        }

        /* Delivery Address Section */
        .delivery-address-section {
            margin-bottom: 24px;
            padding: 20px;
            border: 1px solid #D9D9D9;
            border-radius: 8px;
            background: transparent;
        }

        .address-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
            color: white;
        }

        .address-header svg {
            color: var(--secondary);
        }

        .address-title {
            font-size: 14px;
            font-weight: 500;
        }

        .address-card {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
        }

        .address-info {
            flex: 1;
        }

        .address-name-phone {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 8px;
        }

        .recipient-name {
            color: white;
            font-size: 14px;
            font-weight: 500;
        }

        .recipient-divider {
            color: rgba(255, 255, 255, 0.3);
        }

        .recipient-phone {
            color: rgba(255, 255, 255, 0.7);
            font-size: 14px;
        }

        .address-detail {
            color: rgba(255, 255, 255, 0.6);
            font-size: 13px;
            line-height: 1.5;
            margin: 0;
        }

        .address-edit-btn {
            color: var(--secondary);
            background: none;
            border: none;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            flex-shrink: 0;
        }

        .address-edit-btn:hover {
            text-decoration: underline;
        }

        /* Delivery Methods Section */
        .delivery-methods-section {
            margin-top: 40px;
        }

        .delivery-method-card {
            display: flex;
            align-items: center;
            padding: 16px 20px;
            border: 1px solid #D9D9D9;
            border-radius: 8px;
            background: transparent;
            margin-bottom: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .delivery-method-card:hover {
            background: rgba(255, 255, 255, 0.02);
        }

        .delivery-method-card.selected {
            border-color: var(--secondary);
            background: rgba(202, 120, 66, 0.1);
        }

        .delivery-radio {
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255, 255, 255, 0.4);
            border-radius: 50%;
            background: transparent;
            cursor: pointer;
            appearance: none;
            -webkit-appearance: none;
            position: relative;
            transition: all 0.2s ease;
            margin-right: 16px;
            flex-shrink: 0;
        }

        .delivery-radio:checked {
            border-color: var(--secondary);
        }

        .delivery-radio:checked::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 10px;
            height: 10px;
            background-color: var(--secondary);
            border-radius: 50%;
        }

        .delivery-method-name {
            color: white;
            font-size: 14px;
            font-weight: 500;
        }

        /* Address Modal */
        .address-modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2000;
            overflow-y: auto;
            padding: 20px 0;
        }

        .address-modal-content {
            background: #2A1B14;
            border-radius: 12px;
            width: 100%;
            max-width: 500px;
            margin: auto 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            max-height: 90vh;
            overflow-y: auto;
        }

        .address-modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 24px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            position: sticky;
            top: 0;
            background: #2A1B14;
            z-index: 1;
        }

        .address-modal-header h3 {
            color: white;
            font-size: 18px;
            font-weight: 600;
            margin: 0;
        }

        .modal-close-btn {
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.6);
            font-size: 24px;
            cursor: pointer;
            transition: color 0.2s ease;
        }

        .modal-close-btn:hover {
            color: white;
        }

        .address-modal-body {
            padding: 24px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            color: rgba(255, 255, 255, 0.7);
            font-size: 14px;
            margin-bottom: 8px;
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            color: white;
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s ease;
        }

        .form-input:focus {
            border-color: var(--secondary);
        }

        .form-textarea {
            width: 100%;
            padding: 12px 16px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            color: white;
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s ease;
            resize: vertical;
            min-height: 100px;
            font-family: 'Poppins', sans-serif;
        }

        .form-textarea:focus {
            border-color: var(--secondary);
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 24px;
        }

        .btn-cancel {
            padding: 12px 24px;
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            color: white;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-cancel:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .btn-save {
            padding: 12px 24px;
            background: var(--secondary);
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-save:hover {
            background: #d4864c;
        }

        /* Form Row for side-by-side inputs */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .form-row .form-group {
            margin-bottom: 20px;
        }

        /* Form Select styling */
        .form-select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='rgba(255,255,255,0.6)' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 36px;
            cursor: pointer;
        }

        .form-select option {
            background: #2a1b14;
            color: white;
        }

        /* Optional label */
        .optional-label {
            color: rgba(255, 255, 255, 0.4);
            font-size: 12px;
            font-weight: 400;
        }

        /* Address Label Options */
        .address-label-options {
            display: flex;
            gap: 12px;
        }

        .label-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            color: rgba(255, 255, 255, 0.7);
            font-size: 13px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .label-btn:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.3);
        }

        .label-btn.active {
            background: rgba(202, 120, 66, 0.2);
            border-color: var(--secondary);
            color: var(--secondary);
        }

        .label-btn.active svg {
            stroke: var(--secondary);
        }

        .label-btn svg {
            stroke: rgba(255, 255, 255, 0.6);
        }

        /* Responsive */
        @media (max-width: 992px) {
            .checkout-content {
                grid-template-columns: 1fr;
            }

            .order-summary-section {
                order: -1;
                position: relative;
                top: 0;
            }

            /* Price: Middle Row Left (Row 2) - Orange */
            .order-item-price {
                grid-column: 3;
                grid-row: 2;
                justify-self: start !important;
                color: #ca7842 !important;
                /* Updated color */
                font-weight: 600 !important;
                font-size: 15px !important;
                margin: 0 !important;
                text-align: left !important;
                width: auto !important;
            }
        }

        @media (max-width: 600px) {
            .checkout-page-container {
                padding: 20px 12px;
            }

            .order-item-card {
                display: grid;
                grid-template-columns: auto 70px 1fr;
                grid-template-rows: auto auto;
                gap: 12px;
                padding: 12px;
                align-items: start;
            }

            /* Checkbox - Column 1, Row 1-2 span */
            .order-item-checkbox {
                grid-column: 1;
                grid-row: 1 / span 2;
                align-self: start;
                margin-top: 4px;
            }

            /* Image - Column 2, Row 1-2 span */
            .order-item-image {
                grid-column: 2;
                grid-row: 1 / span 2;
                width: 70px;
                height: 70px;
            }

            /* Product Info - Unwrap untuk grid */
            .order-item-info {
                display: contents;
            }

            .order-item-details {
                grid-column: 3;
                grid-row: 1;
                margin-right: 0;
                width: 100%;
            }

            /* Name - Column 3, Row 1 */
            .order-item-name {
                font-size: 13px;
                line-height: 1.4;
                margin-bottom: 4px;
                color: white;
                font-weight: 500;
            }

            /* Price - Column 3, Row 2 (kanan, sejajar dengan qty) */
            .order-item-price {
                grid-column: 3;
                grid-row: 2;
                font-size: 15px;
                color: #CA7842;
                font-weight: 600;
                text-align: right;
                justify-self: end;
                align-self: center;
            }

            /* Actions (Qty) - Column 3, Row 2 (kiri, sejajar dengan price) */
            .order-item-actions {
                grid-column: 3;
                grid-row: 2;
                display: flex;
                align-items: center;
                justify-content: flex-start;
                width: 100%;
                margin-top: 8px;
            }

            .order-item-qty {
                display: flex;
                align-items: center;
                gap: 8px;
                font-size: 13px;
                color: rgba(255, 255, 255, 0.9);
            }

            /* Checkout Quantity Controls - Mobile */
            .checkout-quantity-controls {
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .checkout-qty-btn {
                width: 24px;
                height: 24px;
                font-size: 14px;
                border-radius: 4px;
                background-color: rgba(255, 255, 255, 0.1);
                color: rgba(255, 255, 255, 0.8);
                border: 1px solid rgba(255, 255, 255, 0.2);
            }

            .checkout-qty-value {
                font-size: 13px;
                min-width: 18px;
            }

            .checkout-qty-minus {
                background-color: rgba(255, 255, 255, 0.08);
            }

            .checkout-qty-plus {
                background-color: rgba(202, 120, 66, 0.3);
                color: #CA7842;
                border-color: rgba(202, 120, 66, 0.4);
            }

            .order-item-subtotal {
                display: none;
            }

            .order-item-delete {
                font-size: 11px;
                color: rgba(255, 255, 255, 0.5);
                background: none;
                border: none;
                cursor: pointer;
                padding: 4px 8px;
                margin-left: auto;
            }

            .order-item-delete:hover {
                color: #e74c3c;
            }

            .order-item-quantity {
                margin-top: 0;
            }
        }

        /* Footer adjustments */
        .footer-container {
            margin-top: 60px;
        }
    </style>
    <style>
        /* Mobile Sticky Footer Styles */
        @media (max-width: 768px) {
            .checkout-content {
                display: block;
                /* Stack content vertically */
                padding-bottom: 250px;
                /* Ensure content isn't hidden behind fixed footer */
            }

            .order-summary-section {
                position: fixed;
                bottom: 0;
                left: 0;
                top: auto;
                /* Ensure it doesn't stretch to top */
                width: 100%;
                z-index: 1000;
                background: #1a1410;
                /* Match theme background or slightly lighter */
                box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.4);
                border-top: 1px solid rgba(255, 244, 214, 0.1);
                animation: slideUp 0.3s ease-out;
            }

            .order-summary-card {
                background: linear-gradient(145deg, #2A1B14, #221510);
                border: none;
                border-radius: 20px 20px 0 0;
                /* Rounded top corners only */
                padding: 20px;
                margin-bottom: 0;
            }

            /* Hide 'Jumlah Pesanan' title only in mobile order summary footer */
            .order-summary-section>.section-title {
                display: none;
            }

            /* Show section titles for payment and delivery methods */
            .payment-methods-section .section-title,
            .delivery-methods-section .section-title {
                display: block;
                font-size: 16px;
                font-weight: 600;
                margin-bottom: 16px;
            }

            /* Ensure the order items take full width */
            .order-items-section-wrapper {
                width: 100%;
                margin-bottom: 20px;
            }
        }

        @keyframes slideUp {
            from {
                transform: translateY(100%);
            }

            to {
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>
    <!-- Checkout Navbar -->
    @include('components.customer.checkout-navbar')

    <!-- Main Content -->
    <!-- Spacer for fixed navbar -->
    <div class="navbar-spacer"></div>

    <!-- Back Button (Below Navbar) -->
    <div class="back-button-container">
        <a href="{{ route('cart.index') }}" class="back-to-cart-btn">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M19 12H5M12 19l-7-7 7-7" />
            </svg>
            <span>Back</span>
        </a>
    </div>

    <main>
        {{ $slot }}
    </main>

    <!-- Footer -->
    @include('components.customer.footer')
</body>

</html>