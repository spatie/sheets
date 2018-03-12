<?php

namespace Spatie\Sheets\Tests\Integration\DummySheets;

use Spatie\Sheets\Sheet;

class Post extends Sheet
{
    public function getFormattedDateAttribute(): string
    {
        return $this->date->format('F jS, Y');
    }
}
