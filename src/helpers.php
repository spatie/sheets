<?php

use Spatie\Sheets\Repository;
use Spatie\Sheets\Sheets;

if (! function_exists('sheets')) {
    /**
     * @param string|null $collection
     *
     * @return \Spatie\Sheets\Repository|\Spatie\Sheets\Sheets
     */
    function sheets(?string $collection = null): Repository
    {
        if ($collection === null) {
            return app(Sheets::class);
        }

        return app(Sheets::class)->collection($collection);
    }
}
