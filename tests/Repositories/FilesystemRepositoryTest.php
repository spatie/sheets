<?php

namespace Spatie\Sheets\Tests\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;
use Spatie\Sheets\Repositories\FilesystemRepository;
use Spatie\Sheets\Sheet;
use Spatie\Sheets\Tests\Concerns\UsesFactory;
use Spatie\Sheets\Tests\Concerns\UsesFilesystem;
use Spatie\Sheets\Tests\TestCase;

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
    public function it_can_get_all_sheets_as_lazy_collection()
    {
        $filesystemRepository = new FilesystemRepository(
            $this->createFactory(),
            $this->createFilesystem()
        );

        $sheets = $filesystemRepository->cursor();

        $this->assertInstanceOf(LazyCollection::class, $sheets);
        $this->assertCount(2, $sheets);

        $this->assertInstanceOf(Sheet::class, $sheets->get(0));
        $this->assertEquals('foo-bar', $sheets->get(0)->slug);
        $this->assertEquals('Foo bar', $sheets->get(0)->title);
        $this->assertEquals("<h1>Foo bar</h1>\n", $sheets->get(0)->contents);

        $this->assertInstanceOf(Sheet::class, $sheets->get(1));
        $this->assertEquals('hello-world', $sheets->get(1)->slug);
        $this->assertEquals('Hello, world!', $sheets->get(1)->title);
        $this->assertEquals("<h1>Hello, world!</h1>\n", $sheets->get(1)->contents);
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

    /** @test */
    public function it_gets_the_same_sheet_instance()
    {
        $filesystemRepository = new FilesystemRepository(
            $this->createFactory(),
            $this->createFilesystem()
        );

        $sheet1 = $filesystemRepository->get('hello-world');
        $this->assertInstanceOf(Sheet::class, $sheet1);

        $sheet2 = $filesystemRepository->get('hello-world.md');
        $this->assertInstanceOf(Sheet::class, $sheet2);

        $this->assertSame($sheet1, $sheet2);
    }

    /** @test */
    public function it_can_forget_a_cached_sheet()
    {
        $filesystemRepository = new FilesystemRepository(
            $this->createFactory(),
            $this->createFilesystem()
        );

        $sheet1 = $filesystemRepository->get('hello-world');
        $this->assertInstanceOf(Sheet::class, $sheet1);

        $filesystemRepository->forget('hello-world');

        $sheet2 = $filesystemRepository->get('hello-world');
        $this->assertInstanceOf(Sheet::class, $sheet2);

        $this->assertNotSame($sheet1, $sheet2);
    }
}
