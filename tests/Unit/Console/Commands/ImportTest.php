<?php

declare(strict_types=1);

namespace Tests\Unit\Console\Commands;

use App\Enumerators\ImportDriver;
use App\Import\Jobs\Import;
use App\Import\Jobs\ImportAll;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ImportTest extends TestCase
{
    /** @test */
    public function it_will_queue_import_all_when_no_driver_is_specified(): void
    {
        Queue::fake();

        Artisan::call('import');

        Queue::assertPushed(ImportAll::class);
    }

    /** @test */
    public function it_will_queue_a_import_when_a_driver_is_specified(): void
    {
        Queue::fake();

        Artisan::call('import ' . ImportDriver::STRAPI);

        Queue::assertPushed(Import::class);
    }
}
