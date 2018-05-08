<?php

namespace Spatie\Sheets;

use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\Collection;
use RuntimeException;
use Spatie\Sheets\ContentParsers\MarkdownWithFrontMatterParser;
use Spatie\Sheets\PathParsers\SlugParser;
use Spatie\Sheets\Repositories\FilesystemRepository;
use Illuminate\Contracts\Filesystem\Filesystem;

class Sheets implements Repository
{
    /** @var \Spatie\Sheets\Repository[] */
    protected $collections;

    /** @var string|null */
    protected $defaultCollection;

    public function collection(string $name): Repository
    {
        if (! isset($this->collections[$name])) {
            throw new RuntimeException("Collection \"{$name}\" doesn't exist");
        }

        return $this->collections[$name];
    }

    public function registerCollection(
        string $name,
        PathParser $pathParser,
        ContentParser $contentParser,
        string $sheetClass,
        Filesystem $filesystem,
        string $extension
    ) {
        $factory = new Factory($pathParser, $contentParser, $sheetClass);

        $repository = new FilesystemRepository($factory, $filesystem, $extension);

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
            throw new RuntimeException("Can't set default collection \"{$defaultCollection}\" because it isn't registered.");
        }

        $this->defaultCollection = $defaultCollection;
    }

    protected function defaultCollection(): Repository
    {
        if (empty($this->collections)) {
            throw new RuntimeException("Can't retrieve a default collection if no collections are registered.");
        }

        return $this->collection(
            $this->defaultCollection ?? array_keys($this->collections)[0]
        );
    }
}
