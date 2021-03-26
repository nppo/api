<?php

declare(strict_types=1);

namespace App\Transforming\Transformers;

use App\Transforming\Interfaces\Transformer;

class FirstName implements Transformer
{
    public function transform($value): string
    {
        $name = explode(' ', $value);

        return ucfirst($name[0]);
    }
}
