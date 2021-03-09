<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Person;
use Illuminate\Http\UploadedFile;
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

    /** @test */
    public function updating_will_associate_media_with_the_person(): void
    {
        $person = Person::factory()->create();

        $newAbout = '::about::';

        $person->about = $newAbout;

        $this->assertEmpty($person->media);

        $this
            ->putJson(
                route('api.people.update', [$person->id]),
                [
                    'profile_picture' => UploadedFile::fake()->image('avatar.jpg', 200, 200),
                ]
            )
            ->assertOk();

        $this->assertNotEmpty($person->media()->get());
    }
}
