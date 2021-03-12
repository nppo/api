<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title'       => ['string', 'nullable'],
            'description' => ['string', 'nullable'],
        ];
    }
}
