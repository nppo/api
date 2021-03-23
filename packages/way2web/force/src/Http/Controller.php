<?php

declare(strict_types=1);

namespace Way2Web\Force\Http;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as RoutingController;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Way2Web\Force\Repository\AbstractRepository;

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

    protected function syncHasManyRelation(
        Model $model,
        AbstractRepository $repository,
        string $relation,
        array $validated
    ): self {
        if (!array_key_exists('children', $validated)) {
            return $this;
        }

        $ids = Collection::make(Arr::get($validated, $relation) ?? [])->pluck('id');

        if ($ids->count() === 0) {
            $model->{$relation}()->update(['parent_id' => null]);
        } else {
            $model->{$relation}()->saveMany($repository->findMany($ids));
        }

        return $this;
    }

    protected function syncBelongsToRelation(
        Model $model,
        AbstractRepository $productRepository,
        string $relation,
        array $validated
    ): self {
        if (!array_key_exists('parent', $validated)) {
            return $this;
        }

        if (isset($validated['parent']['id']) && $validated['parent']['id'] !== $model->{"{$relation}_id"}) {
            $model->parent()->associate($productRepository->findOrFail($validated['parent']['id']));
        } else {
            $model->parent()->dissociate();
        }

        $model->save();

        return $this;
    }
}
