<?php

declare(strict_types=1);

namespace App\Import\Jobs;

use App\Enumerators\Queue;
use App\Import\ConnectionResolver;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\App;

class Import implements ShouldQueue
{
    use Queueable, InteractsWithQueue, Dispatchable;

    public function __construct(string $driver)
    {
        $this->driver = $driver;
        $this->queue = Queue::IMPORT_EXTERNAL;
    }

    public function handle(): void
    {
        App::make(ConnectionResolver::class)->resolve($this->driver)->import();
    }
}
