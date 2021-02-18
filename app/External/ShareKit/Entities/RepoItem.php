<?php

declare(strict_types=1);

namespace App\External\ShareKit\Entities;

use App\External\ShareKit\Entity;
use Illuminate\Support\Arr;

class RepoItem extends Entity
{
    public static function createFromData(array $data)
    {
        $attributes = Arr::get($data, 'attributes');
        $id = Arr::get($data, 'id');

        return new self(
            array_merge($attributes, ['id' => $id])
        );
    }

    public function authors()
    {
        return $this->hasMany(Person::class, 'authors');
    }
}
