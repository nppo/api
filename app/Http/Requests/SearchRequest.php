<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enumerators\Entities;
use App\Enumerators\Filters;
use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'query'   => ['string', 'nullable'],
            'filters' => ['array', 'nullable'],
        ];
    }

    public function getFilters(): array
    {
        return array_key_exists('filters', $this->validated()) ? $this->validated()['filters'] : [];
    }

    public function getQuery(): string
    {
        return array_key_exists('query', $this->validated()) ? (string) $this->validated()['query'] : '';
    }

    public function getTypes(): array
    {
        if (array_key_exists(Filters::TYPES, $this->getFilters())) {
            $types = [];

            foreach ($this->getFilters()[Filters::TYPES] as $type) {
                $types[] = Entities::getByReferableKey($type);
            }

            return $types;
        }

        return array_values(Entities::asArray());
    }
}
