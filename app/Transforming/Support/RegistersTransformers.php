<?php

declare(strict_types=1);

namespace App\Transforming\Support;

use App\Facades\Transformer;
use InvalidArgumentException;

trait RegistersTransformers
{
    protected function registerTransformers(): void
    {
        if (!isset($this->transformers) || !is_array($this->transformers)) {
            $class = get_class($this);

            throw new InvalidArgumentException("{$class} has no transformers to register");
        }

        foreach ($this->transformers as $type => $class) {
            if (!Transformer::exists($type)) {
                Transformer::register($type, $class);
            }
        }
    }
}
