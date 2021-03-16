<?php

declare(strict_types=1);

namespace App\Models;

use App\Interfaces\HasMetaData;
use App\Models\Support\HasMeta;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Way2Web\Force\AbstractModel;

class Project extends AbstractModel implements HasMetaData
{
    use HasMeta;

    /** @var array */
    protected $fillable = [
        'title',
        'description',
        'purpose',
    ];

    public function likes(): MorphToMany
    {
        return $this->morphToMany(User::class, 'likeable');
    }

    public function owner(): MorphToMany
    {
        return $this->people()->wherePivot('is_owner', true);
    }

    public function parties(): MorphToMany
    {
        return $this->morphedByMany(Party::class, 'cooperable');
    }

    public function people(): MorphToMany
    {
        return $this->morphedByMany(Person::class, 'cooperable');
    }

    public function products(): BelongsToMany
    {
        return $this
            ->belongsToMany(Product::class)
            ->withCount('likes')
            ->withTimestamps();
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function themes(): MorphToMany
    {
        return $this->morphToMany(Theme::class, 'themeable');
    }
}
