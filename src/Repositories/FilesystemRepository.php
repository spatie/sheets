<?php

namespace Spatie\Sheets\Repositories;

use Illuminate\Contracts\Filesystem\Factory as FilesystemManagerContract;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
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

    public function __construct(Factory $factory, FilesystemManagerContract $filesystem, array $config = [])
    {
        $this->factory = $factory;
        $this->filesystem = $filesystem->disk($config['disk'] ?? null);
        $this->extension = $config['extension'] ?? 'md';
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

    public function getBySlug(string $slug): ?Sheet
    {
        $parts = explode('/', $slug);

        $name = array_pop($parts);

        $pattern = sprintf(
            '/^%s%s%s$/',
            preg_quote(implode('/', $parts), '/'),
            !empty($parts) ? preg_quote('/', '/') : '',
            '([\w\-]+\.|)'.preg_quote($this->normalizePath($name))
        );

        return $this->get(
            collect($this->filesystem->allFiles())
                ->filter(function (string $path): bool {
                    return Str::endsWith($path, ".{$this->extension}");
                })
                ->filter(function (string $path) use ($pattern): bool {
                    return boolval(preg_match($pattern, $path));
                })
                ->first()
        );
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

    protected function normalizePath(string $path): string
    {
        return Str::finish($path, ".{$this->extension}");
    }
}
