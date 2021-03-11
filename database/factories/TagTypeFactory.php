<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\TagType;
use Illuminate\Database\Eloquent\Factories\Factory;

class TagTypeFactory extends Factory
{
    protected $model = TagType::class;

    public function definition(): array
    {
        return [
            'name' => strtolower($this->faker->word),
        ];
    }
}
