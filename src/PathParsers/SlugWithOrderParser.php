<?php

namespace Spatie\Sheets\PathParsers;

use Spatie\Sheets\PathParser;

class SlugWithOrderParser implements PathParser
{
    public function parse(string $path): array
    {
        [$order, $slug] = explode('.', $path);

        return [
            'order' => (int) $order,
            'slug' => $slug ?? '',
        ];
    }
}
