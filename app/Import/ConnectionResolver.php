<?php

declare(strict_types=1);

namespace App\Import;

use App\Enumerators\ImportDriver;
use App\Import\Connections\Contracts\ImportConnection;
use App\Import\Connections\ShareKit\Connection as ShareKitConnection;
use App\Import\Connections\Strapi\Connection as StrapiConnection;

class ConnectionResolver
{
    public array $connections = [
        ImportDriver::SHAREKIT => ShareKitConnection::class,
        ImportDriver::STRAPI   => StrapiConnection::class,
    ];

    public function resolve(string $name): ImportConnection
    {
        $class = $this->connections[$name];

        return new $class();
    }
}
