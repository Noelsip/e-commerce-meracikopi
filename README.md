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

#### 3.1.1 List Menu

**Endpoint**

```http
GET /api/menus
```

**Query Params (opsional)**

- `is_available` : `true|false`
- `search` : string nama menu

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
      "is_available": true
    }
  ]
}
```

---

#### 3.1.2 Detail Menu

**Endpoint**

```http
GET /api/menus/{id}
```

**Response 200**

```json
{
  "data": {
    "id": 1,
    "name": "Espresso",
    "description": "Kopi espresso single shot",
    "price": 20000,
    "image": "/images/espresso.jpg",
    "is_available": true
  }
}
```

---

### 3.2 Membuat Pesanan (Checkout)

Mencakup use case:
- memilih tipe pesanan (dine in / take away / delivery),
- menambahkan menu ke keranjang,
- melakukan checkout pesanan.

**Endpoint**

```http
POST /api/orders
```

**Auth**

- Wajib `Bearer` token customer

**Request Body**

```json
{
  "order_type": "delivery",        // "dine_in" | "take_away" | "delivery"
  "table_id": 3,                   // hanya untuk "dine_in"
  "items": [
    {
      "menu_id": 1,
      "quantity": 2
    },
    {
      "menu_id": 5,
      "quantity": 1
    }
  ],
  "note": "Less sugar",
  "address": {                     // wajib jika order_type = "delivery"
    "receiver_name": "Budi",
    "phone": "08123456789",
    "full_address": "Jl. Mawar No. 1",
    "city": "Bandung",
    "postal_code": "40123",
    "notes": "Pintu gerbang biru"
  }
}
```

**Response 201**

```json
{
  "message": "Order Created",
  "data": {
    "id": 1001,
    "order_type": "delivery",
    "status": "pending_payment",
    "total_price": 60000,
    "items": [
      {
        "menu_id": 1,
        "quantity": 2,
        "price": 20000,
        "subtotal": 40000
      },
      {
        "menu_id": 5,
        "quantity": 1,
        "price": 20000,
        "subtotal": 20000
      }
    ]
  }
}
```

---

### 3.3 Melihat Daftar Pesanan Customer

**Endpoint**

```http
GET /api/orders
```

**Auth**

- Wajib `Bearer` token customer

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
      "total_price": 60000,
      "created_at": "2024-01-01T10:00:00Z"
    }
  ]
}
```

---

### 3.4 Melihat Detail & Status Pesanan

**Endpoint**

```http
GET /api/orders/{id}
```

**Auth**

- Wajib `Bearer` token customer (hanya boleh melihat pesanan miliknya)

**Response 200**

```json
{
  "data": {
    "id": 1001,
    "order_type": "delivery",
    "status": "on_delivery",
    "total_price": 60000,
    "user": {
      "id": 10,
      "name": "Budi"
    },
    "table": {
      "id": 3,
      "table_number": "A-03"
    },
    "items": [
      {
        "menu_id": 1,
        "menu_name": "Espresso",
        "quantity": 2,
        "price": 20000
      }
    ],
    "address": {
      "receiver_name": "Budi",
      "phone": "08123456789",
      "full_address": "Jl. Mawar No. 1",
      "city": "Bandung",
      "postal_code": "40123"
    },
    "delivery": {
      "courier": "JNE",
      "tracking_number": "JNE123",
      "delivery_status": "on_delivery"
    },
    "payments": [
      {
        "id": 500,
        "payment_gateway": "midtrans",
        "payment_method": "qris",
        "amount": 60000,
        "status": "paid",
        "paid_at": "2024-01-01T10:05:00Z"
      }
    ],
    "logs": [
      {
        "status": "created",
        "note": null,
        "created_at": "2024-01-01T10:00:00Z"
      }
    ]
  }
}
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

#### 5.1.2 Menambah Menu

**Endpoint**

```http
POST /admin/menus
```

**Request Body**

```json
{
  "name": "Latte",
  "description": "Kopi latte",
  "price": 25000,
  "image": "/images/latte.jpg",
  "is_available": true
}
```

**Response 201**

```json
{
  "message": "Menu Created",
  "data": {
    "id": 2,
    "name": "Latte",
    "price": 25000,
    "is_available": true
  }
}
```

---

#### 5.1.3 Mengedit Menu

**Endpoint**

```http
PUT /admin/menus/{id}
```

**Request Body**

```json
{
  "name": "Latte Vanilla",
  "description": "Latte dengan vanilla syrup",
  "price": 28000,
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
    "price": 28000
  }
}
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