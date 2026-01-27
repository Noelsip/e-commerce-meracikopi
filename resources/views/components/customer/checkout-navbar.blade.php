<style>
    /* Checkout Navbar Styling */
    .checkout-navbar {
        background-color: #2A1B14;
        width: 100%;
        height: 115px;
        box-shadow: 0 7px 4px rgba(0, 0, 0, 0.25);
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 100px;
        position: relative;
    }

    .checkout-navbar-container {
        background-color: #2A1B14;
        padding: 0;
        width: 100%;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1000;
    }

    /* Back Button Container (Below Navbar, aligned with content) */
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
        color: var(--secondary);
    }

    .back-to-cart-btn svg {
        flex-shrink: 0;
    }

    /* Logo and Title */
    .checkout-logo-section {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-shrink: 0;
    }

    .checkout-logo-circle {
        width: 45px;
        height: 45px;
        background: linear-gradient(145deg, #F0F2BD, #d4d6a3);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .checkout-logo-text {
        color: white;
        font-weight: 600;
        font-size: 20px;
        font-family: 'Poppins', sans-serif;
    }

    .checkout-divider {
        width: 2px;
        height: 30px;
        background-color: rgba(255, 255, 255, 0.5);
        margin: 0 16px;
    }

    .checkout-title {
        color: #CA7842;
        font-weight: 600;
        font-size: 20px;
        font-family: 'Poppins', sans-serif;
    }

    /* Order Type Dropdown Section */
    .order-type-section {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-left: auto;
    }

    .order-type-label {
        color: rgba(255, 255, 255, 0.7);
        font-size: 14px;
        font-family: 'Poppins', sans-serif;
    }

    .order-type-dropdown {
        position: relative;
    }

    .order-type-select {
        appearance: none;
        -webkit-appearance: none;
        background-color: transparent;
        border: none;
        color: white;
        font-size: 14px;
        font-weight: 500;
        font-family: 'Poppins', sans-serif;
        padding: 8px 32px 8px 12px;
        cursor: pointer;
        outline: none;
        min-width: 120px;
    }

    .order-type-select option {
        background-color: #2A1B14;
        color: white;
        padding: 10px;
    }

    .dropdown-arrow {
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
        color: white;
    }

    /* Table Info (for Dine In) */
    .table-info {
        display: flex;
        align-items: center;
        gap: 8px;
        color: white;
        font-size: 14px;
        font-family: 'Poppins', sans-serif;
    }

    .table-number {
        color: #CA7842;
        font-weight: 600;
    }

    /* Table warning message */
    .table-warning {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        margin-top: 4px;
        padding: 6px 10px;
        background-color: rgba(255, 107, 107, 0.15);
        border: 1px solid rgba(255, 107, 107, 0.3);
        border-radius: 6px;
        color: #ff6b6b;
        font-size: 11px;
        white-space: nowrap;
        display: none;
        z-index: 100;
    }

    .table-warning.show {
        display: block;
    }

    /* Mobile responsiveness */
    @media (max-width: 768px) {
        .checkout-navbar {
            height: auto;
            min-height: 80px;
            padding: 16px 20px;
            flex-wrap: wrap;
            justify-content: center;
            gap: 16px;
        }

        .order-type-section {
            width: 100%;
            justify-content: center;
        }

        .checkout-logo-text {
            font-size: 16px;
        }

        .checkout-title {
            font-size: 16px;
        }
    }
</style>

<!-- Back Button (Outside Navbar) -->
<div class="back-button-container">
    <a href="{{ route('cart.index') }}" class="back-to-cart-btn">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M19 12H5M12 19l-7-7 7-7" />
        </svg>
        <span>Back</span>
    </a>
</div>

<!-- Checkout Navbar Container -->
<div class="checkout-navbar-container">
    <div class="checkout-navbar">
        <!-- Left: Logo and Title -->
        <div class="checkout-logo-section">
            <a href="/" style="display: flex; align-items: center; gap: 12px; text-decoration: none;">
                <img src="{{ asset('meracik-logo1.png') }}" alt="Meracikopi Logo" style="width: 45px; height: 45px; object-fit: contain;">
                <span class="checkout-logo-text">Meracikopi</span>
            </a>
            <div class="checkout-divider"></div>
            <span class="checkout-title">Ringkasan Pesanan</span>
        </div>

        <!-- Right: Order Type and Table Dropdown -->
        <div class="order-type-section" style="display: flex; gap: 12px; align-items: center;">
            <!-- Order Type Dropdown -->
            <div class="order-type-dropdown">
                <select class="order-type-select" id="orderTypeNavbar" onchange="handleOrderTypeChange(this.value)">
                    <option value="takeaway">Takeaway</option>
                    <option value="dine_in">Dine In</option>
                    <option value="delivery">Delivery</option>
                </select>
                <svg class="dropdown-arrow" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <path d="M6 9l6 6 6-6" />
                </svg>
            </div>

            <!-- Table Selector (only visible for Dine In) -->
            <div class="order-type-dropdown" id="tableSelector" style="display: none; position: relative;">
                <select class="order-type-select" id="tableSelect" onchange="handleTableChange(this.value)">
                    <option value="">Pilih Meja...</option>
                </select>
                <svg class="dropdown-arrow" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <path d="M6 9l6 6 6-6" />
                </svg>
                <!-- Warning message -->
                <div class="table-warning" id="tableWarning">
                    ⚠️ Pilih meja untuk melanjutkan
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let availableTables = [];
    let selectedTableId = null;

    // Load available tables on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadAvailableTables();
    });

    // Fetch available tables from backend
    function loadAvailableTables() {
        const tableSelect = document.getElementById('tableSelect');
        tableSelect.innerHTML = '<option value="">Loading tables...</option>';
        tableSelect.disabled = true;

        fetch('/api/customer/tables?status=available')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Tables API response:', data);
                
                if (data.data && Array.isArray(data.data)) {
                    availableTables = data.data;
                    populateTableDropdown();
                    
                    // Load saved table selection if exists
                    const savedTableId = localStorage.getItem('selected_table_id');
                    if (savedTableId && availableTables.find(t => t.id == savedTableId)) {
                        tableSelect.value = savedTableId;
                        selectedTableId = savedTableId;
                    }
                } else {
                    console.warn('No tables data found in response');
                    showNoTablesAvailable();
                }
            })
            .catch(error => {
                console.error('Error loading tables:', error);
                showTableLoadError();
            });
    }

    // Populate table dropdown with fetched data
    function populateTableDropdown() {
        const tableSelect = document.getElementById('tableSelect');
        
        if (!availableTables || availableTables.length === 0) {
            showNoTablesAvailable();
            return;
        }

        tableSelect.disabled = false;
        tableSelect.innerHTML = '<option value="">Pilih Meja...</option>';
        
        availableTables.forEach(table => {
            const option = document.createElement('option');
            option.value = table.id;
            option.textContent = `Meja ${table.table_number} (Kapasitas: ${table.capacity} orang)`;
            tableSelect.appendChild(option);
        });

        console.log(`Loaded ${availableTables.length} available tables`);
    }

    // Show no tables available message
    function showNoTablesAvailable() {
        const tableSelect = document.getElementById('tableSelect');
        tableSelect.innerHTML = '<option value="">Tidak ada meja tersedia</option>';
        tableSelect.disabled = true;
    }

    // Show error loading tables
    function showTableLoadError() {
        const tableSelect = document.getElementById('tableSelect');
        tableSelect.innerHTML = '<option value="">Error loading tables</option>';
        tableSelect.disabled = true;
    }

    // Handle order type change
    function handleOrderTypeChange(value) {
        const tableSelector = document.getElementById('tableSelector');
        const tableSelect = document.getElementById('tableSelect');
        const tableWarning = document.getElementById('tableWarning');
        
        // Show/hide table selector based on order type
        if (value === 'dine_in') {
            tableSelector.style.display = 'block';
            
            // Reload tables if not loaded yet
            if (availableTables.length === 0) {
                console.log('Loading tables for Dine In...');
                loadAvailableTables();
            }
            
            // Restore saved selection if exists
            const savedTableId = localStorage.getItem('selected_table_id');
            if (savedTableId && availableTables.find(t => t.id == savedTableId)) {
                tableSelect.value = savedTableId;
                selectedTableId = savedTableId;
                if (tableWarning) tableWarning.classList.remove('show');
            } else {
                // Show warning if no table selected
                if (tableWarning) tableWarning.classList.add('show');
            }
        } else {
            tableSelector.style.display = 'none';
            tableSelect.value = '';
            selectedTableId = null;
            if (tableWarning) tableWarning.classList.remove('show');
            
            // Clear localStorage when switching away from dine in
            localStorage.removeItem('selected_table_id');
            localStorage.removeItem('selected_table_number');
            localStorage.removeItem('selected_table_capacity');
        }

        // Call the original sync function
        syncOrderType(value);
    }

    // Handle table selection
    function handleTableChange(tableId) {
        selectedTableId = tableId;
        const tableWarning = document.getElementById('tableWarning');
        
        if (tableId) {
            const selectedTable = availableTables.find(t => t.id == tableId);
            if (selectedTable) {
                console.log('✓ Table selected:', {
                    id: selectedTable.id,
                    number: selectedTable.table_number,
                    capacity: selectedTable.capacity,
                    status: selectedTable.status
                });
                
                // Store table info for checkout process
                localStorage.setItem('selected_table_id', tableId);
                localStorage.setItem('selected_table_number', selectedTable.table_number);
                localStorage.setItem('selected_table_capacity', selectedTable.capacity);
                
                // Hide warning
                if (tableWarning) {
                    tableWarning.classList.remove('show');
                }
                
                // Trigger event for other components if needed
                window.dispatchEvent(new CustomEvent('tableSelected', {
                    detail: selectedTable
                }));
            }
        } else {
            console.log('✗ Table selection cleared');
            localStorage.removeItem('selected_table_id');
            localStorage.removeItem('selected_table_number');
            localStorage.removeItem('selected_table_capacity');
            
            // Show warning
            if (tableWarning) {
                tableWarning.classList.add('show');
            }
            
            window.dispatchEvent(new CustomEvent('tableCleared'));
        }
    }

    function syncOrderType(value) {
        // Update the tab display text
        const orderTypeDisplay = document.getElementById('orderTypeDisplay');
        if (orderTypeDisplay) {
            let displayText = '';
            switch (value) {
                case 'dine_in':
                    displayText = 'Dine In';
                    break;
                case 'takeaway':
                    displayText = 'Takeaway';
                    break;
                case 'delivery':
                    displayText = 'Delivery';
                    break;
            }
            orderTypeDisplay.textContent = displayText;
        }

        // Toggle delivery section visibility
        if (typeof window.toggleDeliverySection === 'function') {
            window.toggleDeliverySection(value === 'delivery');
        }

        // Trigger checkout button validation
        if (typeof window.validateCheckoutButton === 'function') {
            window.validateCheckoutButton();
        }

        console.log('Order type changed to:', value);
    }
</script>