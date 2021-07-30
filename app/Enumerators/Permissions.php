<?php

declare(strict_types=1);

namespace App\Enumerators;

use Way2Web\Force\Enum;

class Permissions extends Enum
{
    public const PROJECTS_CREATE = 'create projects';
    public const PROJECTS_UPDATE = 'update projects';
    public const PRODUCTS_CREATE = 'create products';
    public const PRODUCTS_UPDATE = 'update products';
    public const PEOPLE_CREATE = 'create people';
    public const PEOPLE_UPDATE = 'update people';

    public const THEME_CREATE = 'create theme';
    public const THEME_UPDATE = 'update theme';

    public const TAG_CREATE = 'create tag';
    public const TAG_UPDATE = 'update tag';
    public const TAG_DELETE = 'delete tag';
}
