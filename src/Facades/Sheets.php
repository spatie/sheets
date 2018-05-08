<?php

namespace Spatie\Sheets\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Spatie\Sheets\Sheets
 */
class Sheets extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'sheets';
    }
}
