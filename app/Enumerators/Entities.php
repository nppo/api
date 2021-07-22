<?php

declare(strict_types=1);

namespace App\Enumerators;

use Way2Web\Force\Enum;

class Entities extends Enum
{
    public const PRODUCT = 'product';
    public const PROJECT = 'project';
    public const PERSON = 'person';
    public const PARTY = 'party';
    public const ARTICLE = 'article';
}
