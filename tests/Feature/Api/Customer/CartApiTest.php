<?php

namespace Tests\Feature\Api\Customer;

use Tests\TestCase;
use App\Models\Menus;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

class CartApiTest extends TestCase
{
    use RefreshDatabase;

    protected string $guestToken;

    protected function setUp(): void
    {
        parent::setUp();
        $this->guestToken = (string) Str::uuid();
    }

    public function test_add_item_to_cart(): void
    {
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
    }

    public function test_add_item_increases_quantity_if_exists(): void
    {
        $menu = Menus::factory()->create(['is_available' => true]);
        
        // Add item pertama kali
        $this->withHeaders(['X-GUEST-TOKEN' => $this->guestToken])
            ->postJson('/api/customer/cart/items', [
                'menu_id' => $menu->id,
                'quantity' => 2,
            ]);

        // Add item yang sama lagi
        $response = $this->withHeaders(['X-GUEST-TOKEN' => $this->guestToken])
            ->postJson('/api/customer/cart/items', [
                'menu_id' => $menu->id,
                'quantity' => 3,
            ]);

        $response->assertStatus(201);

        // Total quantity harus 5
        $cart = Cart::where('guest_token', $this->guestToken)->first();
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('menu_id', $menu->id)
            ->first();
        
        $this->assertEquals(5, $cartItem->quantity);
    }

    public function test_cannot_add_unavailable_menu_to_cart(): void
    {
        $menu = Menus::factory()->create(['is_available' => false]);

        $response = $this->withHeaders(['X-GUEST-TOKEN' => $this->guestToken])
            ->postJson('/api/customer/cart/items', [
                'menu_id' => $menu->id,
                'quantity' => 1,
            ]);

        $response->assertStatus(404)
            ->assertJson(['message' => 'Menu not available']);
    }

    public function test_view_cart(): void
    {
        $menu = Menus::factory()->create(['is_available' => true, 'price' => 25000]);
        
        // Add item to cart first
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

        $this->assertEquals(50000, $response->json('data.total_price'));
    }

    public function test_view_empty_cart(): void
    {
        $response = $this->withHeaders(['X-GUEST-TOKEN' => $this->guestToken])
            ->getJson('/api/customer/cart');

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'items' => [],
                    'total_price' => 0
                ]
            ]);
    }

    public function test_update_cart_item_quantity(): void
    {
        $menu = Menus::factory()->create(['is_available' => true]);
        
        // Add item
        $this->withHeaders(['X-GUEST-TOKEN' => $this->guestToken])
            ->postJson('/api/customer/cart/items', [
                'menu_id' => $menu->id,
                'quantity' => 2,
            ]);

        $cart = Cart::where('guest_token', $this->guestToken)->first();
        $cartItem = CartItem::where('cart_id', $cart->id)->first();

        // Update quantity
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
    }

    public function test_delete_cart_item(): void
    {
        $menu = Menus::factory()->create(['is_available' => true]);
        
        // Add item
        $this->withHeaders(['X-GUEST-TOKEN' => $this->guestToken])
            ->postJson('/api/customer/cart/items', [
                'menu_id' => $menu->id,
                'quantity' => 2,
            ]);

        $cart = Cart::where('guest_token', $this->guestToken)->first();
        $cartItem = CartItem::where('cart_id', $cart->id)->first();

        // Delete item
        $response = $this->withHeaders(['X-GUEST-TOKEN' => $this->guestToken])
            ->deleteJson("/api/customer/cart/items/{$cartItem->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Item removed from cart']);

        $this->assertDatabaseMissing('cart_items', ['id' => $cartItem->id]);
    }
}