<?php

declare(strict_types=1);

namespace App\Models;

use App\Enumerators\TagTypes;
use App\Models\Support\HasTags;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Way2Web\Force\AbstractModel;
use Way2Web\Force\HasUuid;

class Article extends AbstractModel
{
    use HasTags;
    use HasUuid;

    public $incrementing = false;

    protected $keyType = 'string';

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

    public function likes(): MorphToMany
    {
        return $this->morphToMany(User::class, 'likeable');
    }

    public function themes(): MorphToMany
    {
        return $this->tags()->where('type', TagTypes::THEME);
    }

    public function keywords(): MorphToMany
    {
        return $this->tags()->where('type', TagTypes::KEYWORD);
    }
}
