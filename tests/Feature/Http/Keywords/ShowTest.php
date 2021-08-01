<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Keywords;

use App\Http\Resources\KeywordResource;
use App\Models\Keyword;
use Tests\TestCase;

class ShowTest extends TestCase
{
    protected string $route;

    protected Keyword $keyword;

    protected function setUp(): void
    {
        parent::setUp();

        $this->keyword = Keyword::factory()->create();

        $this->route = route('api.keywords.show', $this->keyword);
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
            ->assertJsonFragment(KeywordResource::make($this->keyword)->resolve());
    }
}
