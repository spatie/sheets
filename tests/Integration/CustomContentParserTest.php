<?php

namespace Spatie\Sheets\Tests\Integration;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Spatie\Sheets\ContentParsers\MarkdownParser;
use Spatie\Sheets\PathParsers\SlugWithDateParser;
use Spatie\Sheets\Sheet;
use Spatie\Sheets\Sheets;

class CustomContentParserTest extends TestCase
{
    /** @test */
    public function it_can_maintain_a_collection_with_a_custom_content_parser()
    {
        $documents = $this->app->make(Sheets::class)->all();

        $this->assertInstanceOf(Collection::class, $documents);
        $this->assertCount(1, $documents);
        $this->assertContainsOnlyInstancesOf(Sheet::class, $documents);
        $this->assertEquals("<h1>Hello, world!</h1>\n", $documents['hello-world.md']->contents);
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('filesystems.disks.plain-markdown', [
            'driver' => 'local',
            'root' => __DIR__.'/../fixtures/plain-markdown',
        ]);

        $app['config']->set('sheets', [
            'collections' => [
                'plain-markdown' => [
                    'content_parser' => MarkdownParser::class,
                ],
            ],
        ]);
    }
}
