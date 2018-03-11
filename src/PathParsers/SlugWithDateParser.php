<?php

namespace Spatie\Sheets\PathParsers;

use Illuminate\Support\Carbon;
use Spatie\Sheets\PathParser;

class SlugWithDateParser implements PathParser
{
    public function parse(string $path): array
    {
        $parts = explode('.', $path);

        return [
            'date' => Carbon::parse($parts[0]),
            'slug' => $parts[1] ?? '',
        ];
    }
}
