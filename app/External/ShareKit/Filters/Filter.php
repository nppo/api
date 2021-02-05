<?php

declare(strict_types=1);

namespace App\External\ShareKit\Filters;

class Filter
{
    protected string $key;
    protected string $operator;
    protected string $value;

    public const LEVEL_OPERATOR = '|';

    public function __construct(string $key, string $operator, ?string $value = null)
    {
        if (!$value) {
            $value = $operator;
            $operator = '=';
        }

        $this->key = $key;
        $this->operator = OperatorParser::parse($operator);
        $this->value = $value;
    }

    public function getUrlKey(): string
    {
        return $this->getFilterKey($this->key);
    }

    public function getUrlValue(): string
    {
        return $this->value;
    }

    protected function getFilterKey(string $key): string
    {
        $array = explode(self::LEVEL_OPERATOR, $this->key);

        $filterString = 'filter';

        foreach ($array as $key) {
            $filterString .= $this->wrap($key);
        }

        return $filterString . $this->wrap($this->operator);
    }

    protected function wrap(string $string): string
    {
        return '[' . $string . ']';
    }
}
