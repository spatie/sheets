<?php

namespace Spatie\Sheets\Tests\PathParsers;

use Illuminate\Support\Carbon;
use PHPUnit\Framework\TestCase;
use Spatie\Sheets\PathParsers\SlugWithOrderParser;

class SlugWithOrderParserTest extends TestCase
{
    /** @test */
    public function it_extracts_an_order_and_slug_attribute_from_a_path()
    {
        $slugWithOrderParser = new SlugWithOrderParser();

        $expected = [
            'order' => 1,
            'slug' => 'hello-world',
        ];

        $this->assertEquals($expected, $slugWithOrderParser->parse('1.hello-world.md'));
    }

    /** @test */
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
