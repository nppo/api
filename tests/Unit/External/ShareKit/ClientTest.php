<?php

declare(strict_types=1);

namespace Tests\Unit\External\ShareKit;

use App\External\ShareKit\Client;
use GuzzleHttp\Client as GuzzleHttpClient;
use Tests\Helpers\Instance;
use Tests\Helpers\Response;
use Tests\TestCase;

class ClientTest extends TestCase
{
    /** @test */
    public function by_default_it_has_no_headers(): void
    {
        $client = $this->app->make(Client::class);

        $this->assertEmpty(
            Instance::getProperty($client, 'headers')
        );
    }

    /** @test */
    public function when_token_is_called_with_a_token_it_will_register_a_authorization_header(): void
    {
        $client = $this->app->make(Client::class);

        $client->setToken('::STRING::');

        $this->assertArrayHasKey(
            'Authorization',
            Instance::getProperty($client, 'headers')
        );
    }

    /** @test */
    public function when_token_is_called_without_a_token_it_will_remove_the_authorization_header(): void
    {
        $client = $this->app->make(Client::class);

        $client->setToken('::STRING::');

        $client->setToken(null);

        $this->assertArrayNotHasKey(
            'Authorization',
            Instance::getProperty($client, 'headers')
        );
    }

    /** @test */
    public function when_get_is_called_it_will_send_the_variables_to_the_client_through_request(): void
    {
        $guzzleClient = $this->createPartialMock(GuzzleHttpClient::class, ['request']);

        $guzzleClient
            ->expects($this->once())
            ->method('request')
            ->with('GET', '::STRING::', ['headers' => ['Authorization' => 'Bearer ::STRING_TOKEN::']])
            ->willReturn(Response::fakeJson());

        /** @var GuzzleHttpClient $guzzleClient */
        $client = new Client($guzzleClient);

        $client->setToken('::STRING_TOKEN::');

        $client->get('::STRING::');
    }
}
