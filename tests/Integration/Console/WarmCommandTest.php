<?php

namespace Spatie\Sheets\Tests\Integration\Console;

use Spatie\Sheets\Sheets;
use Spatie\Sheets\Tests\Integration\TestCase;
use Illuminate\Support\Facades\Artisan;

class WarmCommandTest extends TestCase
{
    /** @test */
    public function it_warms_up_all_caches()
    {
        $this->assertFalse($this->app->cache->has('sheets:content-1:__all'));
        $this->assertFalse($this->app->cache->has('sheets:content-1:hello-world'));
        $this->assertFalse($this->app->cache->has('sheets:content-2:__all'));
        $this->assertFalse($this->app->cache->has('sheets:content-2:hello-world'));

        Artisan::call('sheets:warm');

        $this->assertTrue($this->app->cache->has('sheets:content-1:__all'));
        $this->assertTrue($this->app->cache->has('sheets:content-1:hello-world'));
        $this->assertTrue($this->app->cache->has('sheets:content-2:__all'));
        $this->assertTrue($this->app->cache->has('sheets:content-2:hello-world'));
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('filesystems.disks.content-1', [
            'driver' => 'local',
            'root' => __DIR__.'/../../fixtures/content',
        ]);

        $app['config']->set('filesystems.disks.content-2', [
            'driver' => 'local',
            'root' => __DIR__.'/../../fixtures/content',
        ]);

        $app['config']->set('sheets', [
            'collections' => ['content-1', 'content-2'],
        ]);
    }
}
