<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Laravel\Scout\Searchable;
use Way2Web\Force\AbstractModel;

class Tag extends AbstractModel
{
    use Searchable;

    public function toSearchableArray(): array
    {
        return [
            'id'    => $this->getKey(),
            'label' => $this->label,
        ];
    }

    public function products(): MorphToMany
    {
        return $this->morphedByMany(Product::class, 'taggable');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(TagType::class, 'type_id');
    }

    public function scopeWithType(Builder $query, string $type): Builder
    {
        return $query->whereHas('type', function (Builder $query) use ($type): void {
            $query->where('name', $type);
        });
    }
}
