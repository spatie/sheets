<?php

namespace Spatie\Sheets\Repositories;

use Illuminate\Support\Collection;
use Spatie\Sheets\Repository;
use Spatie\Sheets\Sheet;
use Illuminate\Contracts\Cache\Repository as Cache;

class CacheRepository implements Repository
{
    /** @var string */
    protected $collectionName;

    /** @var \Spatie\Sheets\Repository */
    protected $repository;

    /** @var \Illuminate\Contracts\Cache\Repository */
    protected $cache;

    public function __construct(string $collectionName, Repository $repository, Cache $cache)
    {
        $this->collectionName = $collectionName;
        $this->repository = $repository;
        $this->cache = $cache;
    }

    public function get(string $path): ?Sheet
    {
        return $this->cache->rememberForever("sheets:{$this->collectionName}:$path", function () use ($path) {
            return $this->repository->get($path);
        });
    }

    public function all(): Collection
    {
        return $this->cache->rememberForever("sheets:{$this->collectionName}:__all", function () {
            return $this->repository->all();
        });
    }
}
