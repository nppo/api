<?php

declare(strict_types=1);

namespace App\Import\Actions;

use App\Import\Actions\Support\Skippable;
use App\Import\Interfaces\Action;
use App\Import\SyncResource;
use App\Models\ExternalResource;
use Closure;
use Flow\JSONPath\JSONPath;
use Illuminate\Support\Arr;
use Iterator;

class SplitResource implements Action
{
    use Skippable;

    protected string $type;
    protected string $path;

    protected ?Closure $identifierCallback = null;

    public function __construct(string $type, string $path)
    {
        $this->type = $type;
        $this->path = $path;
    }

    public function process(ExternalResource $externalResource): void
    {
        if ($this->shouldBeSkipped($externalResource)) {
            return;
        }

        foreach ($this->findNestedResources($externalResource) as $resource) {
            if ($resource instanceof JSONPath) {
                $resource = $resource->getData();
            }

            $this->createChildResource($externalResource, Arr::wrap($resource));
        }
    }

    public function resolveIdentifierUsing(?Closure $closure): self
    {
        $this->identifierCallback = $closure;

        return $this;
    }

    private function createChildResource(ExternalResource $externalResource, array $data): void
    {
        $identifier = null;

        if ($this->identifierCallback) {
            $closure = $this->identifierCallback;
            $identifier = $closure($data);
        }

        (new SyncResource(
            $externalResource->driver,
            $this->type,
            $identifier,
            $data,
            $externalResource
        ))
            ->handle();
    }

    private function findNestedResources(ExternalResource $externalResource): Iterator
    {
        return (new JSONPath($externalResource->data))
            ->find($this->path);
    }
}
