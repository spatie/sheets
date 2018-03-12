<?php

namespace Spatie\Sheets\Tests\ContentParsers;

use PHPUnit\Framework\TestCase;
use Spatie\Sheets\ContentParsers\MarkdownParser;
use League\CommonMark\CommonMarkConverter;

class MarkdownParserTest extends TestCase
{
    /** @test */
    public function it_converts_a_front_matter_document_to_attributes()
    {
        $markdownParser = new MarkdownParser(
            new CommonMarkConverter()
        );

        $contents = '# Hello, world!';

        $expected = [
            'contents' => "<h1>Hello, world!</h1>\n",
        ];

        $this->assertEquals($expected, $markdownParser->parse($contents));
    }
}
