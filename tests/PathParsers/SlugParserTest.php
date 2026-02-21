<?php

namespace Spatie\Sheets\Tests\PathParsers;

use Spatie\Sheets\PathParsers\SlugParser;
use Spatie\Sheets\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class SlugParserTest extends TestCase
{
    #[Test]
    public function it_extracts_a_slug_attribute_from_a_path()
    {
        $slugParser = new SlugParser();

        $this->assertEquals(['slug' => 'hello-world'], $slugParser->parse('hello-world.md'));
    }

    #[Test]
    public function it_extracts_a_slug_attribute_from_a_nested_path()
    {
        $slugParser = new SlugParser();

        $this->assertEquals(['slug' => 'pages/hello-world'], $slugParser->parse('pages/hello-world.md'));
    }
}
