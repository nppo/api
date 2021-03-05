<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Person;
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
                $person->only('about')
            )
            ->assertOk()
            ->assertJsonFragment(['about' => $newAbout]);
    }
}
