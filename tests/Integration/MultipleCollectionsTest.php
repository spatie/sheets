<?php

namespace Spatie\Sheets\Tests\Integration;

use Illuminate\Support\Collection;
use Spatie\Sheets\PathParsers\SlugWithDateParser;
use Spatie\Sheets\Sheets;
use Spatie\Sheets\Tests\Integration\DummySheets\Page;
use Spatie\Sheets\Tests\Integration\DummySheets\Post;

class MultipleCollectionsTest extends TestCase
{
    /** @test */
    public function it_can_maintain_multiple_collections()
    {
        $content = $this->app->make(Sheets::class)->collection('content')->all();

        $this->assertInstanceOf(Collection::class, $content);
        $this->assertCount(2, $content);
        $this->assertContainsOnlyInstancesOf(Page::class, $content);

        $posts = $this->app->make(Sheets::class)->collection('posts')->all();

        $this->assertInstanceOf(Collection::class, $posts);
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
