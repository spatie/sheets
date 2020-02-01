<?php

namespace Spatie\Sheets\Tests\ContentParsers;

use Spatie\Sheets\Tests\TestCase;
use Spatie\Sheets\ContentParsers\MarkdownParser;

class MarkdownParserTest extends TestCase
{
    /** @test */
    public function it_converts_a_front_matter_document_to_attributes()
    {
        $markdownParser = $this->app->make(MarkdownParser::class);

        $contents = '# Hello, world!';

        $expected = [
            'contents' => "<h1>Hello, world!</h1>\n",
        ];

        $this->assertEquals($expected, $markdownParser->parse($contents));
    }
}
