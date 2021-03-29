<?php

declare(strict_types=1);

namespace App\Import\Jobs;

use App\Enumerators\ImportDriver;
use App\Enumerators\ImportType;
use App\External\ShareKit\Entities\RepoItem;
use App\External\ShareKit\Facades\ShareKit;
use App\External\ShareKit\Response;
use App\Import\SyncResource;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

class ImportPage implements ShouldQueue
{
    use Queueable, InteractsWithQueue, Dispatchable;

    private const PAGE_SIZE = 20;

    public function handle(int $pageNumber = 1): void
    {
        /** @var Response */
        $response = ShareKit::setPaging(self::PAGE_SIZE, $pageNumber)
            ->repoItems();

        $response
            ->each(function (RepoItem $repoItem): void {
                SyncResource::dispatch(
                    ImportDriver::SHAREKIT,
                    ImportType::PRODUCT,
                    $repoItem->id,
                    $repoItem->getAttributes()
                );
            });
    }
}
