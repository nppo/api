<?php

declare(strict_types=1);

namespace App\Models;

use Laravel\Scout\Searchable;
use Way2Web\Force\AbstractModel;

class Product extends AbstractModel
{
    use Searchable;
}
