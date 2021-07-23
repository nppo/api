<?php

declare(strict_types=1);

namespace App\Import\Resolvers;

use App\Import\Interfaces\ModelResolver;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractResolver implements ModelResolver
{
    abstract public function resolve(array $data): ?Model;
}
