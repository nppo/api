<?php

declare(strict_types=1);

namespace App\Transforming\Transformers\Strapi;

use App\Transforming\Interfaces\Transformer;

class ContentTransformer implements Transformer
{
    /** @param mixed $value */
    public function transform($value)
    {
        if (is_string($value)) {
            return $this->decorate($value);
        }

        return $this->decorateSet($value);
    }

    /** @param mixed $subContent */
    private function decorate($subContent): string
    {
        return preg_replace('(\/[a-zA-Z]*\/.*)', config('strapi.url') . '$0', $subContent);
    }

    private function decorateSet(array $content): array
    {
        return array_map(function ($subContent) {
            if (is_array($subContent)) {
                return $this->decorateSet($subContent);
            }

            return $this->decorate($subContent);
        }, $content);
    }
}
