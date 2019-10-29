<?php

namespace Spatie\Sheets\Tests\Integration;

use Illuminate\Support\Collection;
use Spatie\Sheets\Sheet;
use Spatie\Sheets\Sheets;

class CustomDiskTest extends TestCase
{
    /** @test */
    public function it_can_maintain_a_collection_on_a_specific_disk()
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
            'collections' => [
                'posts' => [
                    'disk' => 'content',
                ],
            ],
        ]);
    }
}
