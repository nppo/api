<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Product;
use App\Models\Theme;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'theme_id'    => Theme::factory()->create()->id,
            'title'       => $this->faker->sentence(mt_rand(2, 6)),
            'description' => $this->faker->text,
            'views'       => $this->faker->randomNumber(5)
        ];
    }
}
