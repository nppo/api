<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

use App\Enumerators\Roles;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'role' => ['nullable', 'string', Rule::in(Roles::asArray())],
        ];
    }
}
