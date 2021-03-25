<?php

declare(strict_types=1);

namespace App\Transforming\Transformers;

use App\Enumerators\ProductTypes;
use App\Transforming\Interfaces\Transformer;
use Illuminate\Support\Str;

class ProductTypeTransformer implements Transformer
{
    public function transform($value): string
    {
        // if (is_string($value)) {
        //     if (Str::contains($value, 'objectstore')) {
        //         return 'document'; // TODO: Use enum
        //     }

        //     return 'link'; // TODO: Use enum
        // }

        return ProductTypes::EMPTY;
    }
}
