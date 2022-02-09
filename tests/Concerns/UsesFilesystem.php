<?php

namespace Spatie\Sheets\Tests\Concerns;

use Illuminate\Contracts\Filesystem\Factory as FilesystemManagerContract;
use Illuminate\Filesystem\FilesystemAdapter;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem as Flysystem;
use League\Flysystem\Local\LocalFilesystemAdapter;

trait UsesFilesystem
{
    protected function createFilesystem(): FilesystemManagerContract
    {
        if (class_exists(LocalFilesystemAdapter::class)) {
            $adapter = new LocalFilesystemAdapter(__DIR__.'/../fixtures/content');
        } else {
            $adapter = new Local(__DIR__.'/../fixtures/content');
        }

        $flysystem = new Flysystem($adapter);

        $adapter = new FilesystemAdapter($flysystem, $adapter);

        return new class($adapter) implements FilesystemManagerContract {
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
