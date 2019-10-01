<?php

namespace Spatie\Sheets\Tests\Repositories;

use PHPUnit\Framework\TestCase;
use Spatie\Sheets\Repositories\FilesystemRepository;
use Spatie\Sheets\Sheet;
use Illuminate\Support\Collection;
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

        $this->assertInstanceOf(Sheet::class, $sheets['foo-bar.md']);
        $this->assertEquals('foo-bar', $sheets['foo-bar.md']->slug);
        $this->assertEquals('Foo bar', $sheets['foo-bar.md']->title);
        $this->assertEquals("<h1>Foo bar</h1>\n", $sheets['foo-bar.md']->contents);

        $this->assertInstanceOf(Sheet::class, $sheets['hello-world.md']);
        $this->assertEquals('hello-world', $sheets['hello-world.md']->slug);
        $this->assertEquals('Hello, world!', $sheets['hello-world.md']->title);
        $this->assertEquals("<h1>Hello, world!</h1>\n", $sheets['hello-world.md']->contents);
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
