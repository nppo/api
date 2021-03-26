<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enumerators\TagTypes;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;

class TagFactory extends Factory
{
    protected $model = Tag::class;

    public function definition(): array
    {
        return [
            'label' => $this->faker->unique()->words(2, true),
        ];
    }

    public function theme(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'label' => function (): string {
                    $label = ucfirst($this->faker->word);

                    if (mt_rand(0, 3)) {
                        $label .= ' & ' . $this->faker->word;
                    }

                    return $label;
                },
                'type' => TagTypes::THEME,
            ];
        });
    }
}
