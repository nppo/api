<?php

namespace Database\Factories;

use App\Models\Theme;
use Illuminate\Database\Eloquent\Factories\Factory;

class ThemeFactory extends Factory
{
    protected $model = Theme::class;

    public function definition()
    {
        return [
            'label' => function (): string {
                $label = ucfirst($this->faker->word);

                if (mt_rand(0, 3)) {
                    $label .= ' & ' . $this->faker->word;
                }

                return $label;
            },
        ];
    }
}
