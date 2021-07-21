<?php

declare(strict_types=1);

namespace App\Transforming\Transformers\Strapi;

use App\Transforming\Interfaces\Transformer;

class ContentTransformer implements Transformer
{
    public function transform($value): array
    {
        return $this->decorate($value);
    }

    private function decorate(array $content): array
    {
        return array_map(function ($subContent) {
            if (is_array($subContent)) {
                return $this->decorate($subContent);
            }

            return preg_replace('(\/[a-zA-Z]*\/.*)', config('strapi.url') . '$0', $subContent);
        }, $content);
    }
}
