<?php

declare(strict_types=1);

namespace App\External\ShareKit\Facades;

use App\External\ShareKit\Connection;
use Illuminate\Support\Facades\Facade;

class ShareKit extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return Connection::class;
    }
}
