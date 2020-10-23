<?php

namespace Spatie\Sheets\Repositories;

use Illuminate\Contracts\Filesystem\Factory as FilesystemManagerContract;
use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;
use Illuminate\Support\Str;
use Spatie\Sheets\Collections\SheetCollection;
use Spatie\Sheets\Factory;
use Spatie\Sheets\Repositories\Traits\CachedSheets;
use Spatie\Sheets\Repository;
use Spatie\Sheets\Sheet;

class FilesystemRepository implements Repository
{
    use CachedSheets;

    /** @var \Spatie\Sheets\Factory */
    protected $factory;

    /** @var \Illuminate\Contracts\Filesystem\Filesystem */
    protected $filesystem;

    /** @var string */
    protected $extension;

    /** @var string */
    protected $collection;

    public function __construct(Factory $factory, FilesystemManagerContract $filesystem, array $config = [])
    {
        $this->factory = $factory;
        $this->filesystem = $filesystem->disk($config['disk'] ?? null);
        $this->extension = $config['extension'] ?? 'md';
        $this->collection = $config['collection'] ?? SheetCollection::class;
    }

    public function get(string $path): ?Sheet
    {
        $path = $this->normalizePath($path);

        if (! $this->filesystem->exists($path)) {
            return null;
        }

        return $this->remember($path, function (string $path): Sheet {
            return $this->factory->make($path, $this->filesystem->get($path));
        });
    }

    public function all(): Collection
    {
        return $this->allLazy()->collect();
    }

    public function allLazy(): LazyCollection
    {
        return $this->collect()
            ->filter(function (string $path) {
                return Str::endsWith($path, ".{$this->extension}");
            })
            ->map(function (string $path) {
                return $this->get($path);
            });
    }

    protected function normalizePath(string $path): string
    {
        return Str::finish($path, ".{$this->extension}");
    }

    protected function collect(): LazyCollection
    {
        return forward_static_call(
            [$this->collection, 'make'],
            $this->filesystem->allFiles()
        );
    }
}
