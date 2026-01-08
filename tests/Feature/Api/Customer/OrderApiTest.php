<?php

namespace Tests\Feature\Api\Customer;

use Tests\TestCase;
use App\Models\Menus;
use App\Models\Orders;
use App\Models\Tables;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

class OrderApiTest extends TestCase
{
    use RefreshDatabase;

    protected string $guestToken;

    protected function setUp(): void
    {
        parent::setUp();
        $this->guestToken = (string) Str::uuid();
    }

    public function test_create_order_take_away(): void
    {
        $menu = Menus::factory()->create(['is_available' => true, 'price' => 25000]);

        $response = $this->withHeaders(['X-GUEST-TOKEN' => $this->guestToken])
            ->postJson('/api/customer/orders', [
                'order_type' => 'take_away',
                'items' => [
                    ['menu_id' => $menu->id, 'quantity' => 2]
                ],
                'note' => 'Less sugar please',
            ]);

        $response->assertStatus(201)
            ->assertJson(['message' => 'Order Created']);

        $this->assertDatabaseHas('orders', [
            'guest_token' => $this->guestToken,
            'order_type' => 'take_away',
        ]);
    }

    public function test_create_order_dine_in(): void
    {
        $menu = Menus::factory()->create(['is_available' => true]);
        $table = Tables::factory()->create();

        $response = $this->withHeaders(['X-GUEST-TOKEN' => $this->guestToken])
            ->postJson('/api/customer/orders', [
                'order_type' => 'dine_in',
                'table_id' => $table->id,
                'items' => [
                    ['menu_id' => $menu->id, 'quantity' => 1]
                ],
            ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('orders', [
            'order_type' => 'dine_in',
            'table_id' => $table->id,
        ]);
    }

    public function test_create_order_delivery(): void
    {
        $menu = Menus::factory()->create(['is_available' => true]);

        $response = $this->withHeaders(['X-GUEST-TOKEN' => $this->guestToken])
            ->postJson('/api/customer/orders', [
                'order_type' => 'delivery',
                'items' => [
                    ['menu_id' => $menu->id, 'quantity' => 2]
                ],
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
    }

    public function test_get_all_orders(): void
    {
        // Create order first
        $menu = Menus::factory()->create(['is_available' => true, 'price' => 25000]);
        
        $this->withHeaders(['X-GUEST-TOKEN' => $this->guestToken])
            ->postJson('/api/customer/orders', [
                'order_type' => 'take_away',
                'items' => [
                    ['menu_id' => $menu->id, 'quantity' => 1]
                ],
            ]);

        $response = $this->withHeaders(['X-GUEST-TOKEN' => $this->guestToken])
            ->getJson('/api/customer/orders');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'order_type', 'status', 'total_price']
                ]
            ]);
    }

    public function test_get_order_by_id(): void
    {
        $menu = Menus::factory()->create(['is_available' => true, 'price' => 25000]);
        
        $createResponse = $this->withHeaders(['X-GUEST-TOKEN' => $this->guestToken])
            ->postJson('/api/customer/orders', [
                'order_type' => 'take_away',
                'items' => [
                    ['menu_id' => $menu->id, 'quantity' => 1]
                ],
            ]);

        $orderId = $createResponse->json('data.id');

        $response = $this->withHeaders(['X-GUEST-TOKEN' => $this->guestToken])
            ->getJson("/api/customer/orders/{$orderId}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['id', 'order_type', 'status', 'total_price', 'items']
            ]);
    }

    public function test_cannot_get_other_guest_order(): void
    {
        $menu = Menus::factory()->create(['is_available' => true]);
        
        // Create order with one guest token
        $this->withHeaders(['X-GUEST-TOKEN' => $this->guestToken])
            ->postJson('/api/customer/orders', [
                'order_type' => 'take_away',
                'items' => [
                    ['menu_id' => $menu->id, 'quantity' => 1]
                ],
            ]);

        $order = Orders::where('guest_token', $this->guestToken)->first();

        // Try to access with different guest token
        $response = $this->withHeaders(['X-GUEST-TOKEN' => (string) Str::uuid()])
            ->getJson("/api/customer/orders/{$order->id}");

        $response->assertStatus(404);
    }

    public function test_create_order_validation_error(): void
    {
        $response = $this->withHeaders(['X-GUEST-TOKEN' => $this->guestToken])
            ->postJson('/api/customer/orders', [
                'order_type' => 'invalid_type',
                'items' => [],
            ]);

        $response->assertStatus(422);
    }
}