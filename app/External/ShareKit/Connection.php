<?php

declare(strict_types=1);

namespace App\External\ShareKit;

use App\External\ShareKit\Entities\RepoItem;
use App\External\ShareKit\Filters\Filter;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use InvalidArgumentException;

class Connection
{
    protected Client $client;

    protected ?string $baseUrl;

    protected array $filters = [];

    protected array $paging = [];

    public function __construct(?string $baseUrl = null, ?string $token = null)
    {
        $token = $token ?? config('sharekit.token');

        $this->client = App::make(Client::class);

        $this->setUrl($baseUrl ?? config('sharekit.url'));
        $this->setToken($token);
    }

    public function setToken(?string $token): self
    {
        $this->client->setToken($token);

        return $this;
    }

    public function setUrl(?string $baseUrl): self
    {
        $this->baseUrl = $baseUrl;

        return $this;
    }

    public function repoItems(): Collection
    {
        $this->setFilters([]);

        $response = $this->client->get($this->getUrl('repoItems', $this->filters, $this->paging));

        return (new Collection($response->getData()))
            ->map(function (array $data): RepoItem {
                return RepoItem::createFromData($data);
            });
    }

    public function repoItem(string $identifier): RepoItem
    {
        $this->setFilters([new Filter('id', $identifier)]);

        return $this->repoItems()->first();
    }

    public function setFilters(array $filters): self
    {
        $this->filters = $filters;

        return $this;
    }

    public function setPaging(int $pageSize = null, int $pageNumber = null): self
    {
        $this->paging = [];

        if ($pageSize) {
            $this->paging['size'] = $pageSize;
        }

        if ($pageNumber) {
            $this->paging['number'] = $pageNumber;
        }

        return $this;
    }

    protected function parsePagingForUrl(array $paging): array
    {
        $asArray = [];

        foreach ($paging as $key => $value) {
            $asArray['page'][$key] = $value;
        }

        return $asArray;
    }

    protected function parseFiltersForUrl(array $filters): array
    {
        $asArray = [];

        /** @var Filter $filter */
        foreach ($filters as $filter) {
            $asArray[$filter->getUrlKey()] = $filter->getUrlValue();
        }

        return $asArray;
    }

    protected function getUrl(string $path, array $filters, array $paging): string
    {
        $url = $this->baseUrl;

        if (is_null($url)) {
            throw new InvalidArgumentException('No base url provided');
        }

        if (!Str::endsWith($url, '/')) {
            $url .= '/';
        }

        return $url . $path . '?' . http_build_query(array_merge(
            $this->parseFiltersForUrl($filters),
            $this->parsePagingForUrl($paging)
        ));
    }
}
