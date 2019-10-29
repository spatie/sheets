<?php

namespace Spatie\Sheets\Repositories;

use Spatie\Sheets\Sheet;
use Spatie\Sheets\Factory;
use Illuminate\Support\Str;
use Spatie\Sheets\Repository;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Filesystem\Factory as FilesystemManagerContract;

class FilesystemRepository implements Repository
{
    /** @var \Spatie\Sheets\Factory */
    protected $factory;

    /** @var \Illuminate\Contracts\Filesystem\Filesystem */
    protected $filesystem;

    /** @var string */
    protected $extension;

    public function __construct(Factory $factory, FilesystemManagerContract $filesystem, array $config = [])
    {
        $this->factory = $factory;
        $this->filesystem = $filesystem->disk($config['disk'] ?? null);
        $this->extension = $config['extension'] ?? 'md';
    }

    public function get(string $path): ?Sheet
    {
        if (!Str::endsWith($path, $this->extension)) {
            $path = "{$path}.{$this->extension}";
        }

        if (!$this->filesystem->exists($path)) {
            return null;
        }

        return $this->factory->make($path, $this->filesystem->get($path));
    }

    public function all(): Collection
    {
        return collect($this->filesystem->allFiles())
            ->filter(function (string $path) {
                return Str::endsWith($path, ".{$this->extension}");
            })
            ->map(function (string $path) {
                return $this->get($path);
            });
    }
}
