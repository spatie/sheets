<?php

namespace Spatie\Sheets\Tests\Integration;

use Illuminate\Support\Collection;
use Spatie\Sheets\PathParsers\SlugWithDateParser;
use Spatie\Sheets\Sheet;
use Spatie\Sheets\Sheets;
use Spatie\Sheets\Tests\Integration\DummySheets\Page;
use Spatie\Sheets\Tests\Integration\DummySheets\Post;

class ImplicitDefaultCollectionTest extends TestCase
{
    /** @test */
    public function it_defaults_to_the_first_collection_if_no_default_is_provided()
    {
        $content = $this->app->make(Sheets::class)->all();

        $this->assertCount(2, $content);
        $this->assertContainsOnlyInstancesOf(Page::class, $content);
    }

    /** @test */
    public function it_throws_exception_on_non_existed_collections()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("Collection \"invalid_collection\" doesn't exist");

        $content = $this->app->make(Sheets::class)->collection('invalid_collection');
    }

    /** @test */
    public function it_can_get_null_on_the_default_collection()
    {
        $this->assertNull($this->app->make(Sheets::class)->get('non_existed_collections'));
    }

    /** @test */
    public function it_throws_excpetion_on_set_default_collection()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("Can't set default collection \"no_registered_collections\" because it isn't registered.");

        $this->app->make(Sheets::class)->setDefaultCollection('no_registered_collections');
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
