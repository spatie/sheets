<?php

namespace Spatie\Sheets\PathParsers;

use Spatie\Sheets\PathParser;

class SlugWithOrderParser implements PathParser
{
    public function parse(string $path): array
    {
        $parts = explode('/', $path);

        $filename = array_pop($parts);

        [$order, $slug] = explode('.', $filename);

        return [
            'order' => $order,
            'slug' => implode('/', array_merge($parts, [$slug])),
        ];
    }
}
