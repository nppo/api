<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enumerators\Mimes;
use App\Enumerators\ProductTypes;
use App\External\ShareKit\Connection;
use App\External\ShareKit\Entities\RepoItem;
use App\Models\Party;
use App\Models\Person;
use App\Models\Product;
use App\Repositories\PartyRepository;
use App\Repositories\PersonRepository;
use App\Repositories\ProductRepository;
use App\Repositories\TagRepository;
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

    public PartyRepository $partyRepository;

    public PersonRepository $personRepository;

    public ProductRepository $productRepository;

    public TagRepository $tagRepository;

    public Connection $connection;

    public function __construct(
        PartyRepository $partyRepository,
        PersonRepository $personRepository,
        ProductRepository $productRepository,
        TagRepository $tagRepository,
        Connection $connection
    ) {
        parent::__construct();

        $this->partyRepository = $partyRepository;
        $this->personRepository = $personRepository;
        $this->productRepository = $productRepository;
        $this->tagRepository = $tagRepository;
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

    public function handle(): void
    {
        $this
            ->connection
            ->repoItems()
            ->each(function (RepoItem $repoItem): void {
                $output = [];

                $this->productMapping()->apply($repoItem->getAttributes(), $output);

                $attributes = array_merge(
                    ['type' => $this->getProductType($repoItem)],
                    $output
                );

                $tags = $this->createTags($repoItem->getAttribute('keywords'));

                /** @var Product $product */
                $product = $this->createProduct($attributes);

                /** @var Party $party */
                $party = $this->createParty($repoItem);

                $this->createPeople($repoItem, $product);

                $this->createFile($repoItem, $product);

                $this->attachTagsToProduct($tags, $product);

                $this->attachPartyToProduct($party, $product);
            });
    }

    private function createProduct(array $attributes): Model
    {
        return $this
            ->productRepository
            ->updateOrCreate(
                Arr::only($attributes, []),
                Arr::except($attributes, []),
            );
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
        if (isset($repoItem->file) && count($repoItem->file)) {
            $product->link = Arr::get(Arr::first($repoItem->getAttribute('file')), 'url');
            $product->save();
        }
    }

    private function createParty(RepoItem $repoItem): ?Model
    {
        $output = [];

        $this->partyMapping()->apply($repoItem->getAttributes(), $output);

        if ($publisher = $repoItem->getAttribute('publisher')) {
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
