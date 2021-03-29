<?php

declare(strict_types=1);

namespace App\Import\Jobs;

use App\External\ShareKit\Facades\ShareKit;
use App\External\ShareKit\Response;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Str;

class ImportAll implements ShouldQueue
{
    use Queueable, InteractsWithQueue, Dispatchable;

    private const PAGE_SIZE = 20;

    public function handle(int $pageNumber = 1): void
    {
        /** @var Response */
        $response = ShareKit::setPaging(self::PAGE_SIZE, $pageNumber)
            ->repoItems();

        $lastPage = $this->findLastPage($response);

        if (!$lastPage) {
            return;
        }

        for ($iterator = 1; $iterator < $lastPage; $iterator++) {
            ImportPage::dispatch($iterator);
        }
    }

    private function findLastPage(Response $response): ?int
    {
        if (!$response->has('links.last')) {
            return null;
        }

        $matches = [];

        preg_match('/page\[number\]\=\d*/', $response->get('links.last'), $matches);

        if (empty($matches)) {
            return null;
        }

        return intval(Str::after($matches[0], 'page[number]='));
    }
}
