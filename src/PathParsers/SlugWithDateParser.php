<?php

namespace Spatie\Sheets\PathParsers;

use Illuminate\Support\Carbon;
use Spatie\Sheets\PathParser;

class SlugWithDateParser implements PathParser
{
    public function parse(string $path): array
    {
        $parts = explode('/', $path);

        $filename = array_pop($parts);

        [$date, $slug] = explode('.', $filename);

        return [
            'date' => Carbon::parse($date),
            'slug' => implode('/', array_merge($parts, [$slug])),
        ];
    }
}
