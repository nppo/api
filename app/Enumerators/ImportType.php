<?php

declare(strict_types=1);

namespace App\Enumerators;

use App\Models\Party;
use App\Models\Person;
use App\Models\Product;
use Way2Web\Force\Enum;

class ImportType extends Enum
{
    public const PRODUCT = Product::class;
    public const PERSON = Person::class;
    public const PARTY = Party::class;
}
