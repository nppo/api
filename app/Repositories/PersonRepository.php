<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Person;
use Way2Web\Force\AbstractRepository;

class PersonRepository extends AbstractRepository
{
    public function model(): string
    {
        return Person::class;
    }
}
