<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Enumerators\TagTypes;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Way2Web\Force\Repository\AbstractRepository;

class ThemeRepository extends AbstractRepository
{
    public function model(): string
    {
        return Tag::class;
    }

    public function makeQuery(bool $timestamps = true): Builder
    {
        return parent::makeQuery()->where('type', TagTypes::THEME);
    }

    public function index(): Collection
    {
        return $this
            ->makeQuery()
            ->orderBy('label')
            ->get();
    }
}
