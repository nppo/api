<?php

declare(strict_types=1);

namespace Way2Web\Force\Http;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as RoutingController;
use Illuminate\Support\Collection;

abstract class Controller extends RoutingController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected array $actionRoutes = [
            'create',
            'store',
            'edit',
            'update',
            'destroy',
    ];

    protected function protectActionRoutes(array $guards = []): self
    {
        foreach ($guards as $guard) {
            $this->middleware('auth:' . $guard)
                ->only($this->actionRoutes);
        }

        return $this;
    }

    protected function syncRelation(Model $model, string $relation, array $entities, array $values = []): self
    {
        $model->{$relation}()
            ->syncWithPivotValues(
                Collection::make($entities)->pluck('id'),
                $values
            );

        return $this;
    }
}
