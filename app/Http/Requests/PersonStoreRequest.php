<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PersonStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'identifier' => ['string', 'required'],
            'first_name' => ['string', 'nullable'],
            'last_name'  => ['string', 'nullable'],
            'phone'      => ['string', 'nullable'],
            'function'   => ['string', 'nullable'],
            'about'      => ['string', 'nullable'],

            'themes'      => ['array', 'min:1'],
            'themes.*'    => ['array', 'required'],
            'themes.*.id' => ['required', 'integer'],
        ];
    }
}
