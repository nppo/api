<?php

declare(strict_types=1);

namespace App\Models;

use App\Enumerators\TagTypes;
use App\Models\Support\HasTags;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Way2Web\Force\AbstractModel;

class Article extends AbstractModel
{
    use HasTags;

    public $fillable = [
        'title',
        'preview_url',
        'summary',
        'header',
        'content',
    ];

    protected $casts = [
        'header'  => 'array',
        'content' => 'array',
    ];

    public function themes(): MorphToMany
    {
        return $this->tags()->where('type', TagTypes::THEME);
    }
}
