<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Laravel\Scout\Searchable;
use Way2Web\Force\AbstractModel;

class Product extends AbstractModel
{
    use Searchable;

    public function toSearchableArray(): array
    {
        return [
            'id'          => $this->getKey(),
            'themes'      => $this->themes->map(function (Theme $theme): int {
                return $theme->id;
            })->toArray(),
            'title'       => $this->title,
            'description' => $this->description,
        ];
    }

    public function parties(): BelongsToMany
    {
        return $this->belongsToMany(Party::class);
    }

    public function people(): BelongsToMany
    {
        return $this->belongsToMany(Person::class);
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
}
