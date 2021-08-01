<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;
use Laravel\Scout\Searchable;
use Way2Web\Force\AbstractModel;
use Way2Web\Force\HasUuid;

class Tag extends AbstractModel
{
    use Searchable;
    use HasUuid;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
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
