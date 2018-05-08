<?php

use Spatie\Sheets\Sheets;

if (! function_exists('sheets')) {
    /**
     * @return \Spatie\Sheets\Sheets
     */
    function sheets(): Sheets
    {
        return app(Sheets::class);
    }
}
