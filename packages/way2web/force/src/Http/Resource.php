<?php

declare(strict_types=1);

namespace Way2Web\Force\Http;

use Illuminate\Http\Resources\Json\JsonResource;
use Way2Web\Force\Interfaces\IsProtected;

abstract class Resource extends JsonResource
{
    public static string $permissionsKey = 'can';

    protected bool $withPermissions = false;

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

        if (!$this->resource instanceof IsProtected || !$this->withPermissions) {
            return $array;
        }

        return array_merge($array, [self::$permissionsKey => $this->resource->aggregatePermissions()]);
    }

    public function withPermissions(bool $active = true): self
    {
        $this->withPermissions = $active;

        return $this;
    }
}
