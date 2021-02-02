<?php

declare(strict_types=1);

namespace App\External\ShareKit\Entities;

use App\External\ShareKit\Entity;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class RepoItem extends Entity
{
    public static function createFromData(array $data)
    {
        return new self(Arr::except($data, 'attributes'), Arr::get($data, 'attributes'));
    }

    public function getAuthors(): Collection
    {
        $relations = [];

        $authors = Arr::get($this->data, 'authors');

        if (!is_array($authors)) {
            $authors = [];
        }

        foreach ($authors as $authorData) {
            if (Arr::has($authorData, 'person')) {
                $relations[] = new Person(
                    Arr::except($authorData, 'person'),
                    Arr::get($authorData, 'person')
                );
            }
        }

        return collect($relations);
    }
}
