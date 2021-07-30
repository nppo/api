<?php

declare(strict_types=1);

namespace App\Transforming\Transformers;

use App\Transforming\Interfaces\Transformer;
use Illuminate\Support\Arr;

class LastName implements Transformer
{
    public function transform($value): ?string
    {
        $name = explode(' ', $value);

        if (count($name) > 1) {
            return implode(' ', Arr::except($name, [0]));
        }

        return null;
    }
}
