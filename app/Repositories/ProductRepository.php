<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Support\Collection;
use Way2Web\Force\Repository\AbstractRepository;

class ProductRepository extends AbstractRepository
{
    public function model(): string
    {
        return Product::class;
    }

    public function search(string $query): Collection
    {
        $builder = $this->makeQuery();

        if ($query !== '') {
            $builder->where('title', 'LIKE', "%{$query}%");
        }

        return $builder->get();
    }
}
