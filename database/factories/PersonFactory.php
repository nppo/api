<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Person;
use Illuminate\Database\Eloquent\Factories\Factory;

class PersonFactory extends Factory
{
    protected $model = Person::class;

    public function definition(): array
    {
        $prefixes = ['de', 'van', 'van der'];

        return [
            'first_name'       => $this->faker->firstName,
            'last_name_prefix' => $this->faker->optional(0.1)->randomElement($prefixes),
            'last_name'        => $this->faker->lastName,
            'email'            => $this->faker->email,
            'function'         => $this->faker->jobTitle,
            'phone'            => $this->faker->phoneNumber,
        ];
    }
}
