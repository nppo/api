<?php

declare(strict_types=1);

namespace Tests\Unit\Import;

use App\Enumerators\ImportDriver;
use App\Enumerators\Queue as EnumeratorsQueue;
use App\Import\ConnectionResolver;
use App\Import\Connections\Contracts\ImportConnection;
use App\Import\Connections\ShareKit\Connection;
use App\Import\Jobs\ImportAll;
use Illuminate\Support\Facades\Queue;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class ImportAllTest extends TestCase
{
    /** @test */
    public function it_is_put_on_the_right_queue(): void
    {
        Queue::fake();

        ImportAll::dispatch();

        Queue::assertPushedOn(EnumeratorsQueue::IMPORT_EXTERNAL, ImportAll::class);
    }

    /** @test */
    public function handle_will_resolve_all_import_drivers_on_the_connection_resolver(): void
    {
        /** @var ConnectionResolver|MockObject */
        $connectionResolver = $this->createMock(ConnectionResolver::class);
        $drivers = [];

        foreach (ImportDriver::asArray() as $importDriver) {
            $drivers[] = [$importDriver];
        }

        $connectionResolver
            ->expects($this->exactly(count($drivers)))
            ->method('resolve')
            ->withConsecutive(...$drivers)
            ->willReturn(
                $this->createMock(ImportConnection::class),
            );

        (new ImportAll())->handle($connectionResolver);
    }

    /** @test */
    public function handle_will_call_import_on_all_connections(): void
    {
        /** @var ConnectionResolver|MockObject */
        $connectionResolver = $this->createMock(ConnectionResolver::class);
        $drivers = [];

        foreach (ImportDriver::asArray() as $importDriver) {
            $drivers[] = [$importDriver];
        }

        $connection = $this->createMock(ImportConnection::class);

        $connection
            ->expects($this->exactly(count(ImportDriver::asArray())))
            ->method('import');

        $connectionResolver
            ->method('resolve')
            ->withConsecutive(...$drivers)
            ->willReturn(
                $connection
            );

        (new ImportAll())->handle($connectionResolver);
    }
}
