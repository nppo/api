<?php

declare(strict_types=1);

namespace Tests\Unit\Import;

use App\Enumerators\ImportDriver;
use App\Import\ConnectionResolver;
use App\Import\Connections\ShareKit\Connection as ShareKitConnection;
use App\Import\Connections\Strapi\Connection;
use Tests\TestCase;

class ConnectionResolverTest extends TestCase
{
    /** @test */
    public function it_will_find_a_connection_based_on_the_array(): void
    {
        $connectionResolver = $this->app->make(ConnectionResolver::class);

        $connectionResolver->connections['::STRING::'] = Connection::class;

        $this->assertInstanceOf(
            Connection::class,
            $connectionResolver->resolve('::STRING::')
        );
    }

    /**
     * @dataProvider connectionsDataProvider
     * @test
     */
    public function it_can_resolve_all_connections(string $name, string $class): void
    {
        $connectionResolver = $this->app->make(ConnectionResolver::class);

        $this->assertInstanceOf(
            $class,
            $connectionResolver->resolve($name)
        );
    }

    public function connectionsDataProvider(): array
    {
        return [
            ImportDriver::STRAPI => [
                ImportDriver::STRAPI,
                Connection::class
            ],
            ImportDriver::SHAREKIT => [
                ImportDriver::SHAREKIT,
                ShareKitConnection::class
            ]
        ];
    }
}
