<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PersonUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'first_name' => ['string', 'nullable'],
            'last_name'  => ['string', 'nullable'],
            'about'      => ['string', 'nullable'],
        ];
    }
}
