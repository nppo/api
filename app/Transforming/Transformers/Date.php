<?php

declare(strict_types=1);

namespace App\Transforming\Transformers;

use App\Transforming\Interfaces\Transformer;
use Carbon\Carbon;

class Date implements Transformer
{
    public function transform($value): Carbon
    {
        if (strlen($value) === 4) {
            return Carbon::createFromFormat('Y', $value)
                ->startOfYear();
        }

        return Carbon::parse($value);
    }
}
