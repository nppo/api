<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title'       => ['string', 'nullable'],
            'description' => ['string', 'nullable'],
            'purpose'     => ['string', 'nullable'],

            'parties'      => ['nullable'],
            'parties.*'    => ['array', 'required'],
            'parties.*.id' => ['required', 'integer'],

            'products'      => ['nullable'],
            'products.*'    => ['array', 'required'],
            'products.*.id' => ['required', 'integer'],
        ];
    }
}
