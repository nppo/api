<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Support\HasTags;
use Way2Web\Force\AbstractModel;

class Article extends AbstractModel
{
    use HasTags;

    public $fillable = [
        'title',
        'content'
    ];

    protected $casts = [
        'content' => 'array',
    ];
}
