<?php

namespace Spatie\Sheets\Tests\Concerns;

use League\CommonMark\CommonMarkConverter;
use Spatie\Sheets\ContentParsers\FrontMatterWithMarkdownParser;
use Spatie\Sheets\Factory;
use Spatie\Sheets\PathParsers\SlugParser;

trait UsesFactory
{
    protected function createFactory(): Factory
    {
        return new Factory(
            new SlugParser(),
            new FrontMatterWithMarkdownParser(new CommonMarkConverter())
        );
    }
}
