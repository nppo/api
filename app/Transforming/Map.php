<?php

declare(strict_types=1);

namespace App\Transforming;

class Map
{
    protected string $origin;

    protected string $destination;

    protected ?string $transformerType;

    public function __construct(string $origin, string $destination, ?string $transformerType = null)
    {
        $this->origin = $origin;
        $this->destination = $destination;
        $this->transformerType = $transformerType;
    }

    public function getOrigin(): string
    {
        return $this->origin;
    }

    public function getDestination(): string
    {
        return $this->destination;
    }

    public function setTransformerType(string $type)
    {
        $this->transformerType = $type;

        return $this;
    }

    public function getTransformerType(): ?string
    {
        return $this->transformerType;
    }
}
