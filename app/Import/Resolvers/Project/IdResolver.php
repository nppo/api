<?php

declare(strict_types=1);

namespace App\Import\Resolvers\Project;

use App\Import\Interfaces\CompositableResolver;
use App\Models\Project;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class IdResolver implements CompositableResolver
{
    public function canResolve(array $data): bool
    {
        return Arr::has($data, 'id') && !empty(Arr::get($data, 'id'));
    }

    public function resolve(array $data): ?Project
    {
        if ($this->canResolve($data)) {
            $query = $this->query(Arr::get($data, 'id'));

            if ($query->count() === 1) {
                /** @var Project */
                $project = $query->sole();

                return $project;
            }
        }

        return null;
    }

    private function query(string $id): Builder
    {
        return Project::where('id', $id);
    }
}
