<?php

namespace Spatie\Sheets\Tests\Concerns;

use League\CommonMark\CommonMarkConverter;
use Spatie\Sheets\ContentParsers\MarkdownWithFrontMatterParser;
use Spatie\Sheets\Factory;
use Spatie\Sheets\PathParsers\SlugParser;

trait UsesFactory
{
    protected function createFactory(): Factory
    {
        return new Factory(
            new SlugParser(),
            new MarkdownWithFrontMatterParser(new CommonMarkConverter())
        );
    }
}
