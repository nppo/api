<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Support\IsTag;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;
use Laravel\Scout\Searchable;
use Way2Web\Force\AbstractModel;

class Tag extends AbstractModel
{
    use Searchable, IsTag;

    protected static $tagType = null;

    protected $fillable = [
        'label',
        'type',
    ];

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

    public static function findOrCreate(array $values, ?string $type = null): Collection
    {
        return Collection::make($values)
            ->map(function (string $value) use ($type) {
                return self::findOrCreateFromLabel($value, $type);
            });
    }

    protected static function findOrCreateFromLabel(string $label, ?string $type = null): self
    {
        $tag = self::where('type', $type)->where('label', $label)->first();

        if (!$tag) {
            $tag = self::create(['label' => $label, 'type' => $type]);
        }

        return $tag;
    }
}
