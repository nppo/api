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

        $this->assertEmpty($person->media);

        dd($this
            ->putJson(
                route('api.people.update', [$person->id]),
                [
                    'profile_picture' => UploadedFile::fake()->image('avatar.jpg', 200, 200),
                ]
            )->json());

        $this->assertNotEmpty($person->media()->get());
    }

    /** @test */
    public function adding_a_profile_picture_will_validate_dimensions(): void
    {
        $person = Person::factory()->create();

        $this
            ->putJson(
                route('api.people.update', [$person->id]),
                [
                    'profile_picture' => UploadedFile::fake()->image('avatar.jpg', 150, 200),
                ]
            )
            ->assertJsonValidationErrors('profile_picture');

        $this->assertEmpty($person->media()->get());
    }
}
