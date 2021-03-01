<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Way2Web\Force\AbstractModel;

class Party extends AbstractModel
{
    public function parties(): MorphToMany
    {
        return $this->morphToMany(Party::class, 'affiliable');
    }

    public function people(): MorphToMany
    {
        return $this->morphToMany(Person::class, 'affiliable');
    }

    public function products(): MorphToMany
    {
        return $this->morphToMany(Product::class, 'contributable')->withCount('likes');
    }
}
