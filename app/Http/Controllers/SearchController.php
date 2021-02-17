<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enumerators\Entities;
use App\Enumerators\Filters;
use App\Http\Requests\SearchRequest;
use App\Http\Resources\SearchResource;
use App\Repositories\PartyRepository;
use App\Repositories\PersonRepository;
use App\Repositories\ProductRepository;
use Illuminate\Support\Arr;

class SearchController extends Controller
{
    private ProductRepository $productRepository;

    private PartyRepository $partyRepository;

    private PersonRepository $personRepository;

    public function __construct(
        ProductRepository $productRepository,
        PartyRepository $partyRepository,
        PersonRepository $personRepository
    ) {
        $this->productRepository = $productRepository;
        $this->partyRepository = $partyRepository;
        $this->personRepository = $personRepository;
    }

    public function search(SearchRequest $request): SearchResource
    {
        $results = ['count' => 0];

        $types = array_key_exists(Filters::TYPE, $request->getFilters())
            ? $request->getFilters()[Filters::TYPE]
            : array_values(Entities::asArray());

        foreach ($types as $type) {
            $results[$type] = $this->searchForType(
                $type,
                $request->getQuery(),
                Arr::except($request->getFilters(), Filters::TYPE)
            );

            $results['count'] += count($results[$type]);
        }

        return SearchResource::make($results);
    }

    protected function searchForType(string $type, string $query = '', array $filters = [])
    {
        switch ($type) {
            case Entities::PRODUCT:
                return $this
                    ->productRepository
                    ->search($query)
                    ->filter($filters)
                    ->get();

            case Entities::PERSON:
                return $this
                    ->personRepository
                    ->search($query)
                    ->filter($filters)
                    ->get();

            case Entities::PARTY:
                return $this
                    ->partyRepository
                    ->search($query)
                    ->get();

            default:
                return [];
        }
    }
}
