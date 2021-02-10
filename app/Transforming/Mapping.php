<?php

declare(strict_types=1);

namespace App\Transforming;

use App\Transforming\Support\MapsData;

class Mapping
{
    use MapsData;

    protected array $maps = [];

    public function __construct(array $maps = [])
    {
        foreach ($maps as $map) {
            $this->addMap($map);
        }
    }

    public function addMap(Map $map): self
    {
        $this->maps[$map->getFrom()][] = $map;

        return $this;
    }

    public function hasMap(string $fromKey): bool
    {
        if (array_key_exists($fromKey, $this->maps)) {
            return count($this->maps[$fromKey]) > 0;
        }

        return false;
    }

    /** @return mixed */
    public function apply(array $using, &$on)
    {
        foreach ($this->maps as $mapsForKey) {
            foreach ($mapsForKey as $map) {
                $this->map($map, $using, $on);
            }
        }

        return $on;
    }
}
