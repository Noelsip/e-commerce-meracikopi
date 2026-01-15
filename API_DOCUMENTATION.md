# API Documentation - E-Commerce MeracikOpi

## Updated Endpoints

### 🆕 Admin - Menu Management dengan Diskon

#### 1. Create Menu dengan Diskon
**POST** `/api/admin/menus`

**Headers:**
```json
{
  "Authorization": "Bearer {admin_token}",
  "Content-Type": "application/json"
}
```

**Request Body:**
```json
{
  "name": "Kopi Susu Gula Aren",
  "category": "drink",
  "description": "Kopi susu dengan gula aren asli",
  "price": 25000,
  "discount_percentage": 10,
  "discount_price": 0,
  "is_available": true
}
```

**Response 201:**
```json
{
  "message": "Menu created successfully",
  "data": {
    "id": 1,
    "name": "Kopi Susu Gula Aren",
    "category": "drink",
    "price": 25000,
    "discount_percentage": 10,
    "discount_price": 0,
    "final_price": 22500,
    "has_discount": true,
    "is_available": true
  }
}
```

**Contoh Diskon:**

1. **Diskon Persen:**
```json
{
  "price": 50000,
  "discount_percentage": 15,
  "discount_price": 0
}
// Final price: 42,500 (hemat 7,500)
```

2. **Diskon Nominal:**
```json
{
  "price": 50000,
  "discount_percentage": 0,
  "discount_price": 10000
}
// Final price: 40,000 (hemat 10,000)
```

3. **Keduanya diisi (prioritas discount_price):**
```json
{
  "price": 50000,
  "discount_percentage": 20,
  "discount_price": 15000
}
// Final price: 35,000 (hemat 15,000)
// discount_price lebih prioritas!
```

---

#### 2. Update Menu dengan Diskon
**PUT** `/api/admin/menus/{id}`

**Request Body:**
```json
{
  "name": "Kopi Susu Gula Aren",
  "category": "drink",
  "description": "Kopi susu dengan gula aren asli",
  "price": 25000,
  "discount_percentage": 20,
  "discount_price": 0,
  "is_available": true
}
```

---

### 🆕 Customer - Menu dengan Diskon

#### 1. Get All Menus (dengan info diskon)
**GET** `/api/customer/catalogs`

**Response 200:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Kopi Susu Gula Aren",
      "category": "drink",
      "description": "Kopi susu dengan gula aren asli",
      "price": 25000,
      "discount_percentage": 10,
      "discount_price": 0,
      "final_price": 22500,
      "has_discount": true,
      "image": "/storage/menus/xxx.jpg",
      "is_available": true
    },
    {
      "id": 2,
      "name": "Nasi Goreng Special",
      "category": "food",
      "description": "Nasi goreng dengan telur dan ayam",
      "price": 35000,
      "discount_percentage": 0,
      "discount_price": 5000,
      "final_price": 30000,
      "has_discount": true,
      "image": "/storage/menus/yyy.jpg",
      "is_available": true
    }
  ]
}
```

---

#### 2. Get Menu Detail
**GET** `/api/customer/catalogs/{id}`

**Response 200:**
```json
{
  "data": {
    "id": 1,
    "name": "Kopi Susu Gula Aren",
    "category": "drink",
    "description": "Kopi susu dengan gula aren asli",
    "price": 25000,
    "discount_percentage": 10,
    "discount_price": 0,
    "final_price": 22500,
    "discount_amount": 2500,
    "has_discount": true,
    "image": "/storage/menus/xxx.jpg",
    "is_available": true
  }
}
```

**Field Explanation:**
- `price`: Harga normal/asli
- `discount_percentage`: Diskon dalam persen (0-100)
- `discount_price`: Diskon dalam nominal rupiah
- `final_price`: Harga setelah diskon (yang dibayar customer)
- `discount_amount`: Jumlah hemat (price - final_price)
- `has_discount`: Boolean, true jika ada diskon

---

### 🆕 Customer - Delivery Fee Calculation

#### 1. Calculate Delivery Fee
**POST** `/api/customer/delivery/calculate-fee`

**Request Body:**
```json
{
  "destination": {
    "latitude": -6.175110,
    "longitude": 106.865036,
    "address": "Jl. Gatot Subroto, Jakarta Selatan"
  }
}
```

**Response 200:**
```json
{
  "data": {
    "distance": 8.5,
    "distance_unit": "km",
    "delivery_fee": 15000,
    "estimated_time": "40 minutes",
    "origin": {
      "latitude": -6.200000,
      "longitude": 106.816666,
      "address": "MeracikOpi Cafe, Jl. Sudirman Jakarta"
    },
    "destination": {
      "latitude": -6.175110,
      "longitude": 106.865036,
      "address": "Jl. Gatot Subroto, Jakarta Selatan"
    }
  }
}
```

**Delivery Fee Calculation Logic:**
- 0-5 km: Rp 10.000
- 5-10 km: Rp 15.000
- 10-15 km: Rp 20.000
- >15 km: Rp 25.000 + (Rp 2.000 per km tambahan)

---

#### 2. Get Delivery Options
**GET** `/api/customer/delivery/options`

**Response 200:**
```json
{
  "data": [
    {
      "id": "standard",
      "name": "Standard Delivery",
      "description": "Delivery dalam 45-60 menit",
      "min_time": 45,
      "max_time": 60
    },
    {
      "id": "express",
      "name": "Express Delivery",
      "description": "Delivery dalam 30-45 menit",
      "min_time": 30,
      "max_time": 45,
      "additional_fee": 5000
    }
  ]
}
```

---

### 🆕 Customer - Checkout dengan Diskon & Delivery Fee

#### 1. Create Order (Checkout)
**POST** `/api/customer/orders`

**Headers:**
```json
{
  "X-Guest-Token": "550e8400-e29b-41d4-a716-446655440000"
}
```

**Request Body:**
```json
{
  "customer_name": "John Doe",
  "customer_phone": "081234567890",
  "order_type": "delivery",
  "table_id": null,
  "delivery_fee": 15000,
  "notes": "Jangan pakai bawang putih",
  "address": {
    "receiver_name": "John Doe",
    "phone": "081234567890",
    "full_address": "Jl. Gatot Subroto No. 123",
    "city": "Jakarta Selatan",
    "postal_code": "12190",
    "notes": "Rumah warna hijau"
  }
}
```

**Response 201:**
```json
{
  "message": "Order created successfully",
  "data": {
    "id": 1,
    "order_type": "delivery",
    "status": "pending_payment",
    "total_price": 67500,
    "delivery_fee": 15000,
    "discount_amount": 7500,
    "final_price": 75000
  }
}
```

**Calculation Example:**

Cart items:
1. Kopi Susu (price: 25000, discount: 10%) x 2 = 45,000 (hemat 5,000)
2. Nasi Goreng (price: 35000, discount: 5000) x 1 = 30,000 (hemat 5,000)

```
Subtotal (setelah diskon):     Rp 75,000
Total Diskon:                  Rp 10,000
Delivery Fee:                  Rp 15,000
-------------------------------------------
TOTAL BAYAR (final_price):     Rp 90,000
```

---

#### 2. Get Order Detail
**GET** `/api/customer/orders/{id}`

**Response 200:**
```json
{
  "data": {
    "id": 1,
    "order_type": "delivery",
    "status": "pending_payment",
    "total_price": 75000,
    "delivery_fee": 15000,
    "discount_amount": 10000,
    "final_price": 90000,
    "customer_name": "John Doe",
    "customer_phone": "081234567890",
    "notes": "Jangan pakai bawang putih",
    "user": null,
    "table": null,
    "items": [
      {
        "menu_id": 1,
        "menu_name": "Kopi Susu Gula Aren",
        "quantity": 2,
        "price": 22500
      },
      {
        "menu_id": 2,
        "menu_name": "Nasi Goreng Special",
        "quantity": 1,
        "price": 30000
      }
    ],
    "address": {
      "receiver_name": "John Doe",
      "phone": "081234567890",
      "full_address": "Jl. Gatot Subroto No. 123",
      "city": "Jakarta Selatan",
      "postal_code": "12190",
      "notes": "Rumah warna hijau"
    },
    "delivery": null,
    "payments": [],
    "logs": [
      {
        "status": "pending_payment",
        "note": "Order created",
        "created_at": "2026-01-15T10:30:00Z"
      }
    ]
  }
}
```

---

## 📊 Price Calculation Logic

### Menu Model (Menus.php)
```php
// Harga final dengan prioritas:
// 1. discount_price (nominal)
// 2. discount_percentage (persen)
// 3. price (normal)

public function getFinalPriceAttribute()
{
    if ($this->discount_price > 0) {
        return max(0, $this->price - $this->discount_price);
    }
    
    if ($this->discount_percentage > 0) {
        $discountAmount = ($this->price * $this->discount_percentage) / 100;
        return max(0, $this->price - $discountAmount);
    }
    
    return $this->price;
}
```

### Order Model (Orders.php)
```php
// Formula final price:
// final_price = total_price + delivery_fee - discount_amount

public function calculateFinalPrice()
{
    $this->final_price = $this->total_price + $this->delivery_fee - $this->discount_amount;
    $this->final_price = max(0, $this->final_price); // Tidak boleh negatif
}
```

---

## 🧪 Testing Scenarios

### Test 1: Menu dengan Diskon Persen
1. Admin create menu dengan `discount_percentage: 20`
2. Customer lihat catalog, cek `final_price` sudah diskon 20%
3. Customer add to cart
4. Checkout, cek `discount_amount` terisi otomatis

### Test 2: Menu dengan Diskon Nominal
1. Admin create menu dengan `discount_price: 10000`
2. Customer checkout, hemat Rp 10.000

### Test 3: Order Delivery dengan Ongkir
1. Customer calculate delivery fee dulu
2. Checkout dengan order_type: delivery
3. Include delivery_fee di request body
4. Cek `final_price = total_price + delivery_fee - discount_amount`

### Test 4: Order Dine In (tanpa ongkir)
1. Checkout dengan order_type: dine_in
2. delivery_fee otomatis 0
3. final_price = total_price - discount_amount

---

## 📝 Database Schema

### Menus Table
```sql
- id
- name
- category
- description
- price (harga normal)
- discount_percentage (0-100)
- discount_price (nominal)
- image_path
- is_available
- deleted_at
```

### Orders Table
```sql
- id
- guest_token
- user_id
- order_type (dine_in, take_away, delivery)
- table_id
- customer_name
- customer_phone
- status
- total_price (subtotal setelah diskon)
- delivery_fee (ongkir)
- discount_amount (total diskon)
- final_price (yang dibayar)
- notes
```

---

## 🔌 Third Party Integration

### Delivery Service (Future Implementation)

File: `app/Services/DeliveryService.php`

**Methods:**
- `calculateDeliveryFee()` - Integrasi dengan GoSend, Grab, JNE API
- `requestDriver()` - Request pickup driver
- `trackDelivery()` - Track delivery status

**Environment Variables (.env):**
```env
DELIVERY_API_URL=https://api.delivery-service.com
DELIVERY_API_KEY=your_api_key_here
```

**Usage:**
```php
use App\Services\DeliveryService;

$deliveryService = new DeliveryService();
$result = $deliveryService->calculateDeliveryFee($origin, $destination);
```

---

## ✅ Checklist Implementation

- [x] Migration untuk kolom diskon di menus
- [x] Migration untuk delivery_fee, discount_amount, final_price di orders
- [x] Model Menus dengan method `getFinalPriceAttribute()`
- [x] Model Orders dengan method `calculateFinalPrice()`
- [x] Admin controller support diskon di CRUD menu
- [x] Customer API return info diskon
- [x] Logic checkout include diskon otomatis
- [x] Logic checkout include delivery fee
- [x] DeliveryService untuk third party integration
- [x] DeliveryController untuk calculate fee
- [x] API routes untuk delivery endpoints
- [x] Documentation lengkap

---

## 🚀 Next Steps

1. **Testing Manual:**
   - Test semua endpoint dengan Postman
   - Verify perhitungan diskon
   - Verify perhitungan delivery fee

2. **Integration:**
   - Integrate dengan real delivery service API
   - Update DeliveryService dengan credentials

3. **UI (Optional):**
   - Tambah form diskon di admin panel
   - Show badge "DISKON" di catalog
   - Show price before/after discount

4. **Additional Features:**
   - Voucher/promo code
   - Loyalty points
   - Bulk discount
   - Flash sale
