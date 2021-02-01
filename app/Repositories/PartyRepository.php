<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Party;
use Way2Web\Force\AbstractRepository;

class PartyRepository extends AbstractRepository
{
    public function model(): string
    {
        return Party::class;
    }
}
