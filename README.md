# E-Commerce Meracikopi

E‑Commerce Meracikopi adalah sistem pemesanan dan pengelolaan pesanan kopi (dine in, take away, delivery).

---

## Kontrak API

### 1. Base URL

| Tipe API        | Base URL   | Deskripsi                            |
|----------------|-----------|--------------------------------------|
| Public Customer | `/api`    | Digunakan oleh customer aplikasi    |
| Admin           | `/admin`  | Digunakan oleh admin/backoffice     |
| Kurir/Partner   | `/courier`| Digunakan oleh sistem kurir eksternal|

Semua request dan response menggunakan JSON.

Header umum:

```http
Content-Type: application/json
Authorization: Bearer <token>   // jika endpoint membutuhkan auth
```

---

## 2. Autentikasi

### 2.1 Admin Login

**Endpoint**

```http
POST /admin/auth/login
```

**Request Body**

```json
{
  "email": "admin@meracikopi.com",
  "password": "password"
}
```

**Response 200**

```json
{
  "message": "Login Successful",
  "token": "jwt-token",
  "user": {
    "id": 1,
    "name": "Admin",
    "email": "admin@meracikopi.com",
    "role": "admin"
  }
}
```

**Response 400/401**

```json
{
  "message": "Kesalahan kredensial"
}
```

---

### 2.2 Admin Logout

**Endpoint**

```http
POST /admin/auth/logout
```

**Auth**

- Wajib header `Authorization: Bearer <token>`

**Response 200**

```json
{
  "message": "Logout Successful"
}
```

---

### 2.3 Customer Register

**Endpoint**

```http
POST /api/auth/register
```

**Request Body**

```json
{
  "name": "Budi",
  "email": "budi@example.com",
  "password": "password"
}
```

**Response 201**

```json
{
  "message": "Register Successful",
  "user": {
    "id": 10,
    "name": "Budi",
    "email": "budi@example.com",
    "role": "customer"
  }
}
```

---

### 2.4 Customer Login

**Endpoint**

```http
POST /api/auth/login
```

**Request Body & Response**

Sama formatnya dengan Admin Login, namun `role` adalah `customer`.

---

### 2.5 Customer Logout

**Endpoint**

```http
POST /api/auth/logout
```

**Auth**

- Wajib `Bearer` token customer

**Response 200**

```json
{
  "message": "Logout Successful"
}
```

---

## 3. API Customer

### 3.1 Mengakses Katalog Menu

#### 3.1.1 List Menu (dengan Info Diskon)

**Endpoint**

```http
GET /api/customer/catalogs
```

**Headers**

```http
Content-Type: application/json
```

**Query Params (opsional)**

- `is_available` : `true|false`
- `search` : string nama menu
- `category` : `food|drink|coffee_beans`

**Response 200**

```json
{
  "data": [
    {
      "id": 1,
      "name": "Espresso",
      "category": "drink",
      "description": "Kopi espresso single shot",
      "price": 20000,
      "discount_percentage": 10,
      "discount_price": 0,
      "final_price": 18000,
      "has_discount": true,
      "image": "/images/espresso.jpg",
      "is_available": true
    },
    {
      "id": 2,
      "name": "Nasi Goreng",
      "category": "food",
      "description": "Nasi goreng special",
      "price": 35000,
      "discount_percentage": 0,
      "discount_price": 5000,
      "final_price": 30000,
      "has_discount": true,
      "image": "/images/nasi-goreng.jpg",
      "is_available": true
    }
  ]
}
```

**Field Description:**
- `price`: Harga normal/asli sebelum diskon
- `discount_percentage`: Diskon dalam persen (0-100)
- `discount_price`: Diskon dalam nominal rupiah
- `final_price`: Harga setelah diskon (yang dibayar customer)
- `has_discount`: Boolean, true jika ada diskon

**Contoh Request:**

```bash
# Get all menus
curl -X GET http://localhost:8000/api/customer/catalogs

# Filter available only
curl -X GET "http://localhost:8000/api/customer/catalogs?is_available=true"

# Search by name
curl -X GET "http://localhost:8000/api/customer/catalogs?search=kopi"

# Filter by category
curl -X GET "http://localhost:8000/api/customer/catalogs?category=drink"
```

---

#### 3.1.2 Detail Menu (dengan Info Diskon)

**Endpoint**

```http
GET /api/customer/catalogs/{id}
```

**Headers**

```http
Content-Type: application/json
```

**Response 200**

```json
{
  "data": {
    "id": 1,
    "name": "Espresso",
    "category": "drink",
    "description": "Kopi espresso single shot",
    "price": 20000,
    "discount_percentage": 10,
    "discount_price": 0,
    "final_price": 18000,
    "discount_amount": 2000,
    "has_discount": true,
    "image": "/images/espresso.jpg",
    "is_available": true
  }
}
```

**Response 404**

```json
{
  "message": "Menu not found"
}
```

**Contoh Request:**

```bash
curl -X GET http://localhost:8000/api/customer/catalogs/1
```

---

### 3.2 Cart Management

#### 3.2.1 Lihat Cart

**Endpoint**

```http
GET /api/customer/cart
```

**Headers**

```http
X-Guest-Token: <guest_token_uuid>
Content-Type: application/json
```

**Response 200**

```json
{
  "data": {
    "id": 1,
    "guest_token": "550e8400-e29b-41d4-a716-446655440000",
    "status": "active",
    "items": [
      {
        "id": 1,
        "menu_id": 1,
        "menu_name": "Espresso",
        "quantity": 2,
        "price": 18000,
        "note": "Less sugar",
        "subtotal": 36000
      }
    ],
    "total": 36000
  }
}
```

---

#### 3.2.2 Tambah Item ke Cart

**Endpoint**

```http
POST /api/customer/cart/items
```

**Headers**

```http
X-Guest-Token: <guest_token_uuid>
Content-Type: application/json
```

**Request Body**

```json
{
  "menu_id": 1,
  "quantity": 2,
  "note": "Less sugar"
}
```

**Response 201**

```json
{
  "message": "Item added to cart",
  "data": {
    "id": 1,
    "menu_id": 1,
    "quantity": 2,
    "note": "Less sugar"
  }
}
```

---

#### 3.2.3 Update Item di Cart

**Endpoint**

```http
PUT /api/customer/cart/items/{id}
```

**Headers**

```http
X-Guest-Token: <guest_token_uuid>
Content-Type: application/json
```

**Request Body**

```json
{
  "quantity": 3,
  "note": "Extra hot"
}
```

**Response 200**

```json
{
  "message": "Cart item updated",
  "data": {
    "id": 1,
    "quantity": 3,
    "note": "Extra hot"
  }
}
```

---

#### 3.2.4 Hapus Item dari Cart

**Endpoint**

```http
DELETE /api/customer/cart/items/{id}
```

**Headers**

```http
X-Guest-Token: <guest_token_uuid>
Content-Type: application/json
```

**Response 200**

```json
{
  "message": "Item removed from cart"
}
```

---

### 3.3 Delivery Fee Calculation

#### 3.3.1 Calculate Delivery Fee

**Endpoint**

```http
POST /api/customer/delivery/calculate-fee
```

**Headers**

```http
Content-Type: application/json
```

**Request Body**

```json
{
  "destination": {
    "latitude": -6.175110,
    "longitude": 106.865036,
    "address": "Jl. Gatot Subroto No. 123, Jakarta Selatan"
  }
}
```

**Response 200**

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
      "address": "Jl. Gatot Subroto No. 123, Jakarta Selatan"
    }
  }
}
```

**Delivery Fee Calculation:**
- 0-5 km: Rp 10.000
- 5-10 km: Rp 15.000
- 10-15 km: Rp 20.000
- >15 km: Rp 25.000 + (Rp 2.000 per km tambahan)

**Contoh Request:**

```bash
curl -X POST http://localhost:8000/api/customer/delivery/calculate-fee \
  -H "Content-Type: application/json" \
  -d '{
    "destination": {
      "latitude": -6.175110,
      "longitude": 106.865036,
      "address": "Jl. Gatot Subroto No. 123, Jakarta Selatan"
    }
  }'
```

---

#### 3.3.2 Get Delivery Options

**Endpoint**

```http
GET /api/customer/delivery/options
```

**Headers**

```http
Content-Type: application/json
```

**Response 200**

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

### 3.4 Membuat Pesanan (Checkout dengan Diskon & Delivery Fee)

Mencakup use case:
- memilih tipe pesanan (dine in / take away / delivery),
- menambahkan menu ke keranjang,
- melakukan checkout pesanan.

**Endpoint**

```http
POST /api/customer/orders
```

**Headers**

```http
X-Guest-Token: <guest_token_uuid>
Content-Type: application/json
```

**Request Body (Dine In)**

```json
{
  "customer_name": "Budi Santoso",
  "customer_phone": "08123456789",
  "order_type": "dine_in",
  "table_id": 3,
  "notes": "Less sugar untuk semua minuman"
}
```

**Request Body (Take Away)**

```json
{
  "customer_name": "Budi Santoso",
  "customer_phone": "08123456789",
  "order_type": "take_away",
  "notes": "Tolong siapkan sebelum jam 12"
}
```

**Request Body (Delivery)**

```json
{
  "customer_name": "Budi Santoso",
  "customer_phone": "08123456789",
  "order_type": "delivery",
  "table_id": null,
  "delivery_fee": 15000,
  "notes": "Jangan pakai bawang putih",
  "address": {
    "receiver_name": "Budi Santoso",
    "phone": "08123456789",
    "full_address": "Jl. Mawar No. 1, RT 01/RW 05",
    "city": "Bandung",
    "postal_code": "40123",
    "notes": "Pintu gerbang biru, rumah nomor 1"
  }
}
```

**Field Description:**
- `customer_name` (required): Nama customer
- `customer_phone` (optional untuk dine_in): Nomor telepon
- `order_type` (required): Tipe order - `dine_in`, `take_away`, atau `delivery`
- `table_id` (required jika dine_in): ID meja yang dipilih
- `delivery_fee` (required jika delivery): Ongkir dari API calculate-fee
- `notes` (optional): Catatan untuk keseluruhan order
- `address` (required jika delivery): Data alamat pengiriman

**Response 201**

```json
{
  "message": "Order created successfully",
  "data": {
    "id": 1001,
    "order_type": "delivery",
    "status": "pending_payment",
    "total_price": 67500,
    "delivery_fee": 15000,
    "discount_amount": 7500,
    "final_price": 75000
  }
}
```

**Response Breakdown:**
- `total_price`: Subtotal setelah diskon (harga menu × qty - diskon)
- `delivery_fee`: Ongkir (0 untuk dine_in dan take_away)
- `discount_amount`: Total diskon dari semua menu
- `final_price`: Total yang harus dibayar (total_price + delivery_fee)

**Contoh Perhitungan:**

Cart items:
1. Espresso (price: 20000, discount: 10%) × 2 = 36,000 (hemat 4,000)
2. Nasi Goreng (price: 35000, discount: 5000) × 1 = 30,000 (hemat 5,000)

```
Subtotal setelah diskon (total_price):  Rp 66,000
Total Diskon (discount_amount):         Rp  9,000
Delivery Fee (delivery_fee):            Rp 15,000
─────────────────────────────────────────────────
TOTAL BAYAR (final_price):              Rp 81,000
```

**Response 422 (Validation Error)**

```json
{
  "message": "The given data was invalid.",
  "errors": {
    "customer_name": ["The customer name field is required."],
    "order_type": ["The order type field must be one of: dine_in, take_away, delivery."],
    "table_id": ["The table id field is required when order type is dine_in."],
    "address": ["The address field is required when order type is delivery."]
  }
}
```

**Response 422 (Cart Empty)**

```json
{
  "message": "Cart is empty"
}
```

**Response 422 (Menu Not Available)**

```json
{
  "message": "Menu 'Espresso' is not available"
}
```

**Contoh Request (Dine In):**

```bash
curl -X POST http://localhost:8000/api/customer/orders \
  -H "X-Guest-Token: 550e8400-e29b-41d4-a716-446655440000" \
  -H "Content-Type: application/json" \
  -d '{
    "customer_name": "Budi Santoso",
    "customer_phone": "08123456789",
    "order_type": "dine_in",
    "table_id": 3,
    "notes": "Less sugar"
  }'
```

**Contoh Request (Delivery):**

```bash
curl -X POST http://localhost:8000/api/customer/orders \
  -H "X-Guest-Token: 550e8400-e29b-41d4-a716-446655440000" \
  -H "Content-Type: application/json" \
  -d '{
    "customer_name": "Budi Santoso",
    "customer_phone": "08123456789",
    "order_type": "delivery",
    "delivery_fee": 15000,
    "notes": "Jangan pakai bawang putih",
    "address": {
      "receiver_name": "Budi Santoso",
      "phone": "08123456789",
      "full_address": "Jl. Mawar No. 1",
      "city": "Bandung",
      "postal_code": "40123",
      "notes": "Pintu gerbang biru"
    }
  }'
```

---

### 3.5 Melihat Daftar Pesanan Customer

**Endpoint**

```http
GET /api/customer/orders
```

**Headers**

```http
X-Guest-Token: <guest_token_uuid>
Content-Type: application/json
```

**Query Params (opsional)**

- `status` : filter status pesanan (contoh: `pending_payment`, `processing`, `completed`, `cancelled`)

**Response 200**

```json
{
  "data": [
    {
      "id": 1001,
      "order_type": "delivery",
      "status": "processing",
      "total_price": 67500,
      "delivery_fee": 15000,
      "discount_amount": 7500,
      "final_price": 75000,
      "created_at": "2026-01-15T10:00:00Z"
    },
    {
      "id": 1002,
      "order_type": "dine_in",
      "status": "completed",
      "total_price": 45000,
      "delivery_fee": 0,
      "discount_amount": 5000,
      "final_price": 45000,
      "created_at": "2026-01-14T15:30:00Z"
    }
  ]
}
```

**Contoh Request:**

```bash
# Get all orders
curl -X GET http://localhost:8000/api/customer/orders \
  -H "X-Guest-Token: 550e8400-e29b-41d4-a716-446655440000"

# Filter by status
curl -X GET "http://localhost:8000/api/customer/orders?status=processing" \
  -H "X-Guest-Token: 550e8400-e29b-41d4-a716-446655440000"
```

---

### 3.6 Melihat Detail & Status Pesanan

**Endpoint**

```http
GET /api/customer/orders/{id}
```

**Headers**

```http
X-Guest-Token: <guest_token_uuid>
Content-Type: application/json
```

**Response 200**

```json
{
  "data": {
    "id": 1001,
    "order_type": "delivery",
    "status": "on_delivery",
    "total_price": 67500,
    "delivery_fee": 15000,
    "discount_amount": 7500,
    "final_price": 75000,
    "customer_name": "Budi Santoso",
    "customer_phone": "08123456789",
    "notes": "Jangan pakai bawang putih",
    "user": null,
    "table": null,
    "items": [
      {
        "menu_id": 1,
        "menu_name": "Espresso",
        "quantity": 2,
        "price": 18000
      },
      {
        "menu_id": 2,
        "menu_name": "Nasi Goreng",
        "quantity": 1,
        "price": 30000
      }
    ],
    "address": {
      "receiver_name": "Budi Santoso",
      "phone": "08123456789",
      "full_address": "Jl. Mawar No. 1, RT 01/RW 05",
      "city": "Bandung",
      "postal_code": "40123",
      "notes": "Pintu gerbang biru"
    },
    "delivery": {
      "status": "on_delivery"
    },
    "payments": [
      {
        "amount": 75000,
        "status": "paid",
        "paid_at": "2026-01-15T10:05:00Z"
      }
    ],
    "logs": [
      {
        "status": "pending_payment",
        "note": "Order created",
        "created_at": "2026-01-15T10:00:00Z"
      },
      {
        "status": "paid",
        "note": "Payment successful",
        "created_at": "2026-01-15T10:05:00Z"
      },
      {
        "status": "processing",
        "note": "Order is being prepared",
        "created_at": "2026-01-15T10:10:00Z"
      }
    ]
  }
}
```

**Response 404**

```json
{
  "message": "Order not found"
}
```

**Contoh Request:**

```bash
curl -X GET http://localhost:8000/api/customer/orders/1001 \
  -H "X-Guest-Token: 550e8400-e29b-41d4-a716-446655440000"
```

---

## 4. Pembayaran

### 4.1 Membuat Request Pembayaran untuk Pesanan

**Endpoint**

```http
POST /api/orders/{order_id}/payments
```

**Auth**

- Wajib `Bearer` token customer

**Request Body**

```json
{
  "payment_gateway": "midtrans",
  "payment_method": "qris"
}
```

**Response 201**

```json
{
  "message": "Payment Created",
  "data": {
    "id": 500,
    "order_id": 1001,
    "payment_gateway": "midtrans",
    "payment_method": "qris",
    "gateway_transaction_id": "trx123",
    "reference_id": "INV-1001",
    "amount": 60000,
    "status": "pending",
    "redirect_url": "https://payment-gateway/checkout/trx123"
  }
}
```

---

### 4.2 Callback / Webhook dari Payment Gateway

**Endpoint**

```http
POST /api/payments/webhook
```

**Auth**

- Tidak menggunakan JWT, namun divalidasi dengan secret/signature dari payment gateway.

**Request Body**

- Mengikuti format dari payment gateway (disimpan ke kolom `payload`).

**Response 200**

```json
{ "message": "OK" }
```

> Endpoint ini akan mengupdate:
> - `payments.status`, `paid_at`
> - `orders.status` (misal menjadi `paid`)
> - menambahkan `order_logs` baru.

---

## 5. API Admin

### 5.1 Manajemen Menu

#### 5.1.1 List Menu

**Endpoint**

```http
GET /admin/menus
```

**Auth**

- Wajib `Bearer` token admin

**Query Params (opsional)**

- `is_available`
- `search`

**Response 200**

```json
{
  "data": [
    {
      "id": 1,
      "name": "Espresso",
      "description": "Kopi espresso single shot",
      "price": 20000,
      "image": "/images/espresso.jpg",
      "is_available": true,
      "created_at": "2024-01-01T09:00:00Z",
      "updated_at": "2024-01-01T09:00:00Z"
    }
  ]
}
```

---

#### 5.1.2 Menambah Menu (dengan Diskon)

**Endpoint**

```http
POST /admin/menus
```

**Auth**

- Wajib `Bearer` token admin

**Headers**

```http
Authorization: Bearer <admin_token>
Content-Type: application/json
```

**Request Body**

```json
{
  "name": "Latte",
  "description": "Kopi latte",
  "price": 25000,
  "discount_percentage": 10,
  "discount_price": 0,
  "image": "/images/latte.jpg",
  "is_available": true
}
```

**Field Description:**
- `name` (required): Nama menu
- `description` (optional): Deskripsi menu
- `price` (required): Harga normal dalam rupiah
- `discount_percentage` (optional): Diskon dalam persen (0-100)
- `discount_price` (optional): Diskon dalam nominal rupiah
- `image` (optional): Path gambar menu
- `is_available` (optional): Status ketersediaan (default: true)

**Diskon Priority:**
1. Jika `discount_price` > 0 → gunakan diskon nominal
2. Jika `discount_percentage` > 0 → gunakan diskon persen
3. Jika keduanya 0 → tidak ada diskon

**Response 201**

```json
{
  "message": "Menu Created",
  "data": {
    "id": 2,
    "name": "Latte",
    "category": "drink",
    "description": "Kopi latte",
    "price": 25000,
    "discount_percentage": 10,
    "discount_price": 0,
    "final_price": 22500,
    "has_discount": true,
    "is_available": true,
    "created_at": "2026-01-15T10:00:00Z"
  }
}
```

**Response 422 (Validation Error)**

```json
{
  "message": "The given data was invalid.",
  "errors": {
    "name": ["The name field is required."],
    "price": ["The price must be at least 0."]
  }
}
```

**Contoh Request dengan Diskon Persen:**

```bash
curl -X POST http://localhost:8000/api/admin/menus \
  -H "Authorization: Bearer <admin_token>" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Cappuccino",
    "description": "Cappuccino dengan foam yang lembut",
    "price": 30000,
    "discount_percentage": 15,
    "discount_price": 0,
    "is_available": true
  }'
```

**Contoh Request dengan Diskon Nominal:**

```bash
curl -X POST http://localhost:8000/api/admin/menus \
  -H "Authorization: Bearer <admin_token>" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Affogato",
    "description": "Espresso dengan ice cream",
    "price": 35000,
    "discount_percentage": 0,
    "discount_price": 5000,
    "is_available": true
  }'
```

---

#### 5.1.3 Mengedit Menu (dengan Diskon)

**Endpoint**

```http
PUT /admin/menus/{id}
```

**Auth**

- Wajib `Bearer` token admin

**Headers**

```http
Authorization: Bearer <admin_token>
Content-Type: application/json
```

**Request Body**

```json
{
  "name": "Latte Vanilla",
  "description": "Latte dengan vanilla syrup",
  "price": 28000,
  "discount_percentage": 20,
  "discount_price": 0,
  "image": "/images/latte-vanilla.jpg",
  "is_available": true
}
```

**Response 200**

```json
{
  "message": "Menu Updated",
  "data": {
    "id": 2,
    "name": "Latte Vanilla",
    "price": 28000,
    "discount_percentage": 20,
    "discount_price": 0,
    "final_price": 22400,
    "has_discount": true
  }
}
```

**Response 404**

```json
{
  "message": "Menu not found"
}
```

**Contoh Request (Update Diskon):**

```bash
curl -X PUT http://localhost:8000/api/admin/menus/2 \
  -H "Authorization: Bearer <admin_token>" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Latte Vanilla",
    "price": 28000,
    "discount_percentage": 0,
    "discount_price": 8000,
    "is_available": true
  }'
```

---

#### 5.1.4 Mengaktifkan / Menonaktifkan Menu

**Endpoint**

```http
PATCH /admin/menus/{id}/availability
```

**Request Body**

```json
{
  "is_available": false
}
```

**Response 200**

```json
{
  "message": "Menu Availability Updated",
  "data": {
    "id": 2,
    "is_available": false
  }
}
```

---

### 5.2 Manajemen Meja (Tables)

#### 5.2.1 Menambah Meja

**Endpoint**

```http
POST /admin/tables
```

**Request Body**

```json
{
  "table_number": "A-03",
  "status": "available"   // available | occupied | disabled
}
```

**Response 201**

```json
{
  "message": "Table Created",
  "data": {
    "id": 3,
    "table_number": "A-03",
    "status": "available"
  }
}
```

---

#### 5.2.2 List Meja

**Endpoint**

```http
GET /admin/tables
```

**Response 200**

```json
{
  "data": [
    {
      "id": 3,
      "table_number": "A-03",
      "status": "available"
    }
  ]
}
```

---

### 5.3 Manajemen Pesanan

#### 5.3.1 Melihat Daftar Pesanan

**Endpoint**

```http
GET /admin/orders
```

**Query Params (opsional)**

- `status`
- `order_type`
- `date_from`
- `date_to`

**Response 200**

```json
{
  "data": [
    {
      "id": 1001,
      "user_name": "Budi",
      "order_type": "delivery",
      "status": "processing",
      "total_price": 60000,
      "created_at": "2024-01-01T10:00:00Z"
    }
  ]
}
```

---

#### 5.3.2 Detail Pesanan

**Endpoint**

```http
GET /admin/orders/{id}
```

**Response**

Struktur sama dengan `GET /api/orders/{id}` namun tanpa pembatasan user.

---

#### 5.3.3 Update Status Pesanan

Mencakup use case:
- Mengupdate status pesanan (accepted, processing, ready, served, completed, cancelled).

**Endpoint**

```http
PATCH /admin/orders/{id}/status
```

**Request Body**

```json
{
  "status": "processing",
  "note": "Sedang disiapkan barista"
}
```

**Response 200**

```json
{
  "message": "Order Status Updated",
  "data": {
    "id": 1001,
    "status": "processing"
  }
}
```

> Endpoint ini juga akan menyimpan log baru ke tabel `order_logs`.

---

### 5.4 Manajemen Delivery (Request Kurir)

#### 5.4.1 Membuat Request Delivery ke Kurir

**Endpoint**

```http
POST /admin/orders/{id}/delivery-request
```

**Prasyarat**

- `orders.order_type = "delivery"`
- Pesanan sudah `paid` atau sesuai aturan bisnis.

**Request Body**

```json
{
  "courier": "JNE"
}
```

**Response 201**

```json
{
  "message": "Delivery Request Created",
  "data": {
    "id": 300,
    "order_id": 1001,
    "courier": "JNE",
    "tracking_number": null,
    "delivery_status": "requested"
  }
}
```

---

#### 5.4.2 Melihat Data Delivery Order

**Endpoint**

```http
GET /admin/orders/{id}/delivery
```

**Response 200**

```json
{
  "data": {
    "id": 300,
    "order_id": 1001,
    "courier": "JNE",
    "tracking_number": "JNE123",
    "delivery_status": "on_delivery"
  }
}
```

---

## 6. API Kurir / Partner

Digunakan oleh sistem kurir untuk:

- menerima request delivery,
- memberikan estimasi ongkir,
- mengirim status delivery,
- mengirim hasil akhir pengantaran.

### 6.1 List Delivery yang Harus Diproses

**Endpoint**

```http
GET /courier/deliveries
```

**Auth**

- Menggunakan mekanisme auth yang disepakati dengan partner (contoh: API key).

**Query Params (opsional)**

- `status` : `requested|on_delivery|delivered|failed`

**Response 200**

```json
{
  "data": [
    {
      "id": 300,
      "order_id": 1001,
      "courier": "JNE",
      "delivery_status": "requested"
    }
  ]
}
```

---

### 6.2 Memberikan Estimasi Ongkir

**Endpoint**

```http
PATCH /courier/deliveries/{id}/quote
```

**Request Body**

```json
{
  "shipping_fee": 15000,
  "estimated_minutes": 30
}
```

**Response 200**

```json
{
  "message": "Quote Accepted",
  "data": {
    "id": 300,
    "order_id": 1001,
    "shipping_fee": 15000,
    "estimated_minutes": 30,
    "delivery_status": "assigned"
  }
}
```

> Implementasi penyimpanan `shipping_fee` dapat dimasukkan ke `orders.total_price` / kolom tambahan sesuai kebutuhan.

---

### 6.3 Update Status Delivery

Mencakup:
- Mengirim status delivery,
- Mengirim hasil akhir pengantaran.

**Endpoint**

```http
PATCH /courier/deliveries/{id}/status
```

**Request Body**

```json
{
  "delivery_status": "delivered",  // requested | assigned | on_delivery | delivered | failed
  "tracking_number": "JNE123",
  "note": "Paket diterima oleh Budi"
}
```

**Response 200**

```json
{
  "message": "Delivery Status Updated",
  "data": {
    "id": 300,
    "order_id": 1001,
    "delivery_status": "delivered",
    "tracking_number": "JNE123"
  }
}
```

---

## 7. Struktur Data Utama (Ringkas)

### 7.1 Status Enum yang Disarankan

- `orders.status`  
  - `pending_payment`, `paid`, `accepted`, `processing`, `ready`, `served`, `on_delivery`, `completed`, `cancelled`

- `deliveries.delivery_status`  
  - `requested`, `assigned`, `on_delivery`, `delivered`, `failed`

- `payments.status`  
  - `pending`, `paid`, `failed`, `expired`, `refunded`

---

Dokumen ini bisa diperluas jika ada kebutuhan endpoint tambahan (misalnya CRUD user, laporan, dsb), namun sudah mencakup seluruh use case utama pada diagram dan tabel di ERD.

---

## 8. Testing Guide

### 8.1 Setup Environment

**1. Clone Repository**

```bash
git clone <repository-url>
cd e-commerce-meracikopi
```

**2. Install Dependencies**

```bash
composer install
npm install
```

**3. Setup Environment**

```bash
cp .env.example .env
php artisan key:generate
```

**4. Setup Database**

Edit `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=meracikopi
DB_USERNAME=root
DB_PASSWORD=
```

**5. Run Migration**

```bash
php artisan migrate --seed
```

**6. Start Server**

```bash
php artisan serve
# Server berjalan di http://127.0.0.1:8000
```

---

### 8.2 Testing Flow dengan Postman

#### Step 1: Get All Menus (with Discount)

```http
GET http://localhost:8000/api/customer/catalogs
```

**Expected Response:**
- List menus dengan informasi diskon
- Field: `price`, `discount_percentage`, `discount_price`, `final_price`, `has_discount`

---

#### Step 2: Add Items to Cart

```http
POST http://localhost:8000/api/customer/cart/items
Headers:
  X-Guest-Token: 550e8400-e29b-41d4-a716-446655440000
  Content-Type: application/json
Body:
{
  "menu_id": 1,
  "quantity": 2,
  "note": "Less sugar"
}
```

**Expected Response:**
- Cart item created
- Status code: 201

Ulangi untuk menambah item lain jika perlu.

---

#### Step 3: View Cart

```http
GET http://localhost:8000/api/customer/cart
Headers:
  X-Guest-Token: 550e8400-e29b-41d4-a716-446655440000
```

**Expected Response:**
- Cart dengan semua items
- Total sudah dihitung

---

#### Step 4: Calculate Delivery Fee (for Delivery Order)

```http
POST http://localhost:8000/api/customer/delivery/calculate-fee
Headers:
  Content-Type: application/json
Body:
{
  "destination": {
    "latitude": -6.175110,
    "longitude": 106.865036,
    "address": "Jl. Gatot Subroto, Jakarta"
  }
}
```

**Expected Response:**
- `delivery_fee`: calculated based on distance
- `distance`: in km
- `estimated_time`: in minutes

Simpan `delivery_fee` untuk digunakan di checkout.

---

#### Step 5: Checkout (Create Order)

**For Dine In:**

```http
POST http://localhost:8000/api/customer/orders
Headers:
  X-Guest-Token: 550e8400-e29b-41d4-a716-446655440000
  Content-Type: application/json
Body:
{
  "customer_name": "John Doe",
  "customer_phone": "08123456789",
  "order_type": "dine_in",
  "table_id": 1,
  "notes": "Less sugar"
}
```

**For Delivery:**

```http
POST http://localhost:8000/api/customer/orders
Headers:
  X-Guest-Token: 550e8400-e29b-41d4-a716-446655440000
  Content-Type: application/json
Body:
{
  "customer_name": "John Doe",
  "customer_phone": "08123456789",
  "order_type": "delivery",
  "delivery_fee": 15000,
  "notes": "Jangan pakai bawang",
  "address": {
    "receiver_name": "John Doe",
    "phone": "08123456789",
    "full_address": "Jl. Sudirman No. 123",
    "city": "Jakarta",
    "postal_code": "12190",
    "notes": "Rumah warna hijau"
  }
}
```

**Expected Response:**
- Order created
- Status: `pending_payment`
- `total_price`: subtotal after discount
- `delivery_fee`: ongkir (0 for dine_in/take_away)
- `discount_amount`: total discount
- `final_price`: total to pay

Simpan `order_id` untuk step berikutnya.

---

#### Step 6: View Order Detail

```http
GET http://localhost:8000/api/customer/orders/{order_id}
Headers:
  X-Guest-Token: 550e8400-e29b-41d4-a716-446655440000
```

**Expected Response:**
- Full order details
- Items with discounted prices
- Delivery info (if delivery)
- Payment status
- Order logs

---

#### Step 7: Admin - Create Menu with Discount

```http
POST http://localhost:8000/api/admin/menus
Headers:
  Authorization: Bearer <admin_token>
  Content-Type: application/json
Body:
{
  "name": "Cappuccino Special",
  "category": "drink",
  "description": "Cappuccino with special foam",
  "price": 35000,
  "discount_percentage": 20,
  "discount_price": 0,
  "is_available": true
}
```

**Expected Response:**
- Menu created
- `final_price`: 28000 (20% off from 35000)
- `has_discount`: true

---

### 8.3 Testing Scenarios

#### Scenario 1: Menu dengan Diskon Persen

1. Admin create menu:
   - `price`: 50000
   - `discount_percentage`: 15
   - `discount_price`: 0

2. Customer view catalog:
   - Verify `final_price` = 42500 (hemat 7500)

3. Customer checkout:
   - Verify `discount_amount` calculated correctly

---

#### Scenario 2: Menu dengan Diskon Nominal

1. Admin create menu:
   - `price`: 50000
   - `discount_percentage`: 0
   - `discount_price`: 10000

2. Customer view catalog:
   - Verify `final_price` = 40000 (hemat 10000)

---

#### Scenario 3: Order Delivery dengan Ongkir

1. Customer calculate delivery fee:
   - Distance: 8 km
   - Expected fee: 15000

2. Customer checkout:
   - `order_type`: delivery
   - Include `delivery_fee`: 15000

3. Verify order:
   - `total_price`: subtotal after discount
   - `delivery_fee`: 15000
   - `final_price`: total_price + delivery_fee

---

#### Scenario 4: Order Dine In (No Delivery Fee)

1. Customer checkout:
   - `order_type`: dine_in
   - `table_id`: 1

2. Verify order:
   - `delivery_fee`: 0
   - `final_price`: total_price - discount_amount

---

### 8.4 Postman Collection

Import collection dari folder `testing/`:
- `postman_collection_complete.json`
- `postman_environment.json`

**Environment Variables:**
- `base_url`: http://localhost:8000
- `guest_token`: 550e8400-e29b-41d4-a716-446655440000
- `admin_token`: (get from login)

---

### 8.5 Common Issues & Solutions

#### Issue 1: Guest Token Error

**Error:**
```json
{
  "message": "Guest token is required"
}
```

**Solution:**
Add header `X-Guest-Token` with a valid UUID.

---

#### Issue 2: Cart Empty

**Error:**
```json
{
  "message": "Cart is empty"
}
```

**Solution:**
Add items to cart before checkout.

---

#### Issue 3: Menu Not Available

**Error:**
```json
{
  "message": "Menu 'Espresso' is not available"
}
```

**Solution:**
Admin update menu availability:
```http
PATCH /api/admin/menus/{id}/availability
Body: { "is_available": true }
```

---

#### Issue 4: Invalid Delivery Fee

**Error:**
```json
{
  "message": "Delivery fee is required for delivery orders"
}
```

**Solution:**
1. Call `/api/customer/delivery/calculate-fee` first
2. Use returned `delivery_fee` in checkout

---

### 8.6 Price Calculation Verification

**Example Cart:**
- Menu A: price 25000, discount 10% → final_price 22500 × 2 = 45000
- Menu B: price 35000, discount 5000 → final_price 30000 × 1 = 30000

**Expected Calculation:**
```
Items Total (after discount):  Rp 75,000
Total Discount:                Rp  9,000
Delivery Fee (8 km):           Rp 15,000
──────────────────────────────────────
FINAL PRICE:                   Rp 90,000
```

**API Response Should Match:**
```json
{
  "total_price": 75000,
  "delivery_fee": 15000,
  "discount_amount": 9000,
  "final_price": 90000
}
```

---

### 8.7 Database Seeder

Run seeder untuk data testing:

```bash
php artisan db:seed
```

**Default Data:**
- Admin user: admin@meracikopi.com / password
- Sample menus with various discounts
- Sample tables
- Sample orders

---

## 9. API Response Codes

| Code | Meaning | Description |
|------|---------|-------------|
| 200 | OK | Request berhasil |
| 201 | Created | Resource berhasil dibuat |
| 400 | Bad Request | Request tidak valid |
| 401 | Unauthorized | Token tidak valid atau expired |
| 403 | Forbidden | Tidak punya akses |
| 404 | Not Found | Resource tidak ditemukan |
| 422 | Unprocessable Entity | Validation error |
| 500 | Internal Server Error | Server error |

---

## 10. Additional Resources

- **API Documentation:** [API_DOCUMENTATION.md](API_DOCUMENTATION.md)
- **Implementation Summary:** [IMPLEMENTATION_SUMMARY.md](IMPLEMENTATION_SUMMARY.md)
- **Postman Collection:** `testing/postman_collection_complete.json`
- **Postman Environment:** `testing/postman_environment.json`

---

## 11. Support & Contact

For issues or questions:
- GitHub Issues: <repository-url>/issues
- Email: support@meracikopi.com

---

**Last Updated:** January 15, 2026