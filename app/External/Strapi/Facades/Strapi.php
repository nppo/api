<?php

declare(strict_types=1);

namespace App\External\Strapi\Facades;

use App\External\Strapi\Client;
use Illuminate\Support\Facades\Facade;

class Strapi extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return Client::class;
    }
}
