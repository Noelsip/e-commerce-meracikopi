<?php

namespace Tests\Feature\Customer;

use Tests\TestCase;
use App\Models\Menus;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CatalogApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_all_catalogs(): void
    {
        Menus::factory()->count(5)->create(['is_available' => true]);

        $response = $this->getJson('/api/customer/catalogs');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'description', 'price', 'is_available']
                ]
            ]);
    }

    public function test_get_available_catalogs_only(): void
    {
        Menus::factory()->count(3)->create(['is_available' => true]);
        Menus::factory()->count(2)->create(['is_available' => false]);

        $response = $this->getJson('/api/customer/catalogs?is_available=1');

        $response->assertStatus(200);
        
        $data = $response->json('data');
        foreach ($data as $menu) {
            $this->assertTrue($menu['is_available']);
        }
    }

    public function test_search_catalog_by_name(): void
    {
        Menus::factory()->create(['name' => 'Kopi Susu']);
        Menus::factory()->create(['name' => 'Teh Manis']);

        $response = $this->getJson('/api/customer/catalogs?search=Kopi');

        $response->assertStatus(200);
        
        $data = $response->json('data');
        $this->assertNotEmpty($data);
        $this->assertStringContainsString('Kopi', $data[0]['name']);
    }

    public function test_get_catalog_by_id(): void
    {
        $menu = Menus::factory()->create();

        $response = $this->getJson("/api/customer/catalogs/{$menu->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['id', 'name', 'description', 'price', 'is_available']
            ]);
    }

    public function test_catalog_not_found(): void
    {
        $response = $this->getJson('/api/customer/catalogs/99999');

        $response->assertStatus(404)
            ->assertJson(['message' => 'Menu not found']);
    }
}