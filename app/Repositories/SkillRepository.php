<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Enumerators\TagTypes;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Way2Web\Force\Repository\AbstractRepository;

class SkillRepository extends AbstractRepository
{
    public function model(): string
    {
        return Tag::class;
    }

    public function makeQuery(bool $timestamps = true): Builder
    {
        $model = $this->makeModel();
        $model->timestamps = $model->timestamps ? $timestamps : false;

        return $model->newQuery()->where('type', TagTypes::SKILL);
    }

    public function index(): Collection
    {
        return $this
            ->makeQuery()
            ->orderBy('label')
            ->get();
    }
}
