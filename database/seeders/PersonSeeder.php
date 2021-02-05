<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Person;
use Illuminate\Database\Seeder;

class PersonSeeder extends Seeder
{
    public function run(): void
    {
        Person::factory()->times(30)->create();
    }
}
