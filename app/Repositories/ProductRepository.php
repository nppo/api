<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Product;
use Way2Web\Force\AbstractRepository;

class ProductRepository extends AbstractRepository
{
    public function model(): string
    {
        return Product::class;
    }
}
