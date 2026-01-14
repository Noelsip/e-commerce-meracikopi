<?php

namespace Database\Factories;

use App\Models\Menus;
use Illuminate\Database\Eloquent\Factories\Factory;

class MenusFactory extends Factory
{
    protected $model = Menus::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->sentence(),
            'price' => $this->faker->numberBetween(15000, 50000),
            'image_path' => '/images/' . $this->faker->slug() . '.jpg',
            'is_available' => true,
        ];
    }

    public function unavailable(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_available' => false,
        ]);
    }
}