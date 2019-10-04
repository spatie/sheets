<?php

namespace Spatie\Sheets\ContentParsers;

use Spatie\Sheets\ContentParser;
use Symfony\Component\Yaml\Yaml;

class YamlParser implements ContentParser
{
    public function parse(string $contents): array
    {
        return Yaml::parse($contents);
    }
}
