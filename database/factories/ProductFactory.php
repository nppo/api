<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enumerators\ProductTypes;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'title'        => $this->faker->sentence(mt_rand(2, 6)),
            'type'         => $this->faker->randomElement(ProductTypes::asArray()),
            'summary'      => $this->faker->text,
            'description'  => $this->faker->text,
            'published_at' => $this->faker->dateTimeBetween('-10 years', now()),
        ];
    }
}
