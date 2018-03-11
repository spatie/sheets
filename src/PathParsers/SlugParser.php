<?php

namespace Spatie\Sheets\PathParsers;

use Spatie\Sheets\PathParser;

class SlugParser implements PathParser
{
    public function parse(string $path): array
    {
        return ['slug' => explode('.', $path)[0]];
    }
}
