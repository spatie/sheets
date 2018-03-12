<?php

namespace Spatie\Sheets\Tests\Integration;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Spatie\Sheets\PathParsers\SlugWithDateParser;
use Spatie\Sheets\Sheet;
use Spatie\Sheets\Sheets;

class CustomPathParserTest extends TestCase
{
    /** @test */
    public function it_can_maintain_a_collection_with_a_custom_path_parser()
    {
        $posts = $this->app->make(Sheets::class)->all();

        $this->assertInstanceOf(Collection::class, $posts);
        $this->assertCount(1, $posts);
        $this->assertContainsOnlyInstancesOf(Sheet::class, $posts);
        $this->assertEquals('My first post', $posts[0]->title);
        $this->assertEquals(Carbon::parse('1992-02-01'), $posts[0]->date);
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('filesystems.disks.posts', [
            'driver' => 'local',
            'root' => __DIR__.'/../fixtures/posts',
        ]);

        $app['config']->set('sheets', [
            'collections' => [
                'posts' => [
                    'path_parser' => SlugWithDateParser::class,
                ],
            ],
        ]);
    }
}
