<?php

namespace Spatie\Sheets\Tests\PathParsers;

use Spatie\Sheets\PathParsers\SlugWithOrderParser;
use Spatie\Sheets\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class SlugWithOrderParserTest extends TestCase
{
    #[Test]
    public function it_extracts_an_order_and_slug_attribute_from_a_path()
    {
        $slugWithOrderParser = new SlugWithOrderParser();

        $expected = [
            'order' => 1,
            'slug' => 'hello-world',
        ];

        $this->assertEquals($expected, $slugWithOrderParser->parse('1.hello-world.md'));
    }

    #[Test]
    public function it_extracts_an_order_and_slug_attribute_from_a_nested_path()
    {
        $slugWithOrderParser = new SlugWithOrderParser();

        $expected = [
            'order' => 1,
            'slug' => 'pages/hello-world',
        ];

        $this->assertEquals($expected, $slugWithOrderParser->parse('pages/1.hello-world.md'));
    }
}
