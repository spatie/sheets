<?php

namespace Spatie\Sheets;

use Illuminate\Support\Collection;
use RuntimeException;

class Sheets implements Repository
{
    /** @var \Spatie\Sheets\Repository[] */
    protected $collections;

    /** @var string|null */
    protected $defaultCollection;

    public function collection(string $name): Repository
    {
        if (! isset($this->collections[$name])) {
            throw new RuntimeException("Collection `{$name}` doesn't exist");
        }

        return $this->collections[$name];
    }

    public function registerCollection(string $name, Repository $repository)
    {
        $this->collections[$name] = $repository;
    }

    public function get(string $path): ?Sheet
    {
        return $this->defaultCollection()->get($path);
    }

    public function all(): Collection
    {
        return $this->defaultCollection()->all();
    }

    public function setDefaultCollection(string $defaultCollection)
    {
        if (! isset($this->collections[$defaultCollection])) {
            throw new RuntimeException("Can't set default collection `{$defaultCollection}` because it isn't registered.");
        }

        $this->defaultCollection = $defaultCollection;
    }

    public function getRegisteredCollectionNames(): array
    {
        return array_keys($this->collections);
    }

    protected function defaultCollection(): Repository
    {
        if (empty($this->collections)) {
            throw new RuntimeException("Can't retrieve a default collection if no collections are registered.");
        }

        return $this->collection(
            $this->defaultCollection ?? $this->getRegisteredCollectionNames()[0]
        );
    }
}
