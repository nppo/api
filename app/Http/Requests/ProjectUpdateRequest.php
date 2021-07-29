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
            'parties.*.id' => ['required', 'uuid'],

            'products'      => ['nullable'],
            'products.*'    => ['array', 'required'],
            'products.*.id' => ['required', 'uuid'],

            'project_picture' => [
                'sometimes',
                'image',
                'mimes:jpg,jpeg,bmp,png,gif',
                'max:' . $this->getMaxFileSize(),
            ],
        ];
    }

    private function getMaxFileSize(): string
    {
        return (string) config('media-library.max_file_size');
    }
}
