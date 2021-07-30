<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enumerators\Disks;
use App\Enumerators\MediaCollections;
use App\Models\Party;
use App\Models\Person;
use App\Models\Product;
use App\Models\Project;
use App\Models\Tag;
use App\Models\User;
use Database\Seeders\Support\SeedsMetadata;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProjectSeeder extends Seeder
{
    use SeedsMetadata;

    private const MAX_PROJECTS = 250;

    private const MAX_TAGS = 10;

    private const MAX_PEOPLE = 10;

    private const MAX_PARTIES = 3;

    private const MAX_PRODUCTS = 2;

    private ?array $seedingOptions = null;

    public function run(): void
    {
        $this->command->getOutput()->progressStart(self::MAX_PROJECTS);

        $tags = Tag::all();
        $people = Person::all();
        $parties = Party::all();
        $users = User::all();
        $products = Product::all();

        Project::factory()
            ->times(self::MAX_PROJECTS)
            ->create()
            ->each(function (Model $project) use ($tags, $people, $parties, $users, $products): void {
                /** @var Project $project */
                $this->attachTags($project, $tags);
                $this->attachPeople($project, $people);
                $this->attachParties($project, $parties);
                $this->attachLikes($project, $users);
                $this->attachProducts($project, $products);

                if (rand(0, 1)) {
                    $this->attachProjectPicture($project);
                }

                $this->seedMetadata($project);

                $this->command->getOutput()->progressAdvance(1);
            });

        $this->command->getOutput()->progressFinish();
    }

    /**
     * @param Project    $project
     * @param Collection $people
     *
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    private function attachPeople(Project $project, Collection $people): void
    {
        $pivotAttributes = [];

        // Women and children first
        $peopleToSave = $people
            ->random(mt_rand(1, self::MAX_PEOPLE))
            ->each(function (Person $person, $key) use (&$pivotAttributes): void {
                $pivotAttributes[] = ['is_owner' => ($key === 0)];
            });

        $project
            ->people()
            ->saveMany($peopleToSave, $pivotAttributes);
    }

    private function attachParties(Project $project, Collection $parties): void
    {
        $pivotAttributes = [];

        $partiesToSave = $parties
            ->random(mt_rand(1, self::MAX_PARTIES))
            ->each(function () use (&$pivotAttributes): void {
                $pivotAttributes[] = ['is_owner' => false];
            });

        $project
            ->parties()
            ->saveMany($partiesToSave, $pivotAttributes);
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

    private function attachProjectPicture(Project $project): void
    {
        $randomImage = Arr::random($this->getSeedingOptions());

        $project
            ->addMediaFromDisk(
                $randomImage,
                Disks::SEEDING
            )
            ->usingFileName(Str::uuid()->toString() . '.' . Str::afterLast($randomImage, '.'))
            ->preservingOriginal()
            ->toMediaCollection(MediaCollections::PROJECT_PICTURE);
    }

    private function getSeedingOptions(): array
    {
        if (is_null($this->seedingOptions)) {
            $this->seedingOptions = Storage::disk(Disks::SEEDING)
                ->files('projects');
        }

        return $this->seedingOptions;
    }
}
