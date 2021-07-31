<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enumerators\TagTypes;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    private array $types = [
        TagTypes::SKILL   => 100,
        TagTypes::THEME   => 50,
        TagTypes::KEYWORD => 50,
    ];

    public function run(): void
    {
        foreach ($this->types as $type => $amount) {
            Tag::factory()
                ->times($amount)
                ->create([
                'type' => $type,
            ]);
        }
    }
}
