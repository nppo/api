<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enumerators\TagTypes;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    private const MAX_TAGS = 100;

    public function run(): void
    {
        Tag::factory()
            ->count(self::MAX_TAGS)
            ->state(new Sequence(
                ['type' => TagTypes::SKILL],
                ['type' => null],
            ))
            ->create();
    }
}
