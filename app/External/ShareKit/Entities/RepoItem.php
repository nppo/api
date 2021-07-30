<?php

declare(strict_types=1);

namespace App\External\ShareKit\Entities;

use App\External\ShareKit\Entity;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class RepoItem extends Entity
{
    public static function createFromData(array $data): self
    {
        $attributes = Arr::get($data, 'attributes');
        $id = Arr::get($data, 'id');

        return new self(
            array_merge($attributes, ['id' => $id])
        );
    }

    public function authors(): Collection
    {
        return $this->hasMany(Person::class, 'authors');
    }
}
