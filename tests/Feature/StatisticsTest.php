<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enumerators\Entities;
use Illuminate\Support\Str;
use Tests\TestCase;

class StatisticsTest extends TestCase
{
    /** @test */
    public function it_can_get_all_entity_statistics(): void
    {
        foreach (Entities::asArray() as $entity) {
            ('App\Models\\' . Str::studly($entity))::factory()
                ->count(mt_rand(5, 10))
                ->create();
        }

        $response = $this
            ->getJson(route('api.statistics.entities'));

        $response
            ->assertOk();

        foreach (Entities::asArray() as $entity) {
            $response->assertJsonFragment([
                'name'  => $entity,
                'count' => ('\App\Models\\' . Str::studly($entity))::count(),
            ]);
        }
    }
}
