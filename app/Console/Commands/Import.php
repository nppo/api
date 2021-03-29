<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Import\Jobs\ImportAll;
use Illuminate\Console\Command;

class Import extends Command
{
    protected $signature = 'import';

    protected $description = 'Imports data from external systems';

    public function handle(): void
    {
        ImportAll::dispatch();
    }
}
