<?php

namespace Spatie\Sheets;

interface PathParser
{
    public function parse(string $path): array;
}
