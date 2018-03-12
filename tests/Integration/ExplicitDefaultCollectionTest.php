<?php

namespace Spatie\Sheets\Tests\Integration;

use Illuminate\Support\Collection;
use Spatie\Sheets\PathParsers\SlugWithDateParser;
use Spatie\Sheets\Sheet;
use Spatie\Sheets\Sheets;
use Spatie\Sheets\Tests\Integration\DummySheets\Page;
use Spatie\Sheets\Tests\Integration\DummySheets\Post;

class ExplicitDefaultCollectionTest extends TestCase
{
    /** @test */
    public function it_accepts_an_explicit_default_collection()
    {
        $posts = $this->app->make(Sheets::class)->all();

        $this->assertCount(1, $posts);
        $this->assertContainsOnlyInstancesOf(Post::class, $posts);
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('filesystems.disks.content', [
            'driver' => 'local',
            'root' => __DIR__.'/../fixtures/content',
        ]);

        $app['config']->set('filesystems.disks.posts', [
            'driver' => 'local',
            'root' => __DIR__.'/../fixtures/posts',
        ]);

        $app['config']->set('sheets', [
            'default' => 'posts',

            'collections' => [
                'content' => [
                    'sheet_class' => Page::class,
                ],
                'posts' => [
                    'path_parser' => SlugWithDateParser::class,
                    'sheet_class' => Post::class,
                ],
            ],
        ]);
    }
}
