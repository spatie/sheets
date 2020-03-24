<?php

namespace Spatie\Sheets;

class Factory
{
    /** @var \Spatie\Sheets\PathParser */
    protected $pathParser;

    /** @var \Spatie\Sheets\ContentParser */
    protected $contentParser;

    /** @var string */
    protected $sheetClass;

    public function __construct(
        PathParser $pathParser,
        ContentParser $contentParser,
        string $sheetClass = Sheet::class
    ) {
        $this->pathParser = $pathParser;
        $this->contentParser = $contentParser;
        $this->sheetClass = $sheetClass;
    }

    public function make(string $path, string $contents): Sheet
    {
        $attributes = array_merge(
            $this->pathParser->parse($path),
            $this->contentParser->parse($contents)
        );

        $sheetClass = $this->sheetClass;

        return new $sheetClass($attributes, $path);
    }
}
