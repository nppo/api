<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id'          => ['required', 'integer', 'exists:projects,id'],
            'title'       => ['string', 'nullable'],
            'description' => ['string', 'nullable'],
            'purpose'     => ['string', 'nullable'],
        ];
    }

    public function getId(): int
    {
        return $this->validated()['id'];
    }
}
