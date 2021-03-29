<?php

declare(strict_types=1);

namespace App\Import;

use App\Models\ExternalResource;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncResource implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $driver;
    protected string $type;
    protected ?string $identifier;
    protected array $data;
    protected ?ExternalResource $externalResource;

    public function __construct(
        string $driver,
        string $type,
        ?string $identifier = null,
        array $data = [],
        ?ExternalResource $externalResource = null
    ) {
        $this->driver = $driver;
        $this->type = $type;
        $this->identifier = $identifier;
        $this->data = $data;
        $this->externalResource = $externalResource;
    }

    public function handle(): void
    {
        if ($this->makeQuery()->exists()) {
            /** @var ExternalResource */
            $model = $this->makeQuery()->sole();

            $model->update(['data' => $this->data]);

            $this->updateParent($model);
            $this->finish($model);

            return;
        }

        $externalResource = ExternalResource::create([
            'driver'              => $this->driver,
            'type'                => $this->type,
            'external_identifier' => $this->identifier,
            'data'                => $this->data,
        ]);

        $this->updateParent($externalResource);
        $this->finish($externalResource);
    }

    private function updateParent(ExternalResource $externalResource): void
    {
        if ($this->externalResource) {
            $externalResource->parent()->associate($this->externalResource);
            $externalResource->save();
        }
    }

    private function makeQuery(): Builder
    {
        $query = ExternalResource::where('driver', $this->driver)
            ->where('type', $this->type);

        if (!is_null($this->identifier)) {
            $query->where('external_identifier', $this->identifier);
        }

        return $query;
    }

    private function finish(ExternalResource $externalResource): void
    {
        (new ProcessResource($externalResource))->handle();
    }
}
