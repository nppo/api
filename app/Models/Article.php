<?php

declare(strict_types=1);

namespace App\Models;

use Way2Web\Force\AbstractModel;

class Article extends AbstractModel
{
    public $fillable = [
        'title',
    ];
}
