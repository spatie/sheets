<?php

namespace Spatie\Sheets\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Spatie\Sheets\Repository collection(string $name)
 * @method static void registerCollection(string $name, \Spatie\Sheets\Repository $repository)
 * @method static ?\Spatie\Sheets\Sheet get(string $path)
 * @method static \Illuminate\Support\Collection all()
 * @method static void setDefaultCollection(string $defaultCollection)
 * @method static \Illuminate\Support\Collection getRegisteredCollectionNames()
 *
 * @see \Spatie\Sheets\Sheets
 */
class Sheets extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'sheets';
    }
}
