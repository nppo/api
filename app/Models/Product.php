<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Laravel\Scout\Searchable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Way2Web\Force\AbstractModel;

class Product extends AbstractModel implements HasMedia
{
    use Searchable, InteractsWithMedia;

    protected $fillable = [
        'type',
        'title',
        'description',
    ];

    public function toSearchableArray(): array
    {
        return [
            'id'          => $this->getKey(),
            'title'       => $this->title,
            'description' => $this->description,

            'themes' => $this->themes->map(function (Theme $theme): int {
                return $theme->id;
            })->toArray(),

            'tags' => $this->tags->map(function (Tag $tag): int {
                return $tag->id;
            })->toArray(),

            'people' => $this->tags->map(function (Person $person): int {
                return $person->id;
            })->toArray(),

            'parties' => $this->parties->map(function (Party $party): int {
                return $party->id;
            })->toArray(),
        ];
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
        return $this->morphedByMany(Person::class, 'contributable');
    }

    public function parties(): MorphToMany
    {
        return $this->morphedByMany(Party::class, 'contributable');
    }

    public function owner(): MorphToMany
    {
        return $this->people()->wherePivot('is_owner', true);
    }
}
