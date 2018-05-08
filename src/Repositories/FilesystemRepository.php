<?php

namespace Spatie\Sheets\Repositories;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Spatie\Sheets\Factory;
use Spatie\Sheets\Repository;
use Spatie\Sheets\Sheet;

class FilesystemRepository implements Repository
{
    /** @var \Spatie\Sheets\Factory */
    protected $factory;

    /** @var \Illuminate\Contracts\Filesystem\Filesystem */
    protected $filesystem;

    /** @var string */
    protected $extension;

    public function __construct(Factory $factory, Filesystem $filesystem, string $extension = 'md')
    {
        $this->factory = $factory;
        $this->filesystem = $filesystem;
        $this->extension = $extension;
    }

    public function get(string $path): ?Sheet
    {
        if (! ends_with($path, $this->extension)) {
            $path = "{$path}.{$this->extension}";
        }

        if (! $this->filesystem->exists($path)) {
            return null;
        }

        return $this->factory->make($path, $this->filesystem->get($path));
    }

    public function all(): Collection
    {
        return collect($this->filesystem->allFiles())
            ->filter(function (string $path) {
                return ends_with($path, ".{$this->extension}");
            })
            ->map(function (string $path) {
                return $this->get($path);
            });
    }
}
