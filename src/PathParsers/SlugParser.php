<?php

namespace Spatie\Sheets\PathParsers;

use Spatie\Sheets\PathParser;

class SlugParser implements PathParser
{
    public function parse(string $path): array
    {
        return ['slug' => pathinfo($path, PATHINFO_FILENAME)];
    }
}
