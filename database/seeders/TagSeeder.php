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

    private const MAX_THEMES = 30;

    public function run(): void
    {
        Tag::factory()
            ->count(self::MAX_TAGS)
            ->state(new Sequence(
                ['type' => TagTypes::SKILL],
                ['type' => TagTypes::KEYWORD],
            ))
            ->create();

        Tag::factory()
            ->count(self::MAX_THEMES)
            ->{TagTypes::THEME}()
            ->create();
    }
}
