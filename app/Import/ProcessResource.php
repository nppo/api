<?php

declare(strict_types=1);

namespace App\Import;

use App\Import\Interfaces\Action;
use App\Models\ExternalResource;
use Illuminate\Support\Facades\Config;
use InvalidArgumentException;

class ProcessResource
{
    protected ExternalResource $externalResource;

    public function __construct(ExternalResource $externalResource)
    {
        $this->externalResource = $externalResource;
    }

    public function handle(): void
    {
        foreach ($this->resolveActions() as $action) {
            if (!$action instanceof Action) {
                throw new InvalidArgumentException('Action does not implement interface');
            }

            $action->handle($this->externalResource);
        }
    }

    private function resolveActions(): array
    {
        $actions = [];
        $configKey = $this->getActionsKey($this->externalResource->driver, $this->externalResource->type);

        if (Config::has($configKey)) {
            $actions = array_merge($actions, Config::get($configKey));
        }

        return $actions;
    }

    private function getActionsKey(string $driver, string $type)
    {
        return 'import.drivers.' . $driver . '.' . $type . '.actions';
    }
}
