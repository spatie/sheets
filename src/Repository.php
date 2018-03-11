<?php

namespace Spatie\Sheets;

use Illuminate\Support\Collection;

interface Repository
{
    public function get(string $path): ?Sheet;

    public function all(): Collection;
}
