<?php

declare(strict_types=1);

namespace App\Facades;

use App\Transforming\Repository;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \App\Transforming\Interfaces\Transformer for(string $type)
 * @method static bool exists(string $type)
 * @method static \App\Transforming\Repository register(string $type, string $class)
 * @method static \App\Transforming\Repository flush()
 * @method static array all()
 *
 * @see \App\Transforming\Repository
 */
class Transformer extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return Repository::class;
    }
}
