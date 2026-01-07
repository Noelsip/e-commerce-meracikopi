<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Menus;

class MenuSeederAdmin extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = [
            [
                'name' => 'Espresso',
                'description' => 'Kopi espresso single shot',
                'price' => 20000,
                'image_path' => '/images/espresso.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'Americano',
                'description' => 'Espresso dengan air panas',
                'price' => 25000,
                'image_path' => '/images/americano.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'Cappuccino',
                'description' => 'Espresso dengan steamed milk dan foam',
                'price' => 30000,
                'image_path' => '/images/cappuccino.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'Latte',
                'description' => 'Espresso dengan banyak steamed milk',
                'price' => 32000,
                'image_path' => '/images/latte.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'Mocha',
                'description' => 'Espresso dengan cokelat dan steamed milk',
                'price' => 35000,
                'image_path' => '/images/mocha.jpg',
                'is_available' => false,
            ],
        ];

        foreach ($menus as $menu) {
            Menus::create($menu);
        }
    }
}
