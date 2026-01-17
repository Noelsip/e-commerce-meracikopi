<?php

use App\Models\Menus;

test('customer can get all catalogs', function () {
    Menus::factory()->count(5)->create(['is_available' => true]);

    $response = $this->getJson('/api/customer/catalogs');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => ['id', 'name', 'description', 'price', 'is_available']
            ]
        ]);
});

test('customer can get available catalogs only', function () {
    Menus::factory()->count(3)->create(['is_available' => true]);
    Menus::factory()->count(2)->create(['is_available' => false]);

    $response = $this->getJson('/api/customer/catalogs?is_available=1');

    $response->assertStatus(200);
    
    $data = $response->json('data');
    foreach ($data as $menu) {
        expect($menu['is_available'])->toBeTrue();
    }
});

test('customer can search catalog by name', function () {
    Menus::factory()->create(['name' => 'Kopi Susu']);
    Menus::factory()->create(['name' => 'Teh Manis']);

    $response = $this->getJson('/api/customer/catalogs?search=Kopi');

    $response->assertStatus(200);
    
    $data = $response->json('data');
    expect($data)->not->toBeEmpty();
    expect($data[0]['name'])->toContain('Kopi');
});

test('customer can get catalog by id', function () {
    $menu = Menus::factory()->create();

    $response = $this->getJson("/api/customer/catalogs/{$menu->id}");

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => ['id', 'name', 'description', 'price', 'is_available']
        ]);
});

test('catalog not found returns 404', function () {
    $response = $this->getJson('/api/customer/catalogs/99999');

    $response->assertStatus(404)
        ->assertJson(['message' => 'Menu not found']);
});