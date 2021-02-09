<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductSearchRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'query'   => ['string', 'nullable'],
            'filters' => ['array', 'nullable'],
        ];
    }
}
