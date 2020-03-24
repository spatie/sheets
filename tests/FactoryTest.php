<?php

namespace Spatie\Sheets\Tests;

use League\CommonMark\CommonMarkConverter;
use Spatie\Sheets\Tests\TestCase;
use Spatie\Sheets\ContentParsers\MarkdownWithFrontMatterParser;
use Spatie\Sheets\Factory;
use Spatie\Sheets\PathParsers\SlugParser;

class FactoryTest extends TestCase
{
    /** @test */
    public function it_extracts_a_slug_attribute_from_a_path()
    {
        $factory = new Factory(
            new SlugParser(),
            new MarkdownWithFrontMatterParser(new CommonMarkConverter())
        );

        $path = 'hello-world.md';

        $contents = implode("\n", [
            '---',
            'title: Hello, world!',
            '---',
            '# Hello, world!',
        ]);

        $sheet = $factory->make($path, $contents);

        $this->assertEquals('hello-world', $sheet->slug);
        $this->assertEquals('Hello, world!', $sheet->title);
        $this->assertEquals("<h1>Hello, world!</h1>\n", $sheet->contents);
        $this->assertEquals($path, $sheet->getPath());
    }
}
