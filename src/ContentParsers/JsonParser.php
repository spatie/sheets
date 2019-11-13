<?php

namespace Spatie\Sheets\ContentParsers;

use Spatie\Sheets\ContentParser;

class JsonParser implements ContentParser
{
    public function parse(string $contents): array
    {
        return json_decode($contents, true);
    }
}
