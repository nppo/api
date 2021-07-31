<?php

declare(strict_types=1);

namespace App\Models\Support;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection as SupportCollection;

trait HasTags
{
    public function syncTags(array $tags, string $type, bool $onlyExisting = false): void
    {
        $this->tags()->detach(Tag::where('type', $type)->get());

        if ($onlyExisting) {
            $this->tags()->attach($this->findTags($tags, $type));

            return;
        }

        $this->tags()->attach($this->findOrCreateTags($tags, $type)->pluck('id'));
    }

    public function tags(): MorphToMany
    {
        return $this->tagRelation(Tag::class);
    }

    public function tagRelation(string $class): MorphToMany
    {
        return $this->morphToMany($class, 'taggable', 'taggables', 'taggable_id', 'tag_id');
    }

    protected function findOrCreateTags(array $tags, ?string $type = null): SupportCollection
    {
        return Tag::findOrCreate($tags, $type);
    }

    protected function findTags(array $tags, ?string $type = null): Collection
    {
        return Tag::where('type', $type)
            ->whereIn('label', $tags)
            ->get();
    }
}
