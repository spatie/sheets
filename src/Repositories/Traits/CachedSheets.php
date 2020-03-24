<?php

namespace Spatie\Sheets\Repositories\Traits;

use Closure;
use Spatie\Sheets\Sheet;

trait CachedSheets
{
    protected $cache = [];

    protected function remember(string $path, Closure $callback): Sheet
    {
        $path = $this->normalizePath($path);

        if (! isset($this->cache[$path])) {
            $this->cache[$path] = $callback($path);
        }

        return $this->cache[$path];
    }

    public function forget(string $path): void
    {
        unset($this->cache[$this->normalizePath($path)]);
    }

    abstract protected function normalizePath(string $path): string;
}
