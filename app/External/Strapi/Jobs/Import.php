<?php

declare(strict_types=1);

namespace App\External\Strapi\Jobs;

use App\Enumerators\ImportDriver;
use App\Enumerators\ImportType;
use App\Enumerators\Queue;
use App\External\Strapi\Facades\Strapi;
use App\Import\SyncResource;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

class Import implements ShouldQueue
{
    use Queueable, InteractsWithQueue, Dispatchable;

    public function __construct()
    {
        $this->queue = Queue::IMPORT_EXTERNAL;
    }

    public function handle(): void
    {
        Strapi::getArticles()
            ->each(function ($article): void {
                SyncResource::dispatch(
                    ImportDriver::STRAPI,
                    ImportType::ARTICLE,
                    $article->id,
                    $article
                );
            });
    }
}
