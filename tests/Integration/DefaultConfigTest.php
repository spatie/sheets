<?php

namespace Spatie\Sheets\Tests\Integration;

use Illuminate\Support\Collection;
use Spatie\Sheets\Sheets;
use Spatie\Sheets\Sheet;

class DefaultConfigTest extends TestCase
{
    /** @test */
    public function it_can_maintain_a_default_collection()
    {
        $content = $this->app->make(Sheets::class)->all();

        $this->assertInstanceOf(Collection::class, $content);
        $this->assertCount(2, $content);
        $this->assertContainsOnlyInstancesOf(Sheet::class, $content);
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
