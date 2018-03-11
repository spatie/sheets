<?php

namespace Spatie\Sheets\Tests\PathParsers;

use Illuminate\Support\Carbon;
use PHPUnit\Framework\TestCase;
use Spatie\Sheets\PathParsers\SlugWithDateParser;

class SlugWithDateParserTest extends TestCase
{
    /** @test */
    public function it_extracts_a_date_and_slug_attribute_from_a_path()
    {
        $slugWithDateParser = new SlugWithDateParser();

        $expected = [
            'date' => Carbon::parse('1992-02-01'),
            'slug' => 'hello-world',
        ];

        $this->assertEquals($expected, $slugWithDateParser->parse('1992-02-01.hello-world.md'));
    }
}
