<?php

use App\Models\Menus;
use App\Models\Orders;
use App\Models\Tables;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Str;

beforeEach(function () {
    $this->guestToken = (string) Str::uuid();
});

test('customer can create take away order', function () {
    $menu = Menus::factory()->create(['is_available' => true, 'price' => 25000]);
    
    // Create cart first
    $cart = Cart::create([
        'guest_token' => $this->guestToken,
        'status' => 'active'
    ]);
    
    // Add item to cart
    CartItem::create([
        'cart_id' => $cart->id,
        'menu_id' => $menu->id,
        'quantity' => 2,
    ]);

    $response = $this->withHeaders(['X-GUEST-TOKEN' => $this->guestToken])
        ->postJson('/api/customer/orders', [
            'customer_name' => 'John Doe',
            'customer_phone' => '08123456789',
            'order_type' => 'take_away',
            'notes' => 'Less sugar please',
        ]);

    $response->assertStatus(201)
        ->assertJson(['message' => 'Order created successfully']);

    $this->assertDatabaseHas('orders', [
        'guest_token' => $this->guestToken,
        'order_type' => 'take_away',
        'customer_name' => 'John Doe',
    ]);
});

test('customer can create dine in order', function () {
    $menu = Menus::factory()->create(['is_available' => true, 'price' => 25000]);
    $table = Tables::factory()->create();
    
    // Create cart first
    $cart = Cart::create([
        'guest_token' => $this->guestToken,
        'status' => 'active'
    ]);
    
    // Add item to cart
    CartItem::create([
        'cart_id' => $cart->id,
        'menu_id' => $menu->id,
        'quantity' => 1,
    ]);

    $response = $this->withHeaders(['X-GUEST-TOKEN' => $this->guestToken])
        ->postJson('/api/customer/orders', [
            'customer_name' => 'Jane Doe',
            'customer_phone' => '08123456789',
            'order_type' => 'dine_in',
            'table_id' => $table->id,
        ]);

    $response->assertStatus(201);

    $this->assertDatabaseHas('orders', [
        'order_type' => 'dine_in',
        'table_id' => $table->id,
    ]);
});

test('customer can create delivery order', function () {
    $menu = Menus::factory()->create(['is_available' => true, 'price' => 25000]);
    
    // Create cart first
    $cart = Cart::create([
        'guest_token' => $this->guestToken,
        'status' => 'active'
    ]);
    
    // Add item to cart
    CartItem::create([
        'cart_id' => $cart->id,
        'menu_id' => $menu->id,
        'quantity' => 2,
    ]);

    $response = $this->withHeaders(['X-GUEST-TOKEN' => $this->guestToken])
        ->postJson('/api/customer/orders', [
            'customer_name' => 'John Doe',
            'customer_phone' => '08123456789',
            'order_type' => 'delivery',
            'address' => [
                'receiver_name' => 'John Doe',
                'phone' => '08123456789',
                'full_address' => 'Jl. Merdeka No. 123',
                'city' => 'Jakarta',
                'postal_code' => '12345',
            ],
        ]);

    $response->assertStatus(201);

    $this->assertDatabaseHas('orders', [
        'order_type' => 'delivery',
    ]);
});

test('customer can get all orders', function () {
    $menu = Menus::factory()->create(['is_available' => true, 'price' => 25000]);
    
    // Create cart first
    $cart = Cart::create([
        'guest_token' => $this->guestToken,
        'status' => 'active'
    ]);
    
    // Add item to cart
    CartItem::create([
        'cart_id' => $cart->id,
        'menu_id' => $menu->id,
        'quantity' => 1,
    ]);
    
    // Create order
    $this->withHeaders(['X-GUEST-TOKEN' => $this->guestToken])
        ->postJson('/api/customer/orders', [
            'customer_name' => 'John Doe',
            'customer_phone' => '08123456789',
            'order_type' => 'take_away',
        ]);

    $response = $this->withHeaders(['X-GUEST-TOKEN' => $this->guestToken])
        ->getJson('/api/customer/orders');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => ['id', 'order_type', 'status', 'total_price']
            ]
        ]);
});

test('customer can get order by id', function () {
    $menu = Menus::factory()->create(['is_available' => true, 'price' => 25000]);
    
    // Create cart first
    $cart = Cart::create([
        'guest_token' => $this->guestToken,
        'status' => 'active'
    ]);
    
    // Add item to cart
    CartItem::create([
        'cart_id' => $cart->id,
        'menu_id' => $menu->id,
        'quantity' => 1,
    ]);
    
    $createResponse = $this->withHeaders(['X-GUEST-TOKEN' => $this->guestToken])
        ->postJson('/api/customer/orders', [
            'customer_name' => 'John Doe',
            'customer_phone' => '08123456789',
            'order_type' => 'take_away',
        ]);

    $orderId = $createResponse->json('data.id');

    $response = $this->withHeaders(['X-GUEST-TOKEN' => $this->guestToken])
        ->getJson("/api/customer/orders/{$orderId}");

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => ['id', 'order_type', 'status', 'total_price', 'items']
        ]);
});

test('customer cannot get other guest order', function () {
    $menu = Menus::factory()->create(['is_available' => true, 'price' => 25000]);
    
    // Create cart first
    $cart = Cart::create([
        'guest_token' => $this->guestToken,
        'status' => 'active'
    ]);
    
    // Add item to cart
    CartItem::create([
        'cart_id' => $cart->id,
        'menu_id' => $menu->id,
        'quantity' => 1,
    ]);
    
    $this->withHeaders(['X-GUEST-TOKEN' => $this->guestToken])
        ->postJson('/api/customer/orders', [
            'customer_name' => 'John Doe',
            'customer_phone' => '08123456789',
            'order_type' => 'take_away',
        ]);

    $order = Orders::where('guest_token', $this->guestToken)->first();

    $response = $this->withHeaders(['X-GUEST-TOKEN' => (string) Str::uuid()])
        ->getJson("/api/customer/orders/{$order->id}");

    $response->assertStatus(404);
});

test('create order with validation error returns 422', function () {
    $response = $this->withHeaders(['X-GUEST-TOKEN' => $this->guestToken])
        ->postJson('/api/customer/orders', [
            'order_type' => 'invalid_type',
        ]);

    $response->assertStatus(422);
});