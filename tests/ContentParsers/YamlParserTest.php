<?php

namespace Spatie\Sheets\Tests\ContentParsers;

use PHPUnit\Framework\TestCase;
use Spatie\Sheets\ContentParsers\YamlParser;

class YamlParserTest extends TestCase
{
    /** @test */
    public function it_converts_a_front_matter_document_to_attributes()
    {
        $yamlParser = new YamlParser();

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
