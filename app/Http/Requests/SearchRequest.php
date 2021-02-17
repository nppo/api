<?php

declare(strict_types=1);

namespace App\Http\Requests;

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
}
