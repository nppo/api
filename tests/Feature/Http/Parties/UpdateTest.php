<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Parties;

use App\Enumerators\Permissions;
use App\Http\Resources\PartyResource;
use App\Models\Party;
use Illuminate\Support\Arr;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    protected string $route;

    protected Party $party;

    protected function setUp(): void
    {
        parent::setUp();

        $this->party = Party::factory()->create();

        $this->route = route('api.parties.update', $this->party);
    }

    /** @test */
    public function guests_are_not_able_to_perform_the_request(): void
    {
        $this
            ->putJson($this->route, Party::factory()->make()->toArray())
            ->assertUnauthorized();
    }

    /** @test */
    public function users_are_not_able_to_perform_the_request(): void
    {
        $this
            ->performAs($this->getUser([], []))
            ->putJson($this->route, Party::factory()->make()->toArray())
            ->assertForbidden();
    }

    /** @test */
    public function users_with_the_right_permission_are_able_to_perform_the_request(): void
    {
        $this
            ->performAs($this->getUser([], [Permissions::PARTY_UPDATE]))
            ->putJson($this->route, Party::factory()->make()->toArray())
            ->assertOk();
    }

    /** @test */
    public function the_response_contains_the_new_information_from_the_entity(): void
    {
        $party = Party::factory()->make();

        $this
            ->performAs($this->getUser([], [Permissions::PARTY_UPDATE]))
            ->putJson($this->route, $party->toArray())
            ->assertJsonFragment(
                Arr::except(
                    PartyResource::make($party)->resolve(),
                    $party->getKeyName()
                )
            );
    }
}
