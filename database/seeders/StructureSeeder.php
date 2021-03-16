<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\Structure;
use Illuminate\Database\Seeder;

class StructureSeeder extends Seeder
{
    private const MIN_ATTRIBUTES = 5;

    private const MAX_ATTRIBUTES = 20;

    public function run(): void
    {
        Structure::each(function (Structure $structure): void {
            Attribute::factory()
                ->times(rand(self::MIN_ATTRIBUTES, self::MAX_ATTRIBUTES))
                ->create([
                    'structure_id' => $structure->id,
                ]);
        });
    }
}
