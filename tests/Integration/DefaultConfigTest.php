<?php

namespace Spatie\Sheets\Tests\Integration;

use Illuminate\Support\Collection;
use Spatie\Sheets\Sheet;
use Spatie\Sheets\Sheets;

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

    /** @test */
    public function it_can_retrieve_a_default_collection_via_helper()
    {
        $content = sheets()->all();

        $this->assertInstanceOf(Collection::class, $content);
        $this->assertCount(2, $content);
        $this->assertContainsOnlyInstancesOf(Sheet::class, $content);
    }

    /** @test */
    public function it_can_retrieve_an_explicit_collection_via_helper()
    {
        $content = sheets('content')->all();

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
