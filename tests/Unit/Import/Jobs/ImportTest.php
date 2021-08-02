<?php

declare(strict_types=1);

namespace Tests\Unit\Import\Jobs;

use App\Enumerators\Queue as EnumeratorsQueue;
use App\Import\ConnectionResolver;
use App\Import\Connections\Contracts\ImportConnection;
use App\Import\Jobs\Import;
use Illuminate\Support\Facades\Queue;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class ImportTest extends TestCase
{
    /** @test */
    public function it_is_put_on_the_right_queue(): void
    {
        Queue::fake();

        Import::dispatch('::DOES_NOT_EXIST::');

        Queue::assertPushedOn(EnumeratorsQueue::IMPORT_EXTERNAL, Import::class);
    }

    /** @test */
    public function handle_will_resolve_the_provided_driver(): void
    {
        /** @var ConnectionResolver|MockObject */
        $connectionResolver = $this->createMock(ConnectionResolver::class);

        $connectionResolver
            ->expects($this->once())
            ->method('resolve')
            ->with('::DOES_NOT_EXIST::')
            ->willReturn(
                $this->createMock(ImportConnection::class)
            );

        (new Import('::DOES_NOT_EXIST::'))->handle($connectionResolver);
    }
}
