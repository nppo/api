<?php

declare(strict_types=1);

namespace App\External\ShareKit;

use App\External\ShareKit\Entities\RepoItem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use InvalidArgumentException;

class Connection
{
    protected Client $client;

    protected ?string $baseUrl;

    public function __construct(?string $baseUrl = null, ?string $token = null)
    {
        $token = $token ?? config('sharekit.token');

        $this->client = App::make(Client::class);

        $this->url($baseUrl ?? config('sharekit.url'));
        $this->token($token);
    }

    public function token(?string $token): self
    {
        $this->client->token($token);

        return $this;
    }

    public function url(?string $baseUrl): self
    {
        $this->baseUrl = $baseUrl;

        return $this;
    }

    public function repoItems(): Collection
    {
        $response = $this->client->get($this->getUrl('repoItems'));

        return (new Collection($response->getData()))
            ->map(function (array $data): RepoItem {
                return RepoItem::createFromData($data);
            });
    }

    protected function getUrl(string $path): string
    {
        $url = $this->baseUrl;

        if (is_null($url)) {
            throw new InvalidArgumentException('No base url provided');
        }

        if (!Str::endsWith($url, '/')) {
            $url .= '/';
        }

        return $url . $path;
    }
}
