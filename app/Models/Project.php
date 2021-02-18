<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Way2Web\Force\AbstractModel;

class Project extends AbstractModel
{
    use HasFactory;

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)->withTimestamps();
    }

    public function themes(): MorphToMany
    {
        return $this->morphToMany(Theme::class, 'themeable');
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function likes(): MorphToMany
    {
        return $this->morphToMany(User::class, 'likeable');
    }

    public function people(): MorphToMany
    {
        return $this->morphedByMany(Person::class, 'cooperable');
    }

    public function parties(): MorphToMany
    {
        return $this->morphedByMany(Party::class, 'cooperable');
    }
}
