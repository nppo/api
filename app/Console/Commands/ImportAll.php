<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enumerators\Mimes;
use App\Enumerators\ProductTypes;
use App\Enumerators\TagTypes;
use App\External\ShareKit\Connection;
use App\External\ShareKit\Entities\RepoItem;
use App\Models\Party;
use App\Models\Person;
use App\Models\Product;
use App\Repositories\PartyRepository;
use App\Repositories\PersonRepository;
use App\Repositories\ProductRepository;
use App\Repositories\TagRepository;
use App\Repositories\ThemeRepository;
use App\Transforming\Map;
use App\Transforming\Mapping;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ImportAll extends Command
{
    protected $signature = 'import:all';

    protected $description = 'Imports all Products and Persons';

    private const PAGE_SIZE = 10;

    public PartyRepository $partyRepository;

    public PersonRepository $personRepository;

    public ProductRepository $productRepository;

    public TagRepository $tagRepository;

    public ThemeRepository $themeRepository;

    public Connection $connection;

    public function __construct(
        PartyRepository $partyRepository,
        PersonRepository $personRepository,
        ProductRepository $productRepository,
        TagRepository $tagRepository,
        ThemeRepository $themeRepository,
        Connection $connection
    ) {
        parent::__construct();

        $this->partyRepository = $partyRepository;
        $this->personRepository = $personRepository;
        $this->productRepository = $productRepository;
        $this->tagRepository = $tagRepository;
        $this->themeRepository = $themeRepository;
        $this->connection = $connection;
    }

    private function productMapping(): Mapping
    {
        return new Mapping([
            new Map('title', 'title'),
            new Map('dateIssued', 'published_at', 'date'),
            new Map('abstract', 'description'),
        ]);
    }

    private function personMapping(): Mapping
    {
        return new Mapping([
            new Map('person.id', 'identifier'),
            new Map('person.name', 'first_name', 'firstName'),
            new Map('person.name', 'last_name', 'lastName'),
            new Map('role', 'function', 'personFunction'),
        ]);
    }

    private function partyMapping(): Mapping
    {
        return new Mapping([
            new Map('publisher', 'name'),
        ]);
    }

    private function themeMapping(): Mapping
    {
        return new Mapping([
            new Map('themeResearchObject', 'label', 'theme'),
        ]);
    }

    public function handle(int $pageNumber = 1): void
    {
        if ($pageNumber === 1) {
            $this->line('Processing ' . self::PAGE_SIZE . ' results per page...');
        }

        $this->line('Importing results for page ' . $pageNumber . '...');

        $repoItems = $this
            ->connection
            ->setPaging(self::PAGE_SIZE, $pageNumber)
            ->repoItems();

        $repoItems
            ->each(function (RepoItem $repoItem): void {
                $output = [];

                $this->productMapping()->apply($repoItem->getAttributes(), $output);

                $attributes = array_merge(
                    ['type' => $this->getProductType($repoItem)],
                    $output
                );

                $tags = $this->createTags($repoItem->getAttribute('keywords'));

                $themes = $this->createThemes($repoItem);

                /** @var Product $product */
                $product = $this->createProduct($attributes);

                /** @var Party $party */
                $party = $this->createParty($repoItem);

                $this->createPeople($repoItem, $product);

                $this->createFile($repoItem, $product);

                $this->attachThemesToProduct($themes, $product);

                $this->attachTagsToProduct($tags, $product);

                $this->attachPartyToProduct($party, $product);
            });

        if ($repoItems->count() === self::PAGE_SIZE) {
            $this->handle($pageNumber + 1);
        }
    }

    private function createProduct(array $attributes): Model
    {
        return $this
            ->productRepository
            ->updateOrCreate($attributes);
    }

    private function createPeople(RepoItem $repoItem, Product $product): Collection
    {
        $people = collect();

        if ($repoItem->getAttribute('authors') && count($repoItem->getAttribute('authors'))) {
            foreach ($repoItem->getAttribute('authors') as $author) {
                $people->add($this->createPerson($author));
            }

            $people->each(function (Person $person, $index) use ($product): void {
                $this->attachPersonToProduct($product, $person, $index === 0);
            });
        }

        return $people;
    }

    private function createPerson(array $author): Model
    {
        $output = [];

        $this->personMapping()->apply($author, $output);

        return $this
            ->personRepository
            ->updateOrCreate(
                Arr::only($output, 'identifier'),
                Arr::except($output, 'identifier')
            );
    }

    private function createFile(RepoItem $repoItem, Product $product): void
    {
        if ($repoItem->hasAttribute('file') && !empty($repoItem->getAttribute('file') ?? [])) {
            $product->link = Arr::get(Arr::first($repoItem->getAttribute('file')), 'url');
            $product->save();
        }
    }

    private function createParty(RepoItem $repoItem): ?Model
    {
        $output = [];

        $this->partyMapping()->apply($repoItem->getAttributes(), $output);

        if ($repoItem->getAttribute('publisher')) {
            return $this
                ->partyRepository
                ->updateOrCreate(
                    Arr::only($output, 'name')
                );
        }

        return null;
    }

    private function createTags($tags): Collection
    {
        $collection = collect();

        if (is_null($tags) || count($tags) === 0) {
            return $collection;
        }

        foreach ($tags as $tag) {
            $collection
                ->push(
                    $this
                        ->tagRepository
                        ->updateOrCreate([
                            'label' => ucfirst($tag),
                        ])
                );
        }

        return $collection;
    }

    private function createThemes(RepoItem $repoItem): Collection
    {
        $key = 'themeResearchObject';

        $output = [];

        $themes = collect();

        $values = is_array($repoItem->getAttribute($key))
            ? $repoItem->getAttribute($key)
            : [$repoItem->getAttribute($key)];

        foreach ($values as $theme) {
            $this->themeMapping()->apply([$key => $theme], $output);

            $themes->push(
                $this
                    ->tagRepository
                    ->updateOrCreate(
                        array_merge(Arr::only($output, 'label'), ['type' => TagTypes::THEME])
                    )
            );
        }

        return $themes;
    }

    private function attachPersonToProduct(Product $product, Person $person, bool $isOwner = false): void
    {
        $product
            ->people()
            ->save($person, ['is_owner' => $isOwner]);
    }

    private function attachTagsToProduct(Collection $tags, Product $product): void
    {
        $product
            ->tags()
            ->saveMany($tags);
    }

    private function attachThemesToProduct(Collection $themes, Product $product): void
    {
        $product
            ->themes()
            ->saveMany($themes);
    }

    private function attachPartyToProduct(Party $party, Product $product): void
    {
        $product->parties()->save($party);
    }

    private function getProductType(RepoItem $repoItem): string
    {
        if (is_null($repoItem->getAttribute('file'))) {
            return ProductTypes::EMPTY;
        }

        $file = Arr::first($repoItem->file);

        if (Str::endsWith(Arr::get($file, 'url'), Mimes::asArray())) {
            return ProductTypes::IMAGE;
        }

        return ProductTypes::EMPTY;
    }
}
