<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Person;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PersonFactory extends Factory
{
    protected $model = Person::class;

    public function definition(): array
    {
        return [
            'identifier'  => Str::random(10),
            'first_name' => $this->faker->firstName,
            'last_name'  => $this->faker->lastName,
            'about'      => $this->faker->text,
            'email'      => $this->faker->email,
            'function'   => $this->faker->jobTitle,
            'phone'      => $this->faker->phoneNumber,
        ];
    }
}
