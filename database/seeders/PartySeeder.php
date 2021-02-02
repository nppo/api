<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Party;
use Illuminate\Database\Seeder;

class PartySeeder extends Seeder
{
    public function run(): void
    {
        Party::factory()->times(30)->create();
    }
}
