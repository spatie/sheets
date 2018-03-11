<?php

namespace Spatie\Sheets\Tests\PathParsers;

use PHPUnit\Framework\TestCase;
use Spatie\Sheets\PathParsers\SlugParser;

class SlugParserTest extends TestCase
{
    /** @test */
    public function it_extracts_a_slug_attribute_from_a_path()
    {
        $slugParser = new SlugParser();

        $this->assertEquals(['slug' => 'hello-world'], $slugParser->parse('hello-world.md'));
    }
}
