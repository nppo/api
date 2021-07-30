<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Party;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;
use Way2Web\Force\Repository\AbstractRepository;

class PartyRepository extends AbstractRepository
{
    protected ?Builder $builder = null;

    public function __construct()
    {
        $this->builder = $this->makeQuery();
    }

    public function model(): string
    {
        return Party::class;
    }

    public function search(string $query): self
    {
        $this
            ->builder
            ->with(['products']);

        if ($query !== '') {
            $this->builder->where('name', 'LIKE', "%{$query}%");
        }

        return $this;
    }

    public function get(): Collection
    {
        return $this->builder->get();
    }

    public function index(): Paginator
    {
        return QueryBuilder::for($this->makeQuery())
            ->defaultSort('name')
            ->allowedSorts([
                AllowedSort::field('id'),
                AllowedSort::field('name'),
            ])
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::partial('name'),
                AllowedFilter::partial('description'),
            ])
            ->jsonPaginate();
    }

    public function show(string $id): Model
    {
        return $this
            ->with([
                'parties',
                'projects',
                'products',
                'media',
            ])
            ->findOrFail($id);
    }

    public function createFull(array $data): Party
    {
        /** @var Party */
        $party = Party::create($data);

        return $party;
    }

    public function updateFull(string $id, array $data): Party
    {
        /** @var Party */
        $party = $this->findOrFail($id);

        $party->update($data);

        return $party;
    }

    public function deleteFull(string $id): Party
    {
        /** @var Party */
        $party = $this->findOrFail($id);

        $party->delete();

        return $party;
    }
}
