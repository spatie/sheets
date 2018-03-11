<?php

namespace Spatie\Sheets\Tests\Concerns;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemAdapter;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem as Flysystem;

trait NeedsFilesystem
{
    protected function createFilesystem(string $root): Filesystem
    {
        $adapter = new Local($root);

        $flysystem = new Flysystem($adapter);

        return new FilesystemAdapter($flysystem);
    }
}
