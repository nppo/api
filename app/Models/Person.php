<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Way2Web\Force\AbstractModel;

class Person extends AbstractModel
{
    public function likes(): MorphToMany
    {
        return $this->morphToMany(User::class, 'likeable');
    }

    public function parties(): BelongsToMany
    {
        return $this->belongsToMany(Party::class);
    }

    public function products(): MorphToMany
    {
        return $this->morphToMany(Product::class, 'contributable')->withCount('likes');
    }

    public function projects(): MorphToMany
    {
        return $this->morphToMany(Project::class, 'cooperable')->withCount('likes');
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
