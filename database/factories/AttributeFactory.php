<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Attribute;
use App\Models\Structure;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttributeFactory extends Factory
{
    protected $model = Attribute::class;

    public function definition(): array
    {
        return [
            'label'        => $this->faker->unique()->word,
            'structure_id' => function (): Factory {
                return Structure::factory();
            },
        ];
    }
}
