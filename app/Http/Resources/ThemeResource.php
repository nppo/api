<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Enumerators\Action;
use Illuminate\Http\Request;
use Way2Web\Force\Http\Resource;

class ThemeResource extends Resource
{
    protected array $permissions = [
        Action::UPDATE,
    ];

    /**
     * @param Request $request
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id'    => $this->getKey(),
            'label' => $this->label,
        ];
    }
}
