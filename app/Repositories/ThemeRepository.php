<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Tag;
use Way2Web\Force\Repository\AbstractRepository;

class ThemeRepository extends AbstractRepository
{
    public function model(): string
    {
        return Tag::class;
    }
}
