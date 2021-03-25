<?php

declare(strict_types=1);

namespace App\Import\Actions;

use App\Import\Action;
use App\Import\SyncResource;
use App\Models\ExternalResource;
use Closure;
use Flow\JSONPath\JSONPath;
use Iterator;

class SplitResource implements Action
{
    protected string $type;
    protected string $path;

    protected Closure $closure;

    public function __construct(string $type, string $path)
    {
        $this->type = $type;
        $this->path = $path;
    }

    public function process(ExternalResource $externalResource): void
    {
        foreach ($this->findResource($externalResource) as $resource) {
            $this->splitResource($externalResource, $resource->getData());
        }
    }

    public function resolveIdentifierUsing(?Closure $closure): self
    {
        $this->closure = $closure;

        return $this;
    }

    private function splitResource(ExternalResource $externalResource, array $data): void
    {
        $identifier = null;

        if ($this->closure) {
            $closure = $this->closure;
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

    private function findResource(ExternalResource $externalResource): Iterator
    {
        return (new JSONPath($externalResource->data))->find($this->path);
    }
}
