<?php

declare(strict_types=1);

namespace App\Transforming\Interfaces;

interface Transformer
{
    /**
     * Transforms a value to a different value.
     *
     * @param mixed $value
     *
     * @return mixed
     */
    public function transform($value);
}
