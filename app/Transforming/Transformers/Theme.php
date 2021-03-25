<?php

declare(strict_types=1);

namespace App\Transforming\Transformers;

use App\Transforming\Interfaces\Transformer;
use Illuminate\Support\Str;

class Theme implements Transformer
{
    private const GLUE = ' & ';

    public function transform($value): string
    {
        $theme = '';

        $words = explode('_', $value);

        foreach ($words as $word) {
            $theme .= ucfirst($word) . self::GLUE;
        }

        return Str::endsWith($theme, self::GLUE)
            ? substr($theme, 0, -3)
            : $theme;
    }
}
