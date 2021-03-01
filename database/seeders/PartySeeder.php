<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Party;
use Illuminate\Database\Seeder;

class PartySeeder extends Seeder
{
    private const MAX_PARTIES = 30;

    private const MAX_AFFILIABLE_PARTIES = 2;

    public function run(): void
    {
        $parties = Party::factory()
            ->times(self::MAX_PARTIES)
            ->create();

        $parties->each(function (Party $party) use ($parties): void {
            $party
                ->parties()
                ->saveMany(
                    $parties
                        ->filter(function (Party $p) use ($party) {
                            return $p->id !== $party->id;
                        })
                        ->random(mt_rand(0, self::MAX_AFFILIABLE_PARTIES))
                );
        });
    }
}
