<?php

namespace Spatie\Sheets\Tests\ContentParsers;

use League\CommonMark\CommonMarkConverter;
use Spatie\Sheets\Tests\TestCase;
use Spatie\Sheets\ContentParsers\MarkdownWithFrontMatterParser;

class MarkdownWithFrontMatterParserTest extends TestCase
{
    /** @test */
    public function it_converts_a_front_matter_document_to_attributes()
    {
        $markdownWithFrontMatterParser = $this->app->make(MarkdownWithFrontMatterParser::class);

        $contents = implode("\n", [
            '---',
            'title: Hello, world!',
            '---',
            '# Hello, world!',
        ]);

        $expected = [
            'title' => 'Hello, world!',
            'contents' => "<h1>Hello, world!</h1>\n",
        ];

        $this->assertEquals($expected, $markdownWithFrontMatterParser->parse($contents));
    }
}
