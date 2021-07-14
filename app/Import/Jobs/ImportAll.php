<?php

declare(strict_types=1);

namespace App\Import\Jobs;

use App\Enumerators\ImportDriver;
use App\Enumerators\Queue;
use App\Import\ConnectionResolver;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

class ImportAll implements ShouldQueue
{
    use Queueable, InteractsWithQueue, Dispatchable;

    protected ConnectionResolver $connectionResolver;

    public function __construct(ConnectionResolver $connectionResolver)
    {
        $this->connectionResolver = $connectionResolver;
        $this->queue = Queue::IMPORT_EXTERNAL;
    }

    public function handle(): void
    {
        foreach (ImportDriver::asArray() as $importDriver) {
            $this->connectionResolver->resolve($importDriver)->import();
        }
    }
}
