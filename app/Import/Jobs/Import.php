<?php

declare(strict_types=1);

namespace App\Import\Jobs;

use App\Enumerators\Queue;
use App\Import\ConnectionResolver;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

class Import implements ShouldQueue
{
    use Queueable, InteractsWithQueue, Dispatchable;

    protected string $driver;

    public function __construct(string $driver)
    {
        $this->driver = $driver;
        $this->queue = Queue::IMPORT_EXTERNAL;
    }

    public function handle(ConnectionResolver $connectionResolver): void
    {
        $connectionResolver->resolve($this->driver)->import();
    }
}
