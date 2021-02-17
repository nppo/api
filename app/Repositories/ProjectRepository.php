<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Project;
use Way2Web\Force\Repository\AbstractRepository;

class ProjectRepository extends AbstractRepository
{
    public function model(): string
    {
        return Project::class;
    }
}
