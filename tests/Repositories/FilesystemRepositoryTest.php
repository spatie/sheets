<?php

namespace Spatie\Sheets\Tests\Repositories;

use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;
use Spatie\Sheets\Repositories\FilesystemRepository;
use Spatie\Sheets\Sheet;
use Spatie\Sheets\Tests\Concerns\UsesFactory;
use Spatie\Sheets\Tests\Concerns\UsesFilesystem;

class FilesystemRepositoryTest extends TestCase
{
    use UsesFactory;
    use UsesFilesystem;

    /** @test */
    public function it_can_get_a_sheet()
    {
        $filesystemRepository = new FilesystemRepository(
            $this->createFactory(),
            $this->createFilesystem()
        );

        $sheet = $filesystemRepository->get('hello-world');

        $this->assertInstanceOf(Sheet::class, $sheet);
        $this->assertEquals('hello-world', $sheet->slug);
        $this->assertEquals('Hello, world!', $sheet->title);
        $this->assertEquals("<h1>Hello, world!</h1>\n", $sheet->contents);
    }

    /** @test */
    public function it_can_get_all_sheets()
    {
        $filesystemRepository = new FilesystemRepository(
            $this->createFactory(),
            $this->createFilesystem()
        );

        $sheets = $filesystemRepository->all();

        $this->assertInstanceOf(Collection::class, $sheets);
        $this->assertCount(2, $sheets);

        $this->assertInstanceOf(Sheet::class, $sheets[0]);
        $this->assertEquals('foo-bar', $sheets[0]->slug);
        $this->assertEquals('Foo bar', $sheets[0]->title);
        $this->assertEquals("<h1>Foo bar</h1>\n", $sheets[0]->contents);

        $this->assertInstanceOf(Sheet::class, $sheets[1]);
        $this->assertEquals('hello-world', $sheets[1]->slug);
        $this->assertEquals('Hello, world!', $sheets[1]->title);
        $this->assertEquals("<h1>Hello, world!</h1>\n", $sheets[1]->contents);
    }

    /** @test */
    public function it_can_get_null_on_non_existed_path()
    {
        $filesystemRepository = new FilesystemRepository(
            $this->createFactory(),
            $this->createFilesystem()
        );

        $this->assertNull($filesystemRepository->get('invalid_path'));
    }
}
