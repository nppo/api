<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Keywords;

use App\Enumerators\Permissions;
use App\Http\Resources\KeywordResource;
use App\Models\Keyword;
use Illuminate\Support\Arr;
use Tests\TestCase;

class StoreTest extends TestCase
{
    protected string $route;

    protected function setUp(): void
    {
        parent::setUp();

        $this->route = route('api.keywords.store');
    }

    /** @test */
    public function guests_are_not_able_to_perform_the_request(): void
    {
        $this
            ->postJson($this->route, Keyword::factory()->make()->toArray())
            ->assertUnauthorized();
    }

    /** @test */
    public function users_are_not_able_to_perform_the_request(): void
    {
        $this
            ->performAs($this->getUser([], []))
            ->postJson($this->route, Keyword::factory()->make()->toArray())
            ->assertForbidden();
    }

    /** @test */
    public function users_with_the_right_permission_are_able_to_perform_the_request(): void
    {
        $this
            ->performAs($this->getUser([], [Permissions::KEYWORD_CREATE]))
            ->postJson($this->route, Keyword::factory()->make()->toArray())
            ->assertCreated();
    }

    /** @test */
    public function the_response_contains_the_information_from_the_new_entity(): void
    {
        $keyword = Keyword::factory()->make();

        $this
            ->performAs($this->getUser([], [Permissions::KEYWORD_CREATE]))
            ->postJson($this->route, $keyword->toArray())
            ->assertJsonFragment(
                Arr::except(
                    KeywordResource::make($keyword)->resolve(),
                    $keyword->getKeyName()
                )
            );
    }
}
