<?php

namespace Spatie\Sheets\Tests\Integration;

use Spatie\Sheets\Sheets;

class CacheFalseTest extends TestCase
{
    /** @test */
    public function it_doesnt_cache_when_cache_is_false()
    {
        $this->assertFalse($this->app->cache->has('sheets:content:__all'));

        $sheets = $this->app->make(Sheets::class)->all();

        $this->assertFalse($this->app->cache->has('sheets:content:__all'));
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('filesystems.disks.content', [
            'driver' => 'local',
            'root' => __DIR__.'/../fixtures/content',
        ]);

        $app['config']->set('sheets', [
            'collections' => [
                'content' => [
                    'cache' => false,
                ],
            ],
        ]);
    }
}
