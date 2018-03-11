<?php

namespace Spatie\Sheets\Tests\Integration;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Spatie\Sheets\SheetsServiceProvider;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [SheetsServiceProvider::class];
    }
}
