<?php

declare(strict_types=1);

namespace App\Import\Resolvers\Product;

use App\Import\Interfaces\CompositableResolver;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class IdResolver implements CompositableResolver
{
    public function canResolve(array $data): bool
    {
        return Arr::has($data, 'id') && !empty(Arr::get($data, 'id'));
    }

    public function resolve(array $data): ?Product
    {
        if ($this->canResolve($data)) {
            $query = $this->query(Arr::get($data, 'id'));

            if ($query->count() === 1) {
                return $query->sole();
            }
        }

        return null;
    }

    private function query(string $id): Builder
    {
        return Product::where('id', $id);
    }
}
