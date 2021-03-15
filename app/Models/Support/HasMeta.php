<?php

declare(strict_types=1);

namespace App\Models\Support;

use App\Interfaces\HasMetaData;
use App\Models\Structure;
use App\Models\Value;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

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

    public function attributes(): HasMany
    {
        return $this->structure->attributes();
    }

    public function resolveStructure(): Structure
    {
        return Structure::where('label', static::class)->sole();
    }

    public function metaAsArray(): array
    {
        $data = [];

        $this->with(['values.attribute']);

        foreach ($this->values as $value) {
            $data[$value->attribute->label] = $value->value;
        }

        return $data;
    }
}
