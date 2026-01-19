<?php

use App\Models\Menus;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Str;

beforeEach(function () {
    $this->guestToken = (string) Str::uuid();
});

test('customer can add item to cart', function () {
    $menu = Menus::factory()->create(['is_available' => true]);

    $response = $this->withHeaders([
            'X-GUEST-TOKEN' => $this->guestToken,
        ])
        ->postJson('/api/customer/cart/items', [
            'menu_id' => $menu->id,
            'quantity' => 2,
        ]);

    $response->assertStatus(201)
        ->assertJson(['message' => 'Item added to cart']);

    $this->assertDatabaseHas('carts', ['guest_token' => $this->guestToken]);
    $this->assertDatabaseHas('cart_items', [
        'menu_id' => $menu->id,
        'quantity' => 2,
    ]);
});

test('adding same item increases quantity', function () {
    $menu = Menus::factory()->create(['is_available' => true]);
    
    $this->withHeaders(['X-GUEST-TOKEN' => $this->guestToken])
        ->postJson('/api/customer/cart/items', [
            'menu_id' => $menu->id,
            'quantity' => 2,
        ]);

    $response = $this->withHeaders(['X-GUEST-TOKEN' => $this->guestToken])
        ->postJson('/api/customer/cart/items', [
            'menu_id' => $menu->id,
            'quantity' => 3,
        ]);

    $response->assertStatus(201);

    $cart = Cart::where('guest_token', $this->guestToken)->first();
    $cartItem = CartItem::where('cart_id', $cart->id)
        ->where('menu_id', $menu->id)
        ->first();
    
    expect($cartItem->quantity)->toBe(5);
});

test('cannot add unavailable menu to cart', function () {
    $menu = Menus::factory()->create(['is_available' => false]);

    $response = $this->withHeaders(['X-GUEST-TOKEN' => $this->guestToken])
        ->postJson('/api/customer/cart/items', [
            'menu_id' => $menu->id,
            'quantity' => 1,
        ]);

    $response->assertStatus(404)
        ->assertJson(['message' => 'Menu not available']);
});

test('customer can view cart', function () {
    $menu = Menus::factory()->create(['is_available' => true, 'price' => 25000]);
    
    $this->withHeaders(['X-GUEST-TOKEN' => $this->guestToken])
        ->postJson('/api/customer/cart/items', [
            'menu_id' => $menu->id,
            'quantity' => 2,
        ]);

    $response = $this->withHeaders(['X-GUEST-TOKEN' => $this->guestToken])
        ->getJson('/api/customer/cart');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'items' => [
                    '*' => ['id', 'menu_id', 'menu_name', 'price', 'quantity', 'subtotal']
                ],
                'total_price'
            ]
        ]);

    // Check total_price exists and is correct
    $totalPrice = $response->json('data.total_price');
    expect($totalPrice)->toBe(50000);
});

test('customer can view empty cart', function () {
    $response = $this->withHeaders(['X-GUEST-TOKEN' => $this->guestToken])
        ->getJson('/api/customer/cart');

    $response->assertStatus(200)
        ->assertJson([
            'data' => [
                'items' => [],
                'total_price' => 0
            ]
        ]);
});

test('customer can update cart item quantity', function () {
    $menu = Menus::factory()->create(['is_available' => true]);
    
    $this->withHeaders(['X-GUEST-TOKEN' => $this->guestToken])
        ->postJson('/api/customer/cart/items', [
            'menu_id' => $menu->id,
            'quantity' => 2,
        ]);

    $cart = Cart::where('guest_token', $this->guestToken)->first();
    $cartItem = CartItem::where('cart_id', $cart->id)->first();

    $response = $this->withHeaders(['X-GUEST-TOKEN' => $this->guestToken])
        ->putJson("/api/customer/cart/items/{$cartItem->id}", [
            'quantity' => 5,
        ]);

    $response->assertStatus(200)
        ->assertJson(['message' => 'Cart item updated']);

    $this->assertDatabaseHas('cart_items', [
        'id' => $cartItem->id,
        'quantity' => 5,
    ]);
});

test('customer can delete cart item', function () {
    $menu = Menus::factory()->create(['is_available' => true]);
    
    $this->withHeaders(['X-GUEST-TOKEN' => $this->guestToken])
        ->postJson('/api/customer/cart/items', [
            'menu_id' => $menu->id,
            'quantity' => 2,
        ]);

    $cart = Cart::where('guest_token', $this->guestToken)->first();
    $cartItem = CartItem::where('cart_id', $cart->id)->first();

    $response = $this->withHeaders(['X-GUEST-TOKEN' => $this->guestToken])
        ->deleteJson("/api/customer/cart/items/{$cartItem->id}");

    $response->assertStatus(200)
        ->assertJson(['message' => 'Item removed from cart']);

    $this->assertDatabaseMissing('cart_items', ['id' => $cartItem->id]);
});