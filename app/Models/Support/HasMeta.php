<?php

declare(strict_types=1);

namespace App\Models\Support;

use App\Interfaces\HasMetaData;
use App\Models\Attribute;
use App\Models\Structure;
use App\Models\Value;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;
use InvalidArgumentException;

trait HasMeta
{
    protected static function bootHasMeta(): void
    {
        static::creating(function (HasMetaData $model): void {
            $model->structure()->associate($model->resolveStructure());
        });
    }

    public function structure(): BelongsTo
    {
        return $this->belongsTo(Structure::class);
    }

    public function values(): MorphMany
    {
        return $this->morphMany(Value::class, 'entity');
    }

    public function attributes(): HasManyThrough
    {
        return $this->hasManyThrough(
            Attribute::class,
            Structure::class,
            'id',
            'structure_id',
            'structure_id',
            'id'
        );
    }

    public function resolveStructure(): Structure
    {
        return Structure::where('label', static::class)->sole();
    }

    public function syncMeta(Collection $metaData): self
    {
        $metaData
            ->map(function ($data): Value {
                if ($data instanceof Value) {
                    return $data;
                }

                if (is_array($data)) {
                    return new Value($data);
                }

                throw new InvalidArgumentException('Provided information could not be casted to a Value');
            })
            ->filter(function (Value $value) {
                return $this->whereHas('attributes', function (Builder $builder) use ($value): Builder {
                    return $builder->where('id', $value->id);
                });
            })
            ->each(function (Value $value): void {
                if (is_null($value->value)) {
                    $this->values()->where('attribute_id', $value->attribute_id)->delete();

                    return;
                }

                $this->values()
                    ->updateOrCreate(
                        ['attribute_id' => $value->attribute_id],
                        ['value' => $value->value]
                    );
            });

        return $this;
    }
}
