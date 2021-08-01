<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Keywords;

use App\Enumerators\Permissions;
use App\Http\Resources\KeywordResource;
use App\Models\Keyword;
use Illuminate\Support\Arr;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    protected string $route;

    protected Keyword $keyword;

    protected function setUp(): void
    {
        parent::setUp();

        $this->keyword = Keyword::factory()->create();

        $this->route = route('api.keywords.update', $this->keyword);
    }

    /** @test */
    public function guests_are_not_able_to_perform_the_request(): void
    {
        $this
            ->putJson($this->route, Keyword::factory()->make()->toArray())
            ->assertUnauthorized();
    }

    /** @test */
    public function users_are_not_able_to_perform_the_request(): void
    {
        $this
            ->performAs($this->getUser([], []))
            ->putJson($this->route, Keyword::factory()->make()->toArray())
            ->assertForbidden();
    }

    /** @test */
    public function users_with_the_right_permission_are_able_to_perform_the_request(): void
    {
        $this
            ->performAs($this->getUser([], [Permissions::KEYWORD_UPDATE]))
            ->putJson($this->route, Keyword::factory()->make()->toArray())
            ->assertOk();
    }

    /** @test */
    public function the_response_contains_the_new_information_from_the_entity(): void
    {
        $keyword = Keyword::factory()->make();

        $this
            ->performAs($this->getUser([], [Permissions::KEYWORD_UPDATE]))
            ->putJson($this->route, $keyword->toArray())
            ->assertJsonFragment(
                Arr::except(
                    KeywordResource::make($keyword)->resolve(),
                    $keyword->getKeyName()
                )
            );
    }
}
