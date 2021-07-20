<?php

declare(strict_types=1);

namespace App\Import\Resolvers;

use App\Import\Interfaces\CompositableResolver;
use Illuminate\Database\Eloquent\Model;

class CompositeResolver extends AbstractResolver
{
    protected array $resolvers;

    public function __construct(array $resolvers = [])
    {
        $this->resolver = $resolvers;
    }

    public function resolve(array $data): ?Model
    {
        /** @var CompositableResolver $resolver */
        foreach ($this->resolvers as $resolver) {
            if ($resolver->canResolve($data)) {
                $resolved = $resolver->resolve($data);

                if ($resolved) {
                    return $resolved;
                }
            }
        }

        return null;
    }

    public function add(CompositableResolver $compositableResolver): self
    {
        $this->resolvers[] = $compositableResolver;

        return $this;
    }
}
