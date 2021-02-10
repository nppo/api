<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'title'       => $this->faker->sentence(mt_rand(2, 6)),
            'description' => $this->faker->text,
        ];
    }
}
