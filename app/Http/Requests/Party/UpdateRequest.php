<?php

declare(strict_types=1);

namespace App\Http\Requests\Party;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'min:3'],
            'description' => ['required', 'string', 'min:3'],
        ];
    }
}
