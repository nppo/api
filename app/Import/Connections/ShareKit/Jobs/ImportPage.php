<?php

declare(strict_types=1);

namespace App\Import\Connections\ShareKit\Jobs;

use App\Enumerators\ImportDriver;
use App\Enumerators\ImportType;
use App\Enumerators\Queue;
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

    protected int $pageNumber;

    public function __construct(int $pageNumber = 1)
    {
        $this->pageNumber = $pageNumber;
        $this->queue = Queue::IMPORT_EXTERNAL;
    }

    public function handle(): void
    {
        /** @var Response */
        $response = ShareKit::setPaging(self::PAGE_SIZE, $this->pageNumber)
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
