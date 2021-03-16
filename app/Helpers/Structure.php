<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Models\Product;

class Structure
{
    public static function labelForProductType(string $type): string
    {
        return Product::class . '-' . $type;
    }
}
