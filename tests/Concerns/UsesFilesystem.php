<?php

namespace Spatie\Sheets\Tests\Concerns;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemAdapter;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem as Flysystem;

trait UsesFilesystem
{
    protected function createFilesystem(): Filesystem
    {
        $adapter = new Local(__DIR__.'/../fixtures/content');

        $flysystem = new Flysystem($adapter);

        return new FilesystemAdapter($flysystem);
    }
}
