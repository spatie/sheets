<?php

namespace Spatie\Sheets\Tests\Integration;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Spatie\Sheets\ContentParsers\MarkdownParser;
use Spatie\Sheets\PathParsers\SlugWithDateParser;
use Spatie\Sheets\Repository;
use Spatie\Sheets\Sheet;
use Spatie\Sheets\Sheets;

class CustomRepositoryTest extends TestCase
{
    /** @test */
    public function it_can_maintain_a_collection_with_a_custom_repository()
    {
        $documents = $this->app->make(Sheets::class)->all();

        $this->assertInstanceOf(Collection::class, $documents);
        $this->assertCount(2, $documents);
        $this->assertContainsOnlyInstancesOf(Sheet::class, $documents);

        $this->assertEquals('foo', $documents[0]->path);
        $this->assertEquals('bar', $documents[0]->foo);

        $this->assertEquals('bar', $documents[1]->path);
        $this->assertEquals('bar', $documents[1]->foo);
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('sheets', [
            'collections' => [
                'null' => [
                    'repository' => StaticRepository::class,
                ],
            ],
        ]);
    }
}

final class StaticRepository implements Repository
{
    public function all(): Collection
    {
        return new Collection([
            $this->get('foo'),
            $this->get('bar'),
        ]);
    }

    public function get(string $path): ?Sheet
    {
        return new Sheet([
            'path' => $path,
            'foo' => 'bar',
        ]);
    }
}