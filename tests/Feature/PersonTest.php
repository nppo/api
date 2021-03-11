<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enumerators\TagTypes;
use App\Models\Person;
use App\Models\Tag;
use App\Models\TagType;
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
                $person->only(['about'])
            )
            ->assertOk()
            ->assertJsonFragment(['about' => $newAbout]);
    }

    /** @test */
    public function updating_will_associate_media_with_the_person(): void
    {
        $person = Person::factory()->create();

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

    /** @test */
    public function it_can_update_a_person_with_skills(): void
    {
        $person = Person::factory()->create();

        $skillType = TagType::where('name', TagTypes::SKILL)->first()
            ?: TagType::factory()->create(['name' => TagTypes::SKILL]);

        $skills = Tag::factory()->times(10)->create([
            'type_id' => $skillType->id,
        ]);

        $formattedSkills = $skills->map->only(['id', 'label'])->toArray();

        $this
            ->putJson(
                route('api.people.update', [$person->id]),
                $person->only(['first_name', 'last_name', 'about']) +
                ['skills' => $formattedSkills]
            )
            ->assertOk()
            ->assertJsonFragment([
                'skills' => $formattedSkills,
            ]);
    }
}
