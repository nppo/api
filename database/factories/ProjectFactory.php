<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        return [
            'title'       => $this->faker->sentence(mt_rand(2, 6)),
            'purpose'     => $this->faker->text,
            'description' => $this->faker->text,
        ];
    }
}
