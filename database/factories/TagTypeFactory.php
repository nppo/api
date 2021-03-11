<?php

namespace Database\Factories;

use App\Models\TagType;
use Illuminate\Database\Eloquent\Factories\Factory;

class TagTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TagType::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => strtolower($this->faker->word)
        ];
    }
}
