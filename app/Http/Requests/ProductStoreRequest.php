<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enumerators\Mimes;
use App\Enumerators\ProductTypes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'type' => [Rule::in(ProductTypes::asArray())],

            'title'        => ['required'],
            'description'  => ['nullable'],
            'published_at' => ['nullable', 'date'],
            'summary'      => ['nullable'],

            'tags'         => ['array', 'nullable'],
            'tags.*.label' => ['required', 'strings'],

            'themes'      => ['array', 'nullable'],
            'themes.*.id' => ['required', 'integer'],

            'people'      => ['array', 'nullable'],
            'people.*.id' => ['required', 'integer'],

            'parties'      => ['array', 'nullable'],
            'parties.*.id' => ['required', 'integer'],

            'file' => ['nullable', 'mimes:' . Mimes::asArrayString()],
        ];
    }
}
