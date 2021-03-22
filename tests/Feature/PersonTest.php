<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enumerators\TagTypes;
use App\Models\Tag;
use Illuminate\Http\UploadedFile;
use Laravel\Passport\Passport;
use Tests\TestCase;

class PersonTest extends TestCase
{
    /** @test */
    public function it_can_update_a_person(): void
    {
        $user = $this->getUser();

        Passport::actingAs($user);

        $newAbout = '::about::';

        $user->person->about = $newAbout;

        $this
            ->putJson(
                route('api.people.update', $user->person->id),
                $user->person->only('about')
            )
            ->assertOk()
            ->assertJsonFragment(['about' => $newAbout]);
    }

    /** @test */
    public function updating_will_associate_media_with_the_person(): void
    {
        $user = $this->getUser();

        Passport::actingAs($user);

        $this->assertEmpty($user->person->media);

        $this
            ->putJson(
                route('api.people.update', $user->person->id),
                [
                    'profile_picture' => UploadedFile::fake()->image('avatar.jpg', 200, 200),
                ]
            )
            ->assertOk();

        $this->assertNotEmpty($user->person->media()->get());
    }

    /** @test */
    public function adding_a_profile_picture_will_validate_dimensions(): void
    {
        $user = $this->getUser();

        Passport::actingAs($user);

        $this
            ->putJson(
                route('api.people.update', $user->person->id),
                [
                    'profile_picture' => UploadedFile::fake()->image('avatar.jpg', 150, 200),
                ]
            )
            ->assertJsonValidationErrors('profile_picture');

        $this->assertEmpty($user->person->media()->get());
    }

    /** @test */
    public function it_can_update_a_person_with_themes(): void
    {
        $user = $this->getUser();

        Passport::actingAs($user);

        $themes = Tag::factory()->times(10)->create([
            'type' => TagTypes::THEME,
        ]);

        $formattedThemes = $themes->map->only(['id', 'label'])->toArray();

        $this
            ->putJson(
                route('api.people.update', [$user->person->id]),
                ['themes' => $formattedThemes]
            )
            ->assertOk()
            ->assertJsonFragment([
                'themes' => $formattedThemes,
            ]);
    }

    /** @test */
    public function it_can_update_a_person_with_skills(): void
    {
        $user = $this->getUser();

        Passport::actingAs($user);

        $skills = Tag::factory()->times(2)->create([
            'type' => TagTypes::SKILL,
        ]);

        $formattedSkills = $skills->map->only(['id', 'label'])->toArray();

        $this
            ->putJson(
                route('api.people.update', [$user->person->id]),
                ['skills' => $formattedSkills]
            )
            ->assertOk()
            ->assertJsonFragment([
                'skills' => $formattedSkills,
            ]);
    }

    /** @test */
    public function it_can_update_a_person_and_remove_all_skills(): void
    {
        $user = $this->getUser();

        Passport::actingAs($user);

        $skills = Tag::factory()->times(10)->create([
            'type' => TagTypes::SKILL,
        ]);

        $user->person->skills()->sync($skills);

        $this
            ->putJson(
                route('api.people.update', [$user->person->id]),
                ['skills' => null]
            )
            ->assertOk()
            ->assertJsonFragment([
                'skills' => [],
            ]);
    }

    /** @test */
    public function it_cannot_update_a_person_with_less_than_one_theme(): void
    {
        $user = $this->getUser();

        Passport::actingAs($user);

        $this
            ->putJson(
                route('api.people.update', [$user->person->id]),
                ['themes' => null]
            )
            ->assertJsonValidationErrors('themes');
    }

    /** @test */
    public function when_skills_are_provided_during_update_that_do_not_exist_yet_it_will_create_them(): void
    {
        $user = $this->getUser();

        Passport::actingAs($user);

        $skills = Tag::factory()->times(2)->make([
            'type' => TagTypes::SKILL,
        ]);

        $original = Tag::count();

        $this
            ->putJson(
                route('api.people.update', [$user->person->id]),
                ['skills' => $skills]
            )
            ->assertOk();

        $this->assertEquals(
            $original + 2,
            Tag::count()
        );
    }
}
