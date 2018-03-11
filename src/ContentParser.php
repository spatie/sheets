<?php

namespace Spatie\Sheets;

interface ContentParser
{
    public function parse(string $contents): array;
}
