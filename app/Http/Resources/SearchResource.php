<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SearchResource extends JsonResource
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
                ? PartyResource::collection($this->resource['party'])
                : [],

            'people' => array_key_exists('person', $this->resource)
                ? PersonResource::collection($this->resource['person'])
                : [],

            'products' => array_key_exists('product', $this->resource)
                ? ProductResource::collection($this->resource['product'])
                : [],

            'projects' => array_key_exists('project', $this->resource)
                ? ProjectResource::collection($this->resource['project'])
                : [],
        ];
    }
}