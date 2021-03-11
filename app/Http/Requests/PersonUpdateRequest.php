<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PersonUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'first_name'      => ['string', 'nullable'],
            'last_name'       => ['string', 'nullable'],
            'about'           => ['string', 'nullable'],
            'tags'            => ['array', 'nullable'],
            'tags.*.id'       => ['required', 'integer'],
            'profile_picture' => [
                'sometimes',
                'image',
                'mimes:jpg,jpeg,bmp,png,gif',
                'max:' . $this->getMaxFileSize(),
                'dimensions:ratio=1/1',
            ],
        ];
    }

    private function getMaxFileSize(): string
    {
        return (string) config('media-library.max_file_size');
    }
}
