<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enumerators\TagTypes;
use App\Models\TagType;
use Illuminate\Database\Seeder;

class TagTypeSeeder extends Seeder
{
    public function run(): void
    {
        foreach (TagTypes::asArray() as $type) {
            TagType::factory()->create(['name' => $type]);
        }
    }
}
