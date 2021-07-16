<?php

declare(strict_types=1);

namespace App\External\Strapi;

use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\Collection;

class Client
{
    protected GuzzleClient $client;

    protected string $baseUrl;

    protected array $headers = [];

    public function __construct(GuzzleClient $guzzleClient)
    {
        $this->client = $guzzleClient;
        $this->baseUrl = config('strapi.url');
    }

    public function getArticles(): Collection
    {
        return new Collection(
            json_decode(
                $this->client->get($this->baseUrl . '/articles')->getBody()->__toString(),
                true
            )
        );
    }
}
