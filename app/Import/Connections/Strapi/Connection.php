<?php

namespace App\Import\Connections\Strapi;

use App\Import\Connections\Contracts\ImportConnection;
use App\Import\Connections\Strapi\Jobs\Import;

class Connection implements ImportConnection
{
    public function import(): void
    {
        Import::dispatch();
    }
}
