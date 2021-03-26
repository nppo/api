<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Structure;
use Way2Web\Force\Repository\AbstractRepository;

class StructureRepository extends AbstractRepository
{
    public function model(): string
    {
        return Structure::class;
    }
}
