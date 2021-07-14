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
use Illuminate\Support\Facades\App;

class ImportAll implements ShouldQueue
{
    use Queueable, InteractsWithQueue, Dispatchable;

    public function __construct()
    {
        $this->queue = Queue::IMPORT_EXTERNAL;
    }

    public function handle(): void
    {
        $connectionResolver = App::make(ConnectionResolver::class);

        foreach (ImportDriver::asArray() as $importDriver) {
            $connectionResolver->resolve($importDriver)->import();
        }
    }
}
