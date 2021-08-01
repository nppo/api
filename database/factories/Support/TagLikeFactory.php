<?php

declare(strict_types=1);

namespace Database\Factories\Support;

use Illuminate\Database\Eloquent\Factories\Factory;

abstract class TagLikeFactory extends Factory
{
    protected string $tagType;

    public function definition(): array
    {
        return [
            'type'  => $this->tagType,
            'label' => $this->faker->unique()->words(2, true),
        ];
    }
}
