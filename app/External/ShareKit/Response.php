<?php

declare(strict_types=1);

namespace App\External\ShareKit;

use GuzzleHttp\Psr7\Response as Psr7Response;
use Illuminate\Support\Arr;

class Response
{
    protected int $code;

    protected array $body;

    public function __construct(int $statusCode, string $body)
    {
        $this->code = $statusCode;
        $this->body = json_decode($body, true);
    }

    public static function fromClient(Psr7Response $response): self
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
}
