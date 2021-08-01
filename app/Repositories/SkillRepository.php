<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Skill;
use Illuminate\Contracts\Pagination\Paginator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;
use Way2Web\Force\Repository\AbstractRepository;

class SkillRepository extends AbstractRepository
{
    public function model(): string
    {
        return Skill::class;
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
                AllowedFilter::partial('label'),
            ])
            ->jsonPaginate();
    }
}
