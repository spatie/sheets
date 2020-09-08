<?php

namespace Spatie\Sheets\Tests\ContentParsers;

use Spatie\Sheets\ContentParsers\YamlParser;
use Spatie\Sheets\Tests\TestCase;

class YamlParserTest extends TestCase
{
    /** @test */
    public function it_converts_a_front_matter_document_to_attributes()
    {
        $yamlParser = $this->app->make(YamlParser::class);

        $contents = implode("\n", [
            'title: Hello, world!',
            'attribute:',
            '  data:',
            '    - foo',
            '    - bar',
        ]);

        $expected = [
            'title' => 'Hello, world!',
            'attribute' => [
                'data' => [
                    'foo',
                    'bar',
                ],
            ],
        ];

        $this->assertEquals($expected, $yamlParser->parse($contents));
    }
}
