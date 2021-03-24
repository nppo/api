<?php

declare(strict_types=1);

namespace App\Enumerators;

use Way2Web\Force\Enum;

class ProductTypes extends Enum
{
    public const EMPTY = 'empty';

    public const IMAGE = 'image';

    public const YOUTUBE = 'youtube';
}
