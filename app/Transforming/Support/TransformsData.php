<?php

declare(strict_types=1);

namespace App\Transforming\Support;

use App\Facades\Transformer;

trait TransformsData
{
    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function transform(string $type, $value)
    {
        return Transformer::for($type)->transform($value);
    }
}
