<?php

declare(strict_types=1);

namespace App\External\ShareKit;

use Closure;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Psr\Http\Message\ResponseInterface;

class Response
{
    protected int $code;

    protected array $body;

    protected ?Closure $mappingCallback = null;

    public function __construct(int $statusCode, string $body)
    {
        $this->code = $statusCode;
        $this->body = json_decode($body, true);
    }

    public static function fromClient(ResponseInterface $response): self
    {
        return new self(
            $response->getStatusCode(),
            $response->getBody()->__toString()
        );
    }

    public function getData(): array
    {
        return Arr::get($this->body, 'data');
    }

    public function has(string $key): bool
    {
        return Arr::has($this->body, $key);
    }

    /** @return mixed */
    public function get(string $key)
    {
        return Arr::get($this->body, $key);
    }

    public function toCollection(): Collection
    {
        return (new Collection($this->getData()))
                ->map($this->mappingCallback);
    }

    public function each(Closure $callback): Collection
    {
        return $this->toCollection()
            ->each($callback);
    }

    public function usingMap(?Closure $closure): self
    {
        $this->mappingCallback = $closure;

        return $this;
    }
}
