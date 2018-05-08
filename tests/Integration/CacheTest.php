<?php

namespace Spatie\Sheets\Tests\Integration;

use Spatie\Sheets\Sheets;

class CacheTest extends TestCase
{
    /** @test */
    public function it_caches_all_results()
    {
        $this->assertFalse($this->app->cache->has('sheets:content:__all'));

        $sheets = $this->app->make(Sheets::class)->all();

        $this->assertEquals($sheets, $this->app->cache->get('sheets:content:__all'));
    }

    /** @test */
    public function it_caches_get_results()
    {
        $this->assertFalse($this->app->cache->has('sheets:content:hello-world'));

        $sheet = $this->app->make(Sheets::class)->get('hello-world');

        $this->assertEquals($sheet, $this->app->cache->get('sheets:content:hello-world'));
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('filesystems.disks.content', [
            'driver' => 'local',
            'root' => __DIR__.'/../fixtures/content',
        ]);

        $app['config']->set('sheets', [
            'collections' => ['content'],
        ]);
    }
}
