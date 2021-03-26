<?php

declare(strict_types=1);

namespace App\Import\Actions;

use App\Import\Actions\Support\Limitable;
use App\Import\Actions\Support\Skippable;
use App\Import\Interfaces\Action;
use App\Models\ExternalResource;

abstract class AbstractAction implements Action
{
    use Skippable, Limitable;

    abstract public function process(ExternalResource $externalResource): void;

    public function handle(ExternalResource $externalResource): void
    {
        if ($this->shouldBeSkipped($externalResource)) {
            return;
        }

        if (!$this->shouldRun($externalResource)) {
            return;
        }

        $this->process($externalResource);
    }
}
