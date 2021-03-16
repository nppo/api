<?php

declare(strict_types=1);

namespace App\Enumerators;

use Way2Web\Force\Enum;

class Permissions extends Enum
{
    public const PROJECTS_UPDATE = 'update projects';
    public const PRODUCTS_CREATE = 'create products';
    public const PRODUCTS_UPDATE = 'update products';
    public const PEOPLE_UPDATE = 'update people';
}
