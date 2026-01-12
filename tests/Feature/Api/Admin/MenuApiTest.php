<?php

namespace Tests\Feature\Api\Admin;

use Tests\TestCase;
use App\Models\User;
use App\Models\Menus;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MenuApiTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Buat admin user untuk testing
        $this->admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@meracikopi.com',
            'password' => 'password'
        ]);
    }

    public function test_get_all_menus(): void
    {
        // Buat beberapa menu
        Menus::factory()->count(3)->create();

        $response = $this->actingAs($this->admin)
            ->getJson('/api/admin/menus');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'description', 'price', 'is_available']
                ]
            ]);
    }

    public function test_create_menu(): void
    {
        $menuData = [
            'name' => 'Kopi Susu Gula Aren',
            'description' => 'Kopi susu dengan gula aren premium',
            'price' => 28000,
            'image_path' => '/images/kopi.jpg',
            'is_available' => true,
        ];

        $response = $this->actingAs($this->admin)
            ->postJson('/api/admin/menus', $menuData);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Menu Created',
                'data' => [
                    'name' => 'Kopi Susu Gula Aren',
                ]
            ]);

        $this->assertDatabaseHas('menus', ['name' => 'Kopi Susu Gula Aren']);
    }

    public function test_get_menu_by_id(): void
    {
        $menu = Menus::factory()->create();

        $response = $this->actingAs($this->admin)
            ->getJson("/api/admin/menus/{$menu->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['id', 'name', 'description', 'price', 'is_available']
            ]);
    }

    public function test_update_menu(): void
    {
        $menu = Menus::factory()->create();

        $response = $this->actingAs($this->admin)
            ->putJson("/api/admin/menus/{$menu->id}", [
                'name' => 'Updated Menu Name',
                'price' => 35000,
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Menu Updated',
                'data' => [
                    'name' => 'Updated Menu Name',
                    'price' => 35000,
                ]
            ]);
    }

    public function test_update_menu_availability(): void
    {
        $menu = Menus::factory()->create(['is_available' => true]);

        $response = $this->actingAs($this->admin)
            ->patchJson("/api/admin/menus/{$menu->id}/availability", [
                'is_available' => false,
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('menus', [
            'id' => $menu->id,
            'is_available' => false,
        ]);
    }

    public function test_delete_menu(): void
    {
        $menu = Menus::factory()->create();

        $response = $this->actingAs($this->admin)
            ->deleteJson("/api/admin/menus/{$menu->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Menu Deleted Successfully']);
    }

    public function test_unauthenticated_user_cannot_access_admin_menus(): void
    {
        $response = $this->getJson('/api/admin/menus');

        $response->assertStatus(401);
    }

    public function test_non_admin_cannot_access_admin_menus(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);

        $response = $this->actingAs($customer)
            ->getJson('/api/admin/menus');

        $response->assertStatus(403);
    }
}