<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\MissingValue;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Str;
use Way2Web\Force\Http\Resource;

class SearchResource extends Resource
{
    /**
     * @param Request $request
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'results' => $this->resource['count'],

            'parties' => array_key_exists('party', $this->resource)
                ? $this->getItems('party')
                : ['items' => []],

            'people' => array_key_exists('person', $this->resource)
                ? $this->getItems('person')
                : ['items' => []],

            'products' => array_key_exists('product', $this->resource)
                ? $this->getItems('product')
                : ['items' => []],

            'projects' => array_key_exists('project', $this->resource)
                ? $this->getItems('project')
                : ['items' => []],

            'articles' => array_key_exists('article', $this->resource)
                ? $this->getItems('article')
                : ['items' => []],
        ];
    }

    /** @return MissingValue|mixed */
    private function getItems(string $entityType)
    {
        $resourceMethod = 'App\\Http\\Resources\\' . Str::ucfirst($entityType) . 'Resource';

        return $this
            ->when(
                get_class($this->resource[$entityType]) === CursorPaginator::class,
                function () use ($entityType, $resourceMethod): array {
                    return [
                        'items'      => call_user_func([$resourceMethod, 'collection'], $this->resource[$entityType]),
                        'nextCursor' => $this->getNextCursor($entityType),
                    ];
                },
                function () use ($entityType, $resourceMethod): array {
                    return ['items' => call_user_func($resourceMethod, $this->resource[$entityType])];
                }
            );
    }

    /** @return bool|string|null */
    private function getNextCursor(string $type)
    {
        /** @var CursorPaginator $resource */
        $resource = $this->resource[$type];
        if (! $resource->hasMorePages()) {
            return false;
        }

        return $resource->nextCursor()
            ? $this->resource[$type]->nextCursor()->encode()
            : null;
    }
}
