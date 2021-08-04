<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Parties;

use App\Http\Resources\PartyResource;
use App\Models\Party;
use Illuminate\Database\Eloquent\Model;
use Tests\TestCase;

class ShowTest extends TestCase
{
    protected string $route;

    protected Model $party;

    protected function setUp(): void
    {
        parent::setUp();

        $this->party = Party::factory()->create();

        $this->route = route('api.parties.show', $this->party);
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
    public function response_contains_the_right_information_from_the_entity(): void
    {
        $this
            ->getJson($this->route)
            ->assertJsonFragment(PartyResource::make($this->party)->resolve());
    }
}
