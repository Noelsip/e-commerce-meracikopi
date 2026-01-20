<?php

use App\Models\User;
use App\Models\Menus;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Auth;

beforeEach(function () {
    $this->admin = User::factory()->create([
        'role' => 'admin',
        'email' => 'admin@meracikopi.com',
        'password' => 'password'
    ]);
    
    // Create Sanctum token untuk admin
    Sanctum::actingAs($this->admin, ['*']);
});

test('admin can get all menus', function () {
    Menus::factory()->count(3)->create();

    $response = $this->getJson('/api/admin/menus');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'data' => [
                    '*' => ['id', 'name', 'description', 'price', 'is_available']
                ],
                'current_page',
                'per_page',
                'total'
            ]
        ]);
});

test('admin can create menu', function () {
    $menuData = [
        'name' => 'Kopi Susu Gula Aren',
        'description' => 'Kopi susu dengan gula aren premium',
        'price' => 28000,
        'image_path' => '/images/kopi.jpg',
        'is_available' => true,
    ];

    $response = $this->postJson('/api/admin/menus', $menuData);

    $response->assertStatus(201)
        ->assertJson([
            'message' => 'Menu Created',
            'data' => [
                'name' => 'Kopi Susu Gula Aren',
            ]
        ]);

    $this->assertDatabaseHas('menus', ['name' => 'Kopi Susu Gula Aren']);
});

test('admin can get menu by id', function () {
    $menu = Menus::factory()->create();

    $response = $this->getJson("/api/admin/menus/{$menu->id}");

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => ['id', 'name', 'description', 'price', 'is_available']
        ]);
});

test('admin can update menu', function () {
    $menu = Menus::factory()->create();

    $response = $this->putJson("/api/admin/menus/{$menu->id}", [
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
});

test('admin can update menu availability', function () {
    $menu = Menus::factory()->create(['is_available' => true]);

    $response = $this->patchJson("/api/admin/menus/{$menu->id}/availability", [
        'is_available' => false,
    ]);

    $response->assertStatus(200);
    $this->assertDatabaseHas('menus', [
        'id' => $menu->id,
        'is_available' => false,
    ]);
});

test('admin can delete menu', function () {
    $menu = Menus::factory()->create();

    $response = $this->deleteJson("/api/admin/menus/{$menu->id}");

    $response->assertStatus(200)
        ->assertJson(['message' => 'Menu Deleted Successfully']);
});

test('unauthenticated user cannot access admin menus', function () {
    // Create a fresh request without Sanctum authentication
    $this->refreshApplication();
    
    $response = $this->getJson('/api/admin/menus');

    $response->assertStatus(401);
});

test('non-admin cannot access admin menus', function () {
    $customer = User::factory()->create(['role' => 'customer']);
    Sanctum::actingAs($customer, ['*']);

    $response = $this->getJson('/api/admin/menus');

    $response->assertStatus(403);
});