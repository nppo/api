<?php

declare(strict_types=1);

namespace App\Models;

use App\Enumerators\Disks;
use App\Enumerators\MediaCollections;
use App\Enumerators\TagTypes;
use App\Interfaces\HasMetaData;
use App\Models\Support\HasMeta;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Way2Web\Force\AbstractModel;

class Person extends AbstractModel implements HasMedia, HasMetaData
{
    use InteractsWithMedia, HasMeta;

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

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function skills(): MorphToMany
    {
        return $this->tags()->where('type', TagTypes::SKILL);
    }

    public function themes(): MorphToMany
    {
        return $this->morphToMany(Theme::class, 'themeable');
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
