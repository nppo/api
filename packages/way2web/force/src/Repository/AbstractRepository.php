<?php

declare(strict_types=1);

namespace Way2Web\Force\Repository;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use RuntimeException;

abstract class AbstractRepository
{
    /**
     * Pagination constants.
     */
    const MAX_PER_PAGE = 250;
    const DEFAULT_PER_PAGE = 15;

    /**
     * Default attributes field used for whereIn and update methods.
     */
    const DEFAULT_ATTRIBUTES_FIELD = 'id';

    /**
     * @return string
     */
    abstract public function model(): string;

    /**
     * @param array $columns
     *
     * @return Collection
     *
     * @throws RuntimeException
     */
    public function all(array $columns = ['*']): Collection
    {
        return $this->makeQuery()->get($columns);
    }

    /**
     * @param array $with
     *
     * @return Builder
     *
     * @throws RuntimeException
     */
    public function with(array $with = []): Builder
    {
        return $this->makeQuery()->with($with);
    }

    /**
     * @param int   $perPage
     * @param array $columns
     *
     * @return LengthAwarePaginator
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function paginate(int $perPage = self::DEFAULT_PER_PAGE, array $columns = ['*']): LengthAwarePaginator
    {
        return $this->makeQuery()->paginate(min($perPage, self::MAX_PER_PAGE), $columns);
    }

    /**
     * @param int   $perPage
     * @param array $columns
     *
     * @return Paginator
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function simplePaginate(int $perPage = self::DEFAULT_PER_PAGE, array $columns = ['*']): Paginator
    {
        return $this->makeQuery()->simplePaginate($perPage, $columns);
    }

    /**
     * @param array $data
     *
     * @return Model
     *
     * @throws RuntimeException
     */
    public function create(array $data = []): Model
    {
        return $this->makeModel()->create($data);
    }

    /**
     * @param array $data
     *
     * @return bool
     *
     * @throws RuntimeException
     */
    public function insert(array $data): bool
    {
        return $this->makeQuery()->insert($data);
    }

    /**
     * @param array  $data
     * @param        $attributeValue
     * @param string $attributeField
     *
     * @return int
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function update(array $data, $attributeValue, string $attributeField = self::DEFAULT_ATTRIBUTES_FIELD): int
    {
        Arr::forget(
            $data,
            [
                '_method',
                '_token',
            ]
        );

        return $this->makeQuery()->where($attributeField, $attributeValue)->update($data);
    }

    /**
     * @param array  $data
     * @param        $attributeValues
     * @param string $attributeField
     *
     * @return int
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function updateWhereIn(
        array $data,
        $attributeValues,
        string $attributeField = self::DEFAULT_ATTRIBUTES_FIELD
    ): int {
        return $this->makeQuery()->whereIn($attributeField, $attributeValues)->update($data);
    }

    /**
     * @param array $attributes
     * @param array $values
     *
     * @return Model
     *
     * @throws RuntimeException
     */
    public function updateOrCreate(array $attributes, array $values = []): Model
    {
        return $this->makeQuery()->updateOrCreate($attributes, $values);
    }

    /**
     * @param int $id
     *
     * @return mixed
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function delete(int $id)
    {
        return $this->makeQuery()->where('id', $id)->delete();
    }

    /**
     * @param array $criteria
     *
     * @return mixed
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function deleteWhere(array $criteria = [])
    {
        return $this->makeQuery()->where($criteria)->delete();
    }

    /**
     * @param array  $values
     * @param string $column
     *
     * @return mixed
     */
    public function deleteWhereIn(array $values, string $column = 'id')
    {
        return $this->makeQuery()->whereIn($column, $values)->delete();
    }

    /**
     * @param int|string $id
     * @param array      $columns
     *
     * @return Model|null
     *
     * @throws RuntimeException
     */
    public function find($id, array $columns = ['*'])
    {
        return $this->makeQuery()->find($id, $columns);
    }

    /**
     * @param int|string $id
     * @param array      $columns
     *
     * @return Builder|Builder[]|Collection|Model|null
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function findAndLock($id, array $columns = ['*'])
    {
        return $this->makeQuery()->lockForUpdate()->find($id, $columns);
    }

    /**
     * @param array $criteria
     * @param array $columns
     *
     * @return Builder|Model|mixed
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function findOneBy(array $criteria = [], array $columns = ['*'])
    {
        return $this->makeQuery()->where($criteria)->first($columns);
    }

    /**
     * @param array $criteria
     * @param array $columns
     *
     * @return Builder|Model|mixed
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function findOneByAndLock(array $criteria = [], array $columns = ['*'])
    {
        return $this->makeQuery()->where($criteria)->lockForUpdate()->first($columns);
    }

    /**
     * @param int|string $id
     * @param array      $columns
     *
     * @return Builder|Builder[]|Collection|Model|null
     *
     * @throws RuntimeException
     */
    public function findOrFail($id, array $columns = ['*'])
    {
        return $this->makeQuery()->findOrFail($id, $columns);
    }

    /**
     * @param int|string $id
     * @param array      $columns
     *
     * @return Builder|Builder[]|Collection|Model|null
     *
     * @throws RuntimeException
     */
    public function findAndLockOrFail($id, array $columns = ['*'])
    {
        return $this->makeQuery()->lockForUpdate()->findOrFail($id, $columns);
    }

    /**
     * @param \Illuminate\Contracts\Support\Arrayable|array $ids
     * @param array                                         $columns
     *
     * @return Collection
     *
     * @throws RuntimeException
     */
    public function findMany($ids, array $columns = ['*'])
    {
        return $this->makeQuery()->findMany($ids, $columns);
    }

    /**
     * @param array $criteria
     * @param array $columns
     *
     * @return Collection
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function findAllBy(array $criteria = [], array $columns = ['*']): Collection
    {
        return $this->makeQuery()->select($columns)->where($criteria)->get();
    }

    /**
     * @param array $data
     *
     * @return int
     *
     * @throws RuntimeException
     */
    public function insertGetId(array $data): int
    {
        return $this->makeQuery()->insertGetId($data);
    }

    /**
     * @return int
     *
     * @throws RuntimeException
     */
    public function count(): int
    {
        return $this->makeQuery()->count();
    }

    /**
     * @param        $id
     * @param string $column
     *
     * @return bool
     */
    public function exists($id, string $column = 'id'): bool
    {
        return $this->makeQuery()->where($column, $id)->exists();
    }

    /**
     * @return Model
     *
     * @throws RuntimeException
     */
    final protected function makeModel(): Model
    {
        $model = app($this->model());

        if (!$model instanceof Model) {
            throw new RuntimeException('Class ' . $this->model() .
                ' must be an instance of Illuminate\\Database\\Eloquent\\Model');
        }

        return $model;
    }

    /**
     * @param bool $timestamps
     *
     * @return Builder
     *
     * @throws RuntimeException
     */
    public function makeQuery(bool $timestamps = true): Builder
    {
        $model = $this->makeModel();
        $model->timestamps = $model->timestamps ? $timestamps : false;

        return $model->newQuery();
    }
}
