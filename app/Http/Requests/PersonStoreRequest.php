<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PersonStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'identifier' => ['string', 'nullable'],
            'first_name' => ['string', 'nullable'],
            'last_name'  => ['string', 'nullable'],
        ];
    }
}
