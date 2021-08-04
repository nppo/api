<?php

declare(strict_types=1);

namespace App\Models;

use App\Enumerators\Disks;
use App\Enumerators\MediaCollections;
use App\Interfaces\HasMetaData;
use App\Models\Support\HasMeta;
use App\Models\Support\HasTags;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Way2Web\Force\AbstractModel;
use Way2Web\Force\HasUuid;

class Project extends AbstractModel implements HasMedia, HasMetaData
{
    use InteractsWithMedia;
    use HasMeta;
    use HasUuid;
    use HasTags;
    use SoftDeletes;

    public $incrementing = false;

    protected $keyType = 'string';

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

    public function themes(): MorphToMany
    {
        return $this->tagRelation(Theme::class);
    }

    public function keywords(): MorphToMany
    {
        return $this->tagRelation(Keyword::class);
    }

    public function getProjectPictureUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl(MediaCollections::PROJECT_PICTURE);
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection(MediaCollections::PROJECT_PICTURE)
            ->singleFile()
            ->useDisk(Disks::SURF_PUBLIC);
    }

    public function articles(): MorphToMany
    {
        return $this->morphToMany(Article::class, 'relatable');
    }
}
