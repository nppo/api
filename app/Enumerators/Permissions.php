<?php

declare(strict_types=1);

namespace App\Enumerators;

use Way2Web\Force\Enum;

class Permissions extends Enum
{
    public const PROJECTS_CREATE = 'create projects';
    public const PROJECTS_UPDATE = 'update projects';
    public const PROJECTS_DELETE = 'delete projects';
    public const PRODUCTS_CREATE = 'create products';
    public const PRODUCTS_UPDATE = 'update products';
    public const PRODUCTS_DELETE = 'delete products';
    public const PEOPLE_CREATE = 'create people';
    public const PEOPLE_UPDATE = 'update people';
}
