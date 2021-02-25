<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Enumerators\Entities;
use App\Enumerators\Filters;
use App\Http\Resources\SearchResource;
use Illuminate\Support\Arr;

class SearchRepository
{
    public array $results = [self::COUNT_KEY => 0];

    private const COUNT_KEY = 'count';

    private ProductRepository $productRepository;

    private PartyRepository $partyRepository;

    private PersonRepository $personRepository;

    private ProjectRepository $projectRepository;

    public function __construct(
        ProductRepository $productRepository,
        PartyRepository $partyRepository,
        PersonRepository $personRepository,
        ProjectRepository $projectRepository
    ) {
        $this->productRepository = $productRepository;
        $this->partyRepository = $partyRepository;
        $this->personRepository = $personRepository;
        $this->projectRepository = $projectRepository;
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

            default:
                $this->results[$type] = [];
                break;
        }

        $this->results[self::COUNT_KEY] += count($this->results[$type]);
    }

    public function toResource(): SearchResource
    {
        return SearchResource::make($this->results);
    }
}
