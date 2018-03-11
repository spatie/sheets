<?php

namespace Spatie\Sheets;

use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\Collection;
use RuntimeException;
use Spatie\Sheets\ContentParsers\FrontMatterWithMarkdownParser;
use Spatie\Sheets\PathParsers\SlugParser;
use Spatie\Sheets\Repositories\FilesystemRepository;

class Sheets implements Repository
{
    /** @var \Spatie\Sheets\Repository[] */
    protected $collections;

    /** @var string|null */
    protected $default;

    public function __construct(array $collections, string $default = null)
    {
        foreach ($collections as $name => $options) {
            if (is_string($options)) {
                $this->registerCollection($options, []);
            } else {
                $this->registerCollection($name, $options);
            }
        }

        $this->default = $default;
    }

    public function collection(string $name): Repository
    {
        if (! isset($this->collections[$name])) {
            throw new RuntimeException("Collection \"{$name}\" doesn't exist");
        }

        return $this->collections[$name];
    }

    public function registerCollection(string $name, array $options)
    {
        $pathParser = app(
            $options['path_parser'] ?? SlugParser::class
        );

        $contentParser = app(
            $options['content_parser'] ?? FrontMatterWithMarkdownParser::class
        );

        $sheetClass = $options['sheet_class'] ?? Sheet::class;

        $factory = new Factory($pathParser, $contentParser, $sheetClass);

        $filesystem = app('filesystem')->disk($options['disk'] ?? $name);

        $repository = new FilesystemRepository($factory, $filesystem);

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

    protected function defaultCollection(): Repository
    {
        if (empty($this->collections)) {
            throw new RuntimeException("Can't retrieve a default collection if no collections are registered.");
        }

        return $this->collection(
            $this->default ?? array_keys($this->collections)[0]
        );
    }
}
