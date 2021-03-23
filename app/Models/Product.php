<?php

declare(strict_types=1);

namespace App\Models;

use App\Enumerators\Disks;
use App\Enumerators\MediaCollections;
use App\Helpers\Structure as StructureHelper;
use App\Interfaces\HasMetaData;
use App\Models\Support\HasMeta;
use App\Models\Support\HasTags;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Laravel\Scout\Searchable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Way2Web\Force\AbstractModel;

class Product extends AbstractModel implements HasMedia, HasMetaData
{
    use Searchable, InteractsWithMedia, HasMeta, HasTags;

    protected $fillable = [
        'type',
        'title',
        'description',
        'summary',
        'link',
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

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection(MediaCollections::PRODUCT_OBJECT)
            ->singleFile()
            ->useDisk(Disks::SURF_PRIVATE);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class);
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
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

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class);
    }

    public function resolveStructure(): Structure
    {
        return Structure::where('label', StructureHelper::labelForProductType($this->type))
            ->sole();
    }

    public function owner(): MorphToMany
    {
        return $this->people()->wherePivot('is_owner', true);
    }

    public function getObjectUrlAttribute(): ?string
    {
        if ($this->hasMedia(MediaCollections::PRODUCT_OBJECT)) {
            return route('api.products.download', $this);
        }

        return $this->link;
    }
}
