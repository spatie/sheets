<?php

namespace Spatie\Sheets\Tests\ContentParsers;

use Spatie\Sheets\Tests\TestCase;
use Spatie\Sheets\ContentParsers\JsonParser;

class JsonParserTest extends TestCase
{
    /** @test */
    public function it_converts_a_front_matter_document_to_attributes()
    {
        $jsonParser = $this->app->make(JsonParser::class);

        $expected = [
            'title' => 'Hello, world!',
            'attribute' => [
                'data' => [
                    'foo',
                    'bar',
                ],
            ],
        ];

        $this->assertEquals($expected, $jsonParser->parse(json_encode($expected)));
    }
}
