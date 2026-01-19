# 🚀 Quick Testing Reference - E-Commerce MeracikOpi

## 📋 Base Information

```
Base URL: http://localhost:8000
Guest Token: 550e8400-e29b-41d4-a716-446655440000
```

---

## 🔑 Common Headers

### For Customer API (with Guest Token)
```http
X-Guest-Token: 550e8400-e29b-41d4-a716-446655440000
Content-Type: application/json
```

### For Admin API
```http
Authorization: Bearer <admin_token>
Content-Type: application/json
```

---

## 📝 Quick Testing Flow

### 1️⃣ View Menu (Public)
```bash
curl -X GET http://localhost:8000/api/customer/catalogs
```

### 2️⃣ Add to Cart
```bash
curl -X POST http://localhost:8000/api/customer/cart/items \
  -H "X-Guest-Token: 550e8400-e29b-41d4-a716-446655440000" \
  -H "Content-Type: application/json" \
  -d '{"menu_id": 1, "quantity": 2, "note": "Less sugar"}'
```

### 3️⃣ View Cart
```bash
curl -X GET http://localhost:8000/api/customer/cart \
  -H "X-Guest-Token: 550e8400-e29b-41d4-a716-446655440000"
```

### 4️⃣ Calculate Delivery Fee
```bash
curl -X POST http://localhost:8000/api/customer/delivery/calculate-fee \
  -H "Content-Type: application/json" \
  -d '{
    "destination": {
      "latitude": -6.175110,
      "longitude": 106.865036,
      "address": "Jl. Gatot Subroto, Jakarta"
    }
  }'
```

### 5️⃣ Checkout (Dine In)
```bash
curl -X POST http://localhost:8000/api/customer/orders \
  -H "X-Guest-Token: 550e8400-e29b-41d4-a716-446655440000" \
  -H "Content-Type: application/json" \
  -d '{
    "customer_name": "John Doe",
    "customer_phone": "08123456789",
    "order_type": "dine_in",
    "table_id": 1,
    "notes": "Less sugar"
  }'
```

### 6️⃣ Checkout (Delivery)
```bash
curl -X POST http://localhost:8000/api/customer/orders \
  -H "X-Guest-Token: 550e8400-e29b-41d4-a716-446655440000" \
  -H "Content-Type: application/json" \
  -d '{
    "customer_name": "John Doe",
    "customer_phone": "08123456789",
    "order_type": "delivery",
    "delivery_fee": 15000,
    "address": {
      "receiver_name": "John Doe",
      "phone": "08123456789",
      "full_address": "Jl. Sudirman No. 123",
      "city": "Jakarta",
      "postal_code": "12190"
    }
  }'
```

### 7️⃣ View Orders
```bash
curl -X GET http://localhost:8000/api/customer/orders \
  -H "X-Guest-Token: 550e8400-e29b-41d4-a716-446655440000"
```

### 8️⃣ View Order Detail
```bash
curl -X GET http://localhost:8000/api/customer/orders/1 \
  -H "X-Guest-Token: 550e8400-e29b-41d4-a716-446655440000"
```

---

## 👨‍💼 Admin Quick Test

### Create Menu with Discount (Percentage)
```bash
curl -X POST http://localhost:8000/api/admin/menus \
  -H "Authorization: Bearer <admin_token>" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Cappuccino",
    "category": "drink",
    "price": 30000,
    "discount_percentage": 15,
    "discount_price": 0,
    "is_available": true
  }'
```

### Create Menu with Discount (Nominal)
```bash
curl -X POST http://localhost:8000/api/admin/menus \
  -H "Authorization: Bearer <admin_token>" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Nasi Goreng",
    "category": "food",
    "price": 35000,
    "discount_percentage": 0,
    "discount_price": 5000,
    "is_available": true
  }'
```

### Update Menu Discount
```bash
curl -X PUT http://localhost:8000/api/admin/menus/1 \
  -H "Authorization: Bearer <admin_token>" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Espresso",
    "price": 25000,
    "discount_percentage": 20,
    "is_available": true
  }'
```

---

## 🧪 Test Scenarios

### ✅ Scenario 1: Order Dine In dengan Menu Diskon

1. Add menu dengan diskon 10% ke cart
2. Checkout dengan order_type: dine_in
3. Verify:
   - `discount_amount` > 0
   - `delivery_fee` = 0
   - `final_price` = total_price - discount_amount

### ✅ Scenario 2: Order Delivery dengan Menu Diskon + Ongkir

1. Add menu dengan diskon ke cart
2. Calculate delivery fee (dapat 15000)
3. Checkout dengan order_type: delivery, delivery_fee: 15000
4. Verify:
   - `discount_amount` > 0
   - `delivery_fee` = 15000
   - `final_price` = total_price + delivery_fee - discount_amount

### ✅ Scenario 3: Menu Tanpa Diskon

1. Admin create menu tanpa diskon (semua discount = 0)
2. Customer view catalog
3. Verify:
   - `has_discount` = false
   - `final_price` = price

---

## 🔢 Price Calculation Example

**Cart:**
- Espresso (Rp 20.000, diskon 10%) × 2 = Rp 36.000
- Nasi Goreng (Rp 35.000, diskon Rp 5.000) × 1 = Rp 30.000

**Calculation:**
```
Subtotal (after discount):     Rp 66.000
Total Discount:                Rp  9.000
Delivery Fee (8 km):           Rp 15.000
───────────────────────────────────────
TOTAL BAYAR (final_price):     Rp 81.000
```

**Expected JSON Response:**
```json
{
  "total_price": 66000,
  "delivery_fee": 15000,
  "discount_amount": 9000,
  "final_price": 81000
}
```

---

## 🎯 Delivery Fee Rates

| Distance | Fee |
|----------|-----|
| 0-5 km | Rp 10.000 |
| 5-10 km | Rp 15.000 |
| 10-15 km | Rp 20.000 |
| >15 km | Rp 25.000 + (Rp 2.000 × extra km) |

---

## 📱 Postman Tips

### Set Environment Variables
```
base_url = http://localhost:8000
guest_token = 550e8400-e29b-41d4-a716-446655440000
```

### Pre-request Script (Auto Guest Token)
```javascript
if (!pm.environment.get("guest_token")) {
    pm.environment.set("guest_token", pm.variables.replaceIn("{{$guid}}"));
}
```

### Test Script (Save Order ID)
```javascript
if (pm.response.code === 201) {
    const response = pm.response.json();
    pm.environment.set("order_id", response.data.id);
}
```

---

## ⚠️ Common Errors & Fixes

| Error | Fix |
|-------|-----|
| "Guest token is required" | Add `X-Guest-Token` header |
| "Cart is empty" | Add items to cart first |
| "Menu not available" | Admin enable menu availability |
| "Delivery fee required" | Call calculate-fee endpoint first |
| "Table not found" | Use valid table_id from tables list |

---

## 🗂️ Files Location

- **Postman Collection:** `testing/postman_collection_complete.json`
- **Environment:** `testing/postman_environment.json`
- **Full API Docs:** `API_DOCUMENTATION.md`
- **Implementation Guide:** `IMPLEMENTATION_SUMMARY.md`

---

## 🚀 Quick Start Commands

```bash
# Setup
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed

# Run server
php artisan serve

# Test connection
curl http://localhost:8000/api/customer/catalogs
```

---

**Happy Testing! 🎉**
