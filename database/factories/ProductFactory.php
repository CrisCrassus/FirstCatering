<?php

namespace Database\Factories;

use App\Models\Product;
use App\Traits\HasIdentifier;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    use HasIdentifier;

    public function definition(): array
    {
        $p = new Product();

        return [
            'name' => fake()->word(),
            'identifier' => $p->generateIdentifier($p, 7, true),
            'price' => mt_rand(5, 50),
            'quantity' => mt_rand(10, 30),
        ];
    }
}
