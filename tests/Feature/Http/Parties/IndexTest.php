<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Parties;

use App\Models\Party;
use Tests\TestCase;

class IndexTest extends TestCase
{
    protected string $route;

    protected function setUp(): void
    {
        parent::setUp();

        $this->route = route('api.parties.index');
    }

    /** @test */
    public function guests_are_able_to_perform_the_request(): void
    {
        $this
            ->getJson($this->route)
            ->assertOk();
    }

    /** @test */
    public function users_are_able_to_perform_the_request(): void
    {
        $this
            ->performAs($this->getUser([]))
            ->getJson($this->route)
            ->assertOk();
    }

    /** @test */
    public function response_contains_the_right_amount_of_total_entities(): void
    {
        Party::factory()->times(mt_rand(15, 25))->create();

        $this
            ->getJson($this->route)
            ->assertJsonFragment(['total' => Party::count()]);
    }
}
