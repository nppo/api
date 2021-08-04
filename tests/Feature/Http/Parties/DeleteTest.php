<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Parties;

use App\Enumerators\Permissions;
use App\Models\Party;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    protected string $route;

    protected function setUp(): void
    {
        parent::setUp();

        $this->route = route('api.parties.destroy', Party::factory()->create());
    }

    /** @test */
    public function guests_are_not_able_to_perform_the_request(): void
    {
        $this
            ->deleteJson($this->route)
            ->assertUnauthorized();
    }

    /** @test */
    public function users_are_not_able_to_perform_the_request(): void
    {
        $this
            ->performAs($this->getUser([], []))
            ->deleteJson($this->route)
            ->assertForbidden();
    }

    /** @test */
    public function users_with_the_right_permission_are_able_to_perform_the_request(): void
    {
        $this
            ->performAs($this->getUser([], [Permissions::PARTY_DELETE]))
            ->deleteJson($this->route)
            ->assertOk();
    }
}
