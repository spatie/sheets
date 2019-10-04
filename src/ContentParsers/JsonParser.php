<?php

namespace Spatie\Sheets\ContentParsers;

use Spatie\Sheets\ContentParser;
use Symfony\Component\Yaml\Yaml;

class JsonParser implements ContentParser
{
    public function parse(string $contents): array
    {
        return json_decode($contents, true);
    }
}
