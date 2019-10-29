<?php

namespace Spatie\Sheets\Tests\Concerns;

use Illuminate\Filesystem\FilesystemAdapter;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem as Flysystem;
use Illuminate\Contracts\Filesystem\Factory as FilesystemManagerContract;

trait UsesFilesystem
{
    protected function createFilesystem(): FilesystemManagerContract
    {
        $adapter = new Local(__DIR__.'/../fixtures/content');

        $flysystem = new Flysystem($adapter);

        $adapter = new FilesystemAdapter($flysystem);

        return new class($adapter) implements FilesystemManagerContract
        {
            private $adapter;

            public function __construct($adapter)
            {
                $this->adapter = $adapter;
            }

            public function disk($name = null)
            {
                return $this->adapter;
            }
        };
    }
}
