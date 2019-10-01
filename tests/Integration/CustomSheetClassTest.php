<?php

namespace Spatie\Sheets\Tests\Integration;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Spatie\Sheets\PathParsers\SlugWithDateParser;
use Spatie\Sheets\Sheet;
use Spatie\Sheets\Sheets;
use Spatie\Sheets\Tests\Integration\DummySheets\Post;

class CustomSheetClassTest extends TestCase
{
    /** @test */
    public function it_can_maintain_a_collection_with_a_custom_sheet_class()
    {
        $posts = $this->app->make(Sheets::class)->all();

        $this->assertInstanceOf(Collection::class, $posts);
        $this->assertCount(1, $posts);
        $this->assertContainsOnlyInstancesOf(Post::class, $posts);
        $this->assertEquals('My first post', $posts['1992-02-01.my-first-post.md']->title);
        $this->assertEquals(Carbon::parse('1992-02-01'), $posts['1992-02-01.my-first-post.md']->date);
        $this->assertEquals('February 1st, 1992', $posts['1992-02-01.my-first-post.md']->formatted_date);
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
                    'sheet_class' => Post::class,
                ],
            ],
        ]);
    }
}
