<?php

declare(strict_types=1);

namespace Way2Web\Force\Http;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;

abstract class Resource extends JsonResource
{
    public static string $permissionsKey = 'can';

    protected bool $withPermissions = false;

    protected array $permissions = [];

    /**
     * Resolve the resource to an array.
     *
     * @param \Illuminate\Http\Request|null $request
     *
     * @return array
     */
    public function resolve($request = null): array
    {
        $array = parent::resolve($request);

        if (!$this->withPermissions) {
            return $array;
        }

        return array_merge($array, [self::$permissionsKey => $this->aggregatePermissions()]);
    }

    public function withPermissions(bool $active = true): self
    {
        $this->withPermissions = $active;

        return $this;
    }

    public function setPermissions(array $permissions = []): self
    {
        $this->permissions = $permissions;

        return $this;
    }

    public function includePermissions(array $permissions = []): self
    {
        $this->permissions = array_merge($this->permissions, $permissions);

        return $this;
    }

    protected function aggregatePermissions(): array
    {
        $permissions = [];

        foreach ($this->permissions as $permission) {
            $permissions[$permission] = Gate::allows($permission, $this);
        }

        return $permissions;
    }
}
