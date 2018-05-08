<?php

namespace Spatie\Sheets\PathParsers;

use Illuminate\Support\Carbon;
use Spatie\Sheets\PathParser;

class SlugWithDateParser implements PathParser
{
    public function parse(string $path): array
    {
        [$date, $slug] = explode('.', $path);

        return [
            'date' => Carbon::parse($date),
            'slug' => $slug ?? '',
        ];
    }
}
