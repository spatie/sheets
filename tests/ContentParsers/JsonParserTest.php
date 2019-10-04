<?php

namespace Spatie\Sheets\Tests\ContentParsers;

use PHPUnit\Framework\TestCase;
use Spatie\Sheets\ContentParsers\JsonParser;
use Spatie\Sheets\ContentParsers\MarkdownParser;
use League\CommonMark\CommonMarkConverter;
use Spatie\Sheets\ContentParsers\YamlParser;

class JsonParserTest extends TestCase
{
    /** @test */
    public function it_converts_a_front_matter_document_to_attributes()
    {
        $jsonParser = new JsonParser();

        $expected = [
            'title' => 'Hello, world!',
            'attribute' => [
                'data' => [
                    'foo',
                    'bar',
                ]
            ]
        ];

        $this->assertEquals($expected, $jsonParser->parse(json_encode($expected)));
    }
}
