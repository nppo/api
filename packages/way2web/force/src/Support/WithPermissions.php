<?php

declare(strict_types=1);

namespace Way2Web\Force\Support;

use Illuminate\Support\Facades\Gate;

trait WithPermissions
{
    protected function readPermissions(): array
    {
        return $this->permissions;
    }

    public function aggregatePermissions(): array
    {
        $permissions = [];

        foreach ($this->readPermissions() as $permission) {
            $permissions[$permission] = Gate::allows($permission, $this);
        }

        return $permissions;
    }
}
