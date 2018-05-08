<?php

namespace Spatie\Sheets\PathParsers;

use Spatie\Sheets\PathParser;

class SlugWithOrderParser implements PathParser
{
    public function parse(string $path): array
    {
        $parts = explode('.', $path);

        return [
            'order' => (int) $parts[0],
            'slug' => $parts[1] ?? '',
        ];
    }
}
