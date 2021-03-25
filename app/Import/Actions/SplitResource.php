<?php

declare(strict_types=1);

namespace App\Import\Actions;

use App\Import\Action;
use App\Import\SyncResource;
use App\Models\ExternalResource;
use Closure;
use Flow\JSONPath\JSONPath;
use Illuminate\Support\Arr;
use Iterator;

class SplitResource implements Action
{
    protected string $type;
    protected string $path;

    protected ?Closure $onlyWhenCallback = null;
    protected ?Closure $identifierCallback = null;

    public function __construct(string $type, string $path)
    {
        $this->type = $type;
        $this->path = $path;
    }

    public function process(ExternalResource $externalResource): void
    {
        if ($this->onlyWhenCallback) {
            $callback = $this->onlyWhenCallback;

            if (!$callback($externalResource)) {
                return;
            }
        }

        foreach ($this->findResource($externalResource) as $resource) {
            if ($resource instanceof JSONPath) {
                $resource = $resource->getData();
            }

            $this->splitResource($externalResource, Arr::wrap($resource));
        }
    }

    public function resolveIdentifierUsing(?Closure $closure): self
    {
        $this->identifierCallback = $closure;

        return $this;
    }

    public function onlyWhen(?Closure $closure): self
    {
        $this->onlyWhenCallback = $closure;

        return $this;
    }

    private function splitResource(ExternalResource $externalResource, array $data): void
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

    private function findResource(ExternalResource $externalResource): Iterator
    {
        return (new JSONPath($externalResource->data))->find($this->path);
    }
}
