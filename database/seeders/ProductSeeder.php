<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enumerators\Disks;
use App\Enumerators\MediaCollections;
use App\Enumerators\ProductTypes;
use App\Models\Party;
use App\Models\Person;
use App\Models\Product;
use App\Models\Tag;
use App\Models\User;
use Database\Seeders\Support\SeedsMedia;
use Database\Seeders\Support\SeedsMetadata;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class ProductSeeder extends Seeder
{
    use SeedsMetadata, SeedsMedia;

    private const MAX_PRODUCTS = 250;

    private const MAX_TAGS_PER_PRODUCT = 10;

    private const MAX_PEOPLE = 10;

    private const MAX_PARTIES = 3;

    private array $links = [
        ProductTypes::IMAGE => [
            'https://picsum.photos/100/100',
            'https://picsum.photos/200/200',
            'https://picsum.photos/300/300',
            'https://picsum.photos/400/400',
            'https://picsum.photos/500/500',
        ],
        ProductTypes::YOUTUBE => [
            'https://www.youtube.com/embed/uDMkQTvYMs4',
            'https://www.youtube.com/embed/Tr1eDP2CqUo',
            'https://www.youtube.com/embed/DAZR0p3uCvk',
            'https://www.youtube.com/embed/dQw4w9WgXcQ',
        ],
        ProductTypes::DOCUMENT => [
            'https://filesamples.com/samples/document/docx/sample1.docx',
            'https://filesamples.com/samples/document/docx/sample2.docx',
            'https://filesamples.com/samples/document/docx/sample3.docx',
            'https://filesamples.com/samples/document/txt/sample1.txt',
            'https://filesamples.com/samples/document/txt/sample2.txt',
            'https://filesamples.com/samples/document/txt/sample3.txt',
            'https://filesamples.com/samples/document/pdf/sample1.pdf',
            'https://filesamples.com/samples/document/pdf/sample2.pdf',
            'https://filesamples.com/samples/document/pdf/sample3.pdf',
        ],
        ProductTypes::LINK => [
            'http://www.academic-bookshop.com/ourshop/prod_6547122-ECMLG-2018-
             Proceedings-of-the-14th-European-Conference-on-Management-Leadership-and-Governance-PRINT-VERSION.html',
            'https://www.aup-online.com/content/journals/10.5117/TVT2017.1.HARM',
            'https://doi.org/10.5117/TVT2017.1.HARM',
        ],
    ];

    public function run(): void
    {
        $this->command->getOutput()->progressStart(self::MAX_PRODUCTS);

        $tags = Tag::all();
        $people = Person::all();
        $parties = Party::all();
        $users = User::all();

        Product::factory()
            ->times(self::MAX_PRODUCTS)
            ->create()
            ->each(function (Model $product) use ($tags, $people, $parties, $users): void {
                /** @var Product $product */
                $this->attachTags($product, $tags);
                $this->attachPeople($product, $people);
                $this->attachParties($product, $parties);
                $this->attachLikes($product, $users);
                $this->seedProduct($product);
                $this->seedMetadata($product);

                $this->command->getOutput()->progressAdvance(1);
            });

        $this->command->getOutput()->progressFinish();
    }

    /**
     * @param Product    $product
     * @param Collection $people
     *
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    private function attachPeople(Product $product, Collection $people): void
    {
        $pivotAttributes = [];

        // Women and children first
        $peopleToSave = $people
            ->random(mt_rand(1, self::MAX_PEOPLE))
            ->each(function (Person $person, $key) use (&$pivotAttributes): void {
                $pivotAttributes[] = ['is_owner' => ($key === 0)];
            });

        $product
            ->people()
            ->saveMany($peopleToSave, $pivotAttributes);
    }

    private function attachParties(Product $product, Collection $parties): void
    {
        $pivotAttributes = [];

        $partiesToSave = $parties
            ->random(mt_rand(1, self::MAX_PARTIES))
            ->each(function () use (&$pivotAttributes): void {
                $pivotAttributes[] = ['is_owner' => false];
            });

        $product
            ->parties()
            ->saveMany($partiesToSave, $pivotAttributes);
    }

    private function attachTags(Product $product, Collection $tags): void
    {
        $product
            ->tags()
            ->saveMany(
                $tags->random(mt_rand(0, self::MAX_TAGS_PER_PRODUCT))
            );
    }

    private function attachLikes(Product $product, Collection $users): void
    {
        $product
            ->likes()
            ->saveMany(
                $users->random(mt_rand(0, $users->count()))
            );
    }

    private function seedProduct(Product $product): void
    {
        if ($this->hasMediaOptions($product->type) && $this->hasLinkOptions($product->type)) {
            if (rand(0, 1)) {
                $this->seedMedia($product);

                return;
            }
            $this->seedLink($product);

            return;
        }

        if ($this->hasMediaOptions($product->type)) {
            $this->seedMedia($product);

            return;
        }

        if ($this->hasLinkOptions($product->type)) {
            $this->seedLink($product);

            return;
        }
    }

    private function seedMedia(Product $product): void
    {
        $file = $this->getRandomMediaFile($product->type);

        $product
            ->addMediaFromDisk($file, Disks::SEEDING)
            ->preservingOriginal()
            ->usingFileName($this->randomFileName($file))
            ->toMediaCollection(MediaCollections::PRODUCT_OBJECT);
    }

    private function seedLink(Product $product): void
    {
        $product->link = $this->getRandomLink($product->type);
        $product->save();
    }

    private function hasLinkOptions(string $type): bool
    {
        return Arr::has($this->links, $type);
    }

    private function getRandomLink(string $type): string
    {
        return Arr::random(Arr::get($this->links, $type));
    }
}
