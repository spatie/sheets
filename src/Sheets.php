<?php

namespace Spatie\Sheets;

use RuntimeException;
use Spatie\Sheets\ContentParsers\FrontMatterWithMarkdownParser;
use Spatie\Sheets\PathParsers\SlugParser;
use Spatie\Sheets\Repositories\FilesystemRepository;
use Illuminate\Filesystem\FilesystemManager;

class Sheets
{
    /** @var \Spatie\Sheets\Repository[] */
    protected $collections;

    public function __construct(array $collections)
    {
        foreach ($collections as $name => $options) {
            if (is_string($options)) {
                $this->registerCollection($options, []);
            } else {
                $this->registerCollection($name, $options);
            }
        }
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

        $factory = new Factory($pathParser, $contentParser);

        $repository = new FilesystemRepository(
            $factory,
            app('filesystem')->disk($options['disk'] ?? $name)
        );

        $this->collections[$name] = $repository;
    }
}
