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
            if ($default = config('structures.' . $structure->label)) {
                $sequence = [];

                foreach ($default as $attribute) {
                    $sequence[] = ['label' => $attribute];
                }

                Attribute::factory()
                    ->times(count($default))
                    ->sequence(...$sequence)
                    ->create([
                        'structure_id' => $structure->id,
                    ]);
            } else {
                Attribute::factory()
                    ->times(rand(self::MIN_ATTRIBUTES, self::MAX_ATTRIBUTES))
                    ->create([
                        'structure_id' => $structure->id,
                    ]);
            }
        });
    }
}
