<?php

namespace Database\Factories;

use App\Models\Tables;
use Illuminate\Database\Eloquent\Factories\Factory;

class TablesFactory extends Factory
{
    protected $model = Tables::class;

    public function definition(): array
    {
        return [
            'table_number' => $this->faker->unique()->numberBetween(1, 50),
            'capacity' => $this->faker->numberBetween(2, 8),
            'status' => 'available',
        ];
    }
}