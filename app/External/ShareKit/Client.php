<?php

declare(strict_types=1);

namespace App\External\ShareKit;

use GuzzleHttp\Client as GuzzleClient;

class Client
{
    protected GuzzleClient $client;

    protected array $headers = [];

    public function __construct(GuzzleClient $guzzleClient)
    {
        $this->client = $guzzleClient;
    }

    public function get(string $path)
    {
        return Response::fromClient(
            $this->client->request('GET', $path, [
                'headers' => $this->getHeaders(),
            ])
        );
    }

    public function token(?string $token): self
    {
        if ($token) {
            $this->headers['Authorization'] = 'Bearer ' . $token;
        }

        return $this;
    }

    protected function getHeaders(): array
    {
        return $this->headers;
    }
}
