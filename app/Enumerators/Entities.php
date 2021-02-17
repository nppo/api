<?php

declare(strict_types=1);

namespace App\Enumerators;

use Way2Web\Force\Enum;

class Entities extends Enum
{
    public const PARTY = 'party';
    public const PERSON = 'person';
    public const PRODUCT = 'product';
    public const PROJECT = 'project';
}
