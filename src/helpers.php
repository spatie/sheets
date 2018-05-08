<?php

use Spatie\Sheets\Sheets;

if (! function_exists('sheets')) {
    function sheets(): Sheets
    {
        return app(Sheets::class);
    }
}
