<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    private const MAX_TAGS = 100;

    public function run(): void
    {
        Tag::factory()
            ->count(self::MAX_TAGS)
            ->create();
    }
}
