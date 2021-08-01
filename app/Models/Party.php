<?php

declare(strict_types=1);

namespace App\Models;

use App\Enumerators\Disks;
use App\Enumerators\MediaCollections;
use App\Models\Support\HasExternalResource;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Way2Web\Force\AbstractModel;
use Way2Web\Force\HasUuid;

class Party extends AbstractModel implements HasMedia
{
    use InteractsWithMedia;
    use HasExternalResource;
    use HasUuid;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'description',
    ];

    public function parties(): MorphToMany
    {
        return $this->morphToMany(self::class, 'affiliable');
    }

    public function people(): MorphToMany
    {
        return $this->morphToMany(Person::class, 'affiliable');
    }

    public function products(): MorphToMany
    {
        return $this->morphToMany(Product::class, 'contributable')->withCount('likes');
    }

    public function projects(): MorphToMany
    {
        return $this->morphToMany(Project::class, 'cooperable');
    }

    public function getPartyPictureUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl(MediaCollections::PARTY_PICTURE);
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection(MediaCollections::PARTY_PICTURE)
            ->singleFile()
            ->useDisk(Disks::SURF_PUBLIC);
    }
}
