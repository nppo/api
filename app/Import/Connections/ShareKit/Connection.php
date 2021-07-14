<?php

namespace App\Import\Connections\ShareKit;

use App\Import\Connections\Contracts\ImportConnection;
use App\Import\Connections\ShareKit\Jobs\Import;

class Connection implements ImportConnection
{
    public function import(): void
    {
        Import::dispatch();
    }
}
