<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Structure;
use Illuminate\Database\Eloquent\Factories\Factory;

class StructureFactory extends Factory
{
    protected $model = Structure::class;

    public function definition(): array
    {
        return [
            'label' => $this->faker->word,
        ];
    }
}
