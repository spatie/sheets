<?php

namespace Spatie\Sheets\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Spatie\Sheets\SheetsServiceProvider;

abstract class TestCase extends OrchestraTestCase
{
    public function getPackageProviders($app)
    {
        return [
            SheetsServiceProvider::class,
        ];
    }
}
