<?php

declare(strict_types=1);

namespace Tests\Mocks;

use App\Transforming\Interfaces\Transformer as InterfacesTransformer;

class Transformer implements InterfacesTransformer
{
    public function transform($value)
    {
        return $value;
    }
}
