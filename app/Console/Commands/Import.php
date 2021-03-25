<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enumerators\ImportDriver;
use App\Enumerators\ImportType;
use App\External\ShareKit\Entities\RepoItem;
use App\External\ShareKit\Facades\ShareKit;
use App\Import\SyncResource;
use Illuminate\Console\Command;

class Import extends Command
{
    protected $signature = 'import';

    protected $description = 'Imports data from external systems';

    public function handle(): void
    {
        ShareKit::repoItems()
            ->each(function (RepoItem $repoItem): void {
                (new SyncResource(
                    ImportDriver::SHAREKIT,
                    ImportType::PRODUCT,
                    $repoItem->id,
                    $repoItem->getAttributes()
                ))
                    ->handle();
            });
    }
}
