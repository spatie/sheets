<?php

namespace Spatie\Sheets\Tests;

use ArrayAccess;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Spatie\Sheets\Sheet;

class SheetTest extends TestCase
{
    /** @test */
    public function it_can_create_a_sheet_with_attributes()
    {
        $attributes = [
            'foo' => 'bar',
            'hello' => 'world',
        ];
        $sheet = new Sheet($attributes);

        $reflection = (new ReflectionClass($sheet))->getProperty('attributes');
        $reflection->setAccessible(true);

        $this->assertEquals($attributes, $reflection->getValue($sheet));
    }

    /** @test */
    public function it_can_get_a_specific_attribute()
    {
        $sheet = new Sheet(['foo' => 'bar']);

        $this->assertEquals('bar', $sheet->foo);
    }

    /** @test */
    public function it_can_get_null_for_a_non_existing_attribute()
    {
        $sheet = new Sheet();

        $this->assertNull($sheet->unknown);
    }

    /** @test */
    public function it_can_set_a_specific_attribute()
    {
        $sheet = new Sheet();

        $sheet->foo = 'bar';

        $reflection = (new ReflectionClass($sheet))->getProperty('attributes');
        $reflection->setAccessible(true);

        $this->assertEquals(['foo' => 'bar'], $reflection->getValue($sheet));
    }

    /** @test */
    public function it_can_be_extended_with_accessor()
    {
        $child = new class extends Sheet {
            public function getFooAttribute($original)
            {
                return 'baz';
            }
        };

        $sheet = new $child(['foo' => 'bar']);

        $this->assertNotEquals('bar', $sheet->foo);
        $this->assertEquals('baz', $sheet->foo);
    }

    /** @test */
    public function it_can_be_transformed_to_array_with_accessor()
    {
        $child = new class extends Sheet {
            public function getFooAttribute($original)
            {
                return 'baz';
            }
        };

        $sheet = new $child([
            'foo' => 'bar',
            'lorem' => 'ipsum',
        ]);

        $this->assertEquals([
            'foo' => 'baz',
            'lorem' => 'ipsum',
        ], $sheet->toArray());
    }

    /** @test */
    public function it_implements_array_access()
    {
        $sheet = new Sheet(['foo' => 'bar']);

        $this->assertInstanceOf(ArrayAccess::class, $sheet);
        $this->assertEquals('bar', $sheet['foo']);
        $this->assertNull($sheet['unknown']);

        unset($sheet['foo']);
        $this->assertNull($sheet['foo']);

        $sheet['foo'] = 'baz';
        $this->assertEquals('baz', $sheet['foo']);
    }

    /** @test */
    public function it_can_be_transformed_to_an_array()
    {
        $sheet = new Sheet(['foo' => 'bar']);

        $this->assertInstanceOf(Arrayable::class, $sheet);
        $this->assertEquals(['foo' => 'bar'], $sheet->toArray());
    }

    /** @test */
    public function it_can_be_transformed_to_json()
    {
        $sheet = new Sheet(['foo' => 'bar']);

        $this->assertInstanceOf(Jsonable::class, $sheet);
        $this->assertEquals('{"foo":"bar"}', $sheet->toJson());
    }

    /** @test */
    public function it_is_json_serializable()
    {
        $sheet = new Sheet(['foo' => 'bar']);

        $this->assertInstanceOf(JsonSerializable::class, $sheet);
        $this->assertEquals('{"foo":"bar"}', json_encode($sheet));
    }

    /** @test */
    public function it_serialized_to_json_when_casted_to_a_string()
    {
        $sheet = new Sheet(['foo' => 'bar']);

        $this->assertInstanceOf(Jsonable::class, $sheet);
        $this->assertEquals('{"foo":"bar"}', (string) $sheet);
    }
}
