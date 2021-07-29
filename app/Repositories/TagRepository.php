<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Tag;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;
use Way2Web\Force\Repository\AbstractRepository;

class TagRepository extends AbstractRepository
{
    public function model(): string
    {
        return Tag::class;
    }

    public function makeQuery(bool $timestamps = true): Builder
    {
        return parent::makeQuery($timestamps)
            ->where('type', null);
    }

    public function index(): Paginator
    {
        return QueryBuilder::for($this->makeQuery())
            ->defaultSort('label')
            ->allowedSorts([
                AllowedSort::field('id'),
                AllowedSort::field('label'),
            ])
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::partial('label')
            ])
            ->paginate();
    }

    public function show(string $id): Tag
    {
        return $this->findOrFail($id);
    }

    public function createFull(array $data): Tag
    {
        $tag = Tag::create($data);

        return $tag;
    }

    public function updateFull(string $id, array $data): Tag
    {
        $tag = $this->findOrFail($id);

        $tag->update($data);

        return $tag;
    }

    public function deleteFull(string $id): Tag
    {
        $tag = $this->findOrFail($id);

        $tag->delete();

        return $tag;
    }
}
