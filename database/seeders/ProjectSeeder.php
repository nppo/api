<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Party;
use App\Models\Person;
use App\Models\Product;
use App\Models\Project;
use App\Models\Tag;
use App\Models\Theme;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class ProjectSeeder extends Seeder
{
    private const MAX_PROJECTS = 250;

    private const MAX_TAGS = 10;

    private const MAX_PEOPLE = 10;

    private const MAX_THEMES = 3;

    private const MAX_PARTIES = 3;

    private const MAX_PRODUCTS = 2;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $this->command->getOutput()->progressStart(self::MAX_PROJECTS);

        $themes = Theme::all();
        $tags = Tag::all();
        $people = Person::all();
        $parties = Party::all();
        $users = User::all();
        $products = Product::all();

        Project::factory()
            ->times(self::MAX_PROJECTS)
            ->create()
            ->each(function (Project $project) use ($themes, $tags, $people, $parties, $users, $products): void {
                $this->attachThemes($project, $themes);
                $this->attachTags($project, $tags);
                $this->attachPeople($project, $people);
                $this->attachParties($project, $parties);
                $this->attachLikes($project, $users);
                $this->attachProducts($project, $products);

                $this->command->getOutput()->progressAdvance(1);
            });

        $this->command->getOutput()->progressFinish();
    }

    private function attachThemes(Project $project, Collection $themes): void
    {
        $project
            ->themes()
            ->saveMany($themes->random(mt_rand(1, self::MAX_THEMES)));
    }

    private function attachPeople(Project $project, Collection $people): void
    {
        $project
            ->people()
            ->saveMany(
                $people->random(mt_rand(1, self::MAX_PEOPLE))
            );
    }

    private function attachParties(Project $project, Collection $parties): void
    {
        $project
            ->parties()
            ->saveMany(
                $parties->random(mt_rand(1, self::MAX_PARTIES))
            );
    }

    private function attachTags(Project $project, Collection $tags): void
    {
        $project
            ->tags()
            ->saveMany(
                $tags->random(mt_rand(0, self::MAX_TAGS))
            );
    }

    private function attachLikes(Project $project, Collection $users): void
    {
        $project
            ->likes()
            ->saveMany(
                $users->random(mt_rand(0, $users->count()))
            );
    }

    private function attachProducts(Project $project, Collection $products): void
    {
        $project
            ->products()
            ->saveMany(
                $products->random(mt_rand(1, self::MAX_PRODUCTS))
            );
    }
}
