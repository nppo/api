<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Import\Jobs\Import as ImportJob;
use App\Import\Jobs\ImportAll;
use Illuminate\Console\Command;

class Import extends Command
{
    protected $signature = 'import {driver?}';

    protected $description = 'Imports data from external systems';

    public function handle(): void
    {
        if ($this->hasArgument('driver') && !empty($this->argument('driver'))) {
            ImportJob::dispatch($this->argument('driver'));

            return;
        }

        ImportAll::dispatch();
    }
}
