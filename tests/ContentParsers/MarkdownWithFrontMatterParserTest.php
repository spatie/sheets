<?php

namespace Spatie\Sheets\Tests\ContentParsers;

use League\CommonMark\CommonMarkConverter;
use PHPUnit\Framework\TestCase;
use Spatie\Sheets\ContentParsers\MarkdownWithFrontMatterParser;

class MarkdownWithFrontMatterParserTest extends TestCase
{
    /** @test */
    public function it_converts_a_front_matter_document_to_attributes()
    {
        $markdownWithFrontMatterParser = new MarkdownWithFrontMatterParser(
            new CommonMarkConverter()
        );

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
