<?php

declare(strict_types=1);

namespace App\Models\Support;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasTags
{
    public function syncTags(array $tags, string $type = null, bool $onlyExisting = false): void
    {
        if ($onlyExisting) {
            $this->tags()->sync($this->findTags($tags, $type));

            return;
        }

        $this->tags()->sync($this->findOrCreateTags($tags, $type)->pluck('id'));
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    protected function findOrCreateTags(array $tags, ?string $type = null)
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
