<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Way2Web\Force\AbstractModel;

class Person extends AbstractModel
{
    public function products(): MorphToMany
    {
        return $this->morphToMany(Product::class, 'contributable');
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function likes(): MorphToMany
    {
        return $this->morphToMany(User::class, 'likeable');
    }
}
