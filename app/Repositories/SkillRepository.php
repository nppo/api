<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Skill;
use Illuminate\Database\Eloquent\Collection;
use Way2Web\Force\Repository\AbstractRepository;

class SkillRepository extends AbstractRepository
{
    public function model(): string
    {
        return Skill::class;
    }

    public function index(): Collection
    {
        return $this
            ->makeQuery()
            ->orderBy('label')
            ->get();
    }
}
