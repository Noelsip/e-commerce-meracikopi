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
            .note-wrapper:hover, .note-wrapper:focus-within {
                border-color: #fff !important;
                background: rgba(255,255,255,0.1) !important;
            }
        </style>

        <div style="max-width: 1440px; margin: 0 auto; padding: 0 40px; position: relative; z-index: 10;">
            <!-- Header / Breadcrumb Area -->
            <div style="display: flex; align-items: center; margin-bottom: 40px;">
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
        <div style="
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
            <div style="
                position: relative;
                z-index: 2;
                width: 100%;
                height: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 60px;
            "
            x-data="{
                quantity: 1,
                note: '',
                loading: false,
                addToCart() {
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
                        if(newToken) {
                            localStorage.setItem('guest_token', newToken);
                        }

                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        alert('Berhasil menambahkan ke keranjang! 🛒');
                        this.quantity = 1;
                        this.note = '';
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Gagal menambahkan ke keranjang. Silakan coba lagi.');
                    })
                    .finally(() => {
                        this.loading = false;
                    });
                }
            }">
                
                <!-- Product Image -->
                <div
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
                <div style="width: 250px;">
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

                    <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 16px;">
                        <button @click="quantity > 1 ? quantity-- : null"
                            style="width: 32px; height: 32px; border-radius: 50%; background: #F0F2BD; border: none; font-size: 20px; font-weight: 600; color: #2a1b14; cursor: pointer; display: flex; align-items: center; justify-content: center;">-</button>
                        
                        <input type="number" x-model.number="quantity" min="1" 
                            style="width: 50px; background: transparent; border: none; font-size: 18px; color: #fff; font-weight: 500; text-align: center; outline: none; -moz-appearance: textfield;" 
                            class="no-spinners">
                        
                        <button @click="quantity++"
                            style="width: 32px; height: 32px; border-radius: 50%; background: #F0F2BD; border: none; font-size: 20px; font-weight: 600; color: #2a1b14; cursor: pointer; display: flex; align-items: center; justify-content: center;">+</button>
                    </div>

                    <button 
                        @click="addToCart"
                        :disabled="loading"
                        style="
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
        <div style="max-width: 1440px; margin: 0 auto; padding: 40px; position: relative; z-index: 10;">
             <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 80px;">
                <!-- Description -->
                <div>
                    <h3 style="font-size: 18px; color: #fff; font-weight: 600; margin-bottom: 16px; border-bottom: 1px solid rgba(255,255,255,0.2); padding-bottom: 8px;">
                        Deskripsi Produk
                    </h3>
                    <p style="color: #aaa; font-size: 14px; line-height: 1.6;">
                        {{ $menu->description ?? 'Deskripsi produk belum tersedia.' }}
                    </p>
                </div>

                <!-- Notes -->
                <div>
                    <h3 style="font-size: 18px; color: #fff; font-weight: 600; margin-bottom: 16px; border-bottom: 1px solid rgba(255,255,255,0.2); padding-bottom: 8px;">
                        Catatan
                    </h3>
                    <div class="note-wrapper" style="background: rgba(255,255,255,0.05); border-radius: 12px; padding: 16px;">
                        <textarea 
                            x-model="note"
                            placeholder="Catatan opsional..." 
                            style="width: 100%; background: transparent; border: none; color: #fff; font-size: 14px; outline: none; resize: none; font-family: 'Inter', sans-serif;"
                            rows="6"
                        ></textarea>
                    </div>
                </div>
             </div>
        </div>
    </div>
</x-customer.layout>