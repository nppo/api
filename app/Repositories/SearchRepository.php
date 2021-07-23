<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Enumerators\Entities;
use App\Enumerators\Filters;
use App\Http\Resources\SearchResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Way2Web\Force\Repository\AbstractRepository;

class SearchRepository
{
    const DISCOVER_EAGER_LOADED_RELATIONS = [
        'themes',
        'tags',
        'products',
        'projects',
        'parties',
        'articles',
        'skills',
        'children',
    ];

    public array $results = [self::COUNT_KEY => 0];

    private const COUNT_KEY = 'count';

    private ProductRepository $productRepository;

    private PartyRepository $partyRepository;

    private PersonRepository $personRepository;

    private ProjectRepository $projectRepository;

    private ArticleRepository $articleRepository;

    public function __construct(
        ProductRepository $productRepository,
        PartyRepository $partyRepository,
        PersonRepository $personRepository,
        ProjectRepository $projectRepository,
        ArticleRepository $articleRepository
    ) {
        $this->productRepository = $productRepository;
        $this->partyRepository = $partyRepository;
        $this->personRepository = $personRepository;
        $this->projectRepository = $projectRepository;
        $this->articleRepository = $articleRepository;
    }

    /**
     * @param string|array $types
     * @param string       $query
     * @param array        $filters
     *
     * @return $this
     */
    public function searchFor($types, string $query = '', array $filters = []): self
    {
        $filters = Arr::except($filters, Filters::TYPES);

        if (is_array($types)) {
            foreach ($types as $type) {
                $this->handleSearch($type, $query, $filters);
            }

            return $this;
        }

        $this->handleSearch($types, $query, $filters);

        return $this;
    }

    protected function handleSearch(string $type, string $query, array $filters): void
    {
        switch ($type) {
            case Entities::PRODUCT:
                $this->results[$type] = $this
                    ->productRepository
                    ->search($query)
                    ->filter($filters)
                    ->orderBy('published_at', 'desc')
                    ->get();
                break;

            case Entities::PERSON:
                $this->results[$type] = $this
                    ->personRepository
                    ->search($query)
                    ->filter($filters)
                    ->get();
                break;

            case Entities::PARTY:
                $this->results[$type] = $this
                    ->partyRepository
                    ->search($query)
                    ->get();
                break;

            case Entities::PROJECT:
                $this->results[$type] = $this
                    ->projectRepository
                    ->search($query)
                    ->filter($filters)
                    ->get();
                break;

            case Entities::ARTICLE:
                $this->results[$type] = $this
                    ->articleRepository
                    ->search($query)
                    ->filter($filters)
                    ->get();
                break;

            default:
                $this->results[$type] = [];
                break;
        }

        $this->results[self::COUNT_KEY] += count($this->results[$type]);
    }

    public function discover(): self
    {
        foreach (Entities::asArray() as $entity) {
            $model = 'App\Models\\' . Str::studly($entity);
            $repositoryName = "{$entity}Repository";
            $orderByColumn = $entity === Entities::PRODUCT ? 'published_at' : 'created_at';

            if (!property_exists(self::class, $repositoryName)) {
                continue;
            }

            /** @var AbstractRepository $repository */
            $repository = $this->{$repositoryName};

            $query = $repository
                ->makeQuery()
                ->orderByDesc($orderByColumn)
                ->limit(10);

            if (class_exists($model)) {
                /** @var Model $model */
                $model = new $model();

                foreach (self::DISCOVER_EAGER_LOADED_RELATIONS as $relation) {
                    if (method_exists($model, $relation)) {
                        $query->with($relation);
                    }
                }

                if (method_exists($model, 'likes')) {
                    $query->withCount('likes');
                }
            }

            $this->results[$entity] = $query->get();

            $this->results[self::COUNT_KEY] += count($this->results[$entity]);
        }

        return $this;
    }

    public function toResource(): SearchResource
    {
        return SearchResource::make($this->results);
    }
}
