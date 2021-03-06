<?php

declare(strict_types=1);

namespace App\Models;

use App\Enumerators\Disks;
use App\Enumerators\MediaCollections;
use App\Interfaces\HasMetaData;
use App\Models\Support\HasMeta;
use App\Models\Support\HasTags;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Way2Web\Force\AbstractModel;
use Way2Web\Force\HasUuid;

class Person extends AbstractModel implements HasMedia, HasMetaData
{
    use InteractsWithMedia;
    use HasMeta;
    use HasTags;
    use HasUuid;

    public $incrementing = false;

    protected $keyType = 'string';

    public $fillable = [
        'identifier',
        'first_name',
        'last_name',
        'function',
        'phone',
        'about',
        'email',
    ];

    public function likes(): MorphToMany
    {
        return $this->morphToMany(User::class, 'likeable');
    }

    public function parties(): MorphToMany
    {
        return $this->morphToMany(Party::class, 'affiliable');
    }

    public function products(): MorphToMany
    {
        return $this->morphToMany(Product::class, 'contributable')->withCount('likes');
    }

    public function projects(): MorphToMany
    {
        return $this->morphToMany(Project::class, 'cooperable')->withCount('likes');
    }

    public function skills(): MorphToMany
    {
        return $this->tagRelation(Skill::class);
    }

    public function themes(): MorphToMany
    {
        return $this->tagRelation(Theme::class);
    }

    public function keywords(): MorphToMany
    {
        return $this->tagRelation(Keyword::class);
    }

    public function getProfilePictureUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl(MediaCollections::PROFILE_PICTURE);
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection(MediaCollections::PROFILE_PICTURE)
            ->singleFile()
            ->useDisk(Disks::SURF_PUBLIC);
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }
}
