<?php

declare(strict_types=1);

namespace App\Import\Resolvers\Tag;

use App\Import\Interfaces\CompositableResolver;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class LabelResolver implements CompositableResolver
{
    protected ?string $type = null;

    public function __construct(?string $type = null)
    {
        $this->type = $type;
    }

    public function canResolve(array $data): bool
    {
        return Arr::has($data, 'label') && !empty(Arr::get($data, 'label'));
    }

    public function resolve(array $data): ?Tag
    {
        if ($this->canResolve($data)) {
            $query = $this->query(Arr::get($data, 'label'));

            if ($query->count() === 1) {
                return $query->sole();
            }
        }

        return null;
    }

    private function query(string $label): Builder
    {
        return Tag::where('type', $this->type)->where('label', $label);
    }
}
