<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Way2Web\Force\Http\Resource;

class ArticleResource extends Resource
{
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
            'id'         => $this->id,
            'title'      => $this->title,
            'previewUrl' => $this->preview_url,
            'summary'    => $this->summary,

            'header'  => $this->header,
            'content' => $this->content,

            'keywords' => $this->whenLoaded('keywords', function (): AnonymousResourceCollection {
                return KeywordResource::collection($this->keywords);
            }),

            'themes' => $this->whenLoaded('themes', function (): AnonymousResourceCollection {
                return ThemeResource::collection($this->themes);
            }),
        ];
    }
}
