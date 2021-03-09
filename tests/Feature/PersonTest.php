<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Person;
use App\Models\Tag;
use Tests\TestCase;

class PersonTest extends TestCase
{
    /** @test */
    public function it_can_update_a_person(): void
    {
        $person = Person::factory()->create();

        $newAbout = '::about::';

        $person->about = $newAbout;

        $this
            ->putJson(
                route('api.people.update', [$person->id]),
                $person->only(['first_name', 'last_name', 'about'])
            )
            ->assertOk()
            ->assertJsonFragment(['about' => $newAbout]);
    }

    /** @test */
    public function it_can_update_a_person_with_tags(): void
    {
        $person = Person::factory()->create();
        $tags = Tag::factory()->times(10)->create();
        $formattedTags = $tags->map->only(['id', 'label'])->toArray();

        $newAbout = '::about::';

        $person->about = $newAbout;

        $this
            ->putJson(
                route('api.people.update', [$person->id]),
                $person->only(['first_name', 'last_name', 'about']) +
                ['tags' => $formattedTags]
            )
            ->assertOk()
            ->assertJsonFragment([
                'about' => $newAbout,
                'tags'  => $formattedTags,
            ]);
    }
}
