<?php

declare(strict_types=1);

namespace App\Transforming;

class Map
{
    protected string $from;

    protected string $to;

    protected ?string $transformerType;

    public function __construct(string $from, string $to, ?string $transformerType = null)
    {
        $this->from = $from;
        $this->to = $to;
        $this->transformerType = $transformerType;
    }

    public function getFrom(): string
    {
        return $this->from;
    }

    public function getTo(): string
    {
        return $this->to;
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
