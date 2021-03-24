<?php

declare(strict_types=1);

namespace App\Transforming\Transformers;

use App\Transforming\Interfaces\Transformer;

class PersonFunction implements Transformer
{
    public function transform($value): ?string
    {
        return $value ? ucfirst($value) : null;
    }
}
