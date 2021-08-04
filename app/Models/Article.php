<?php

declare(strict_types=1);

namespace App\Models;

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
        return $this->tagRelation(Theme::class);
    }

    public function keywords(): MorphToMany
    {
        return $this->tagRelation(Keyword::class);
    }

    public function products(): MorphToMany
    {
        return $this->morphedByMany(Product::class, 'relatable');
    }

    public function projects(): MorphToMany
    {
        return $this->morphedByMany(Project::class, 'relatable');
    }
}
