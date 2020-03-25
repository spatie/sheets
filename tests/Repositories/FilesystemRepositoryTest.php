<?php

namespace Spatie\Sheets\Tests\Repositories;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use League\CommonMark\CommonMarkConverter;
use Spatie\Sheets\ContentParsers\MarkdownWithFrontMatterParser;
use Spatie\Sheets\Factory;
use Spatie\Sheets\PathParsers\SlugWithDateParser;
use Spatie\Sheets\PathParsers\SlugWithOrderParser;
use Spatie\Sheets\Tests\TestCase;
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
    public function it_can_get_a_sheet_by_slug()
    {
        $filesystemRepository = new FilesystemRepository(
            $this->createFactory(),
            $this->createFilesystem()
        );

        $sheet = $filesystemRepository->getBySlug('hello-world');

        $this->assertInstanceOf(Sheet::class, $sheet);
        $this->assertEquals('hello-world', $sheet->slug);
        $this->assertEquals('Hello, world!', $sheet->title);
        $this->assertEquals("<h1>Hello, world!</h1>\n", $sheet->contents);
    }

    /** @test */
    public function it_can_get_a_sheet_with_date_by_slug()
    {
        $filesystemRepository = new FilesystemRepository(
            new Factory(
                new SlugWithDateParser(),
                new MarkdownWithFrontMatterParser(new CommonMarkConverter())
            ),
            $this->createFilesystem('posts')
        );

        $sheet = $filesystemRepository->getBySlug('my-first-post');

        $this->assertInstanceOf(Sheet::class, $sheet);
        $this->assertEquals('my-first-post', $sheet->slug);
        $this->assertEquals('My first post', $sheet->title);
        $this->assertEquals(Carbon::parse('1992-02-01'), $sheet->date);
    }

    /** @test */
    public function it_can_get_a_sheet_with_order_by_slug()
    {
        $filesystemRepository = new FilesystemRepository(
            new Factory(
                new SlugWithOrderParser(),
                new MarkdownWithFrontMatterParser(new CommonMarkConverter())
            ),
            $this->createFilesystem('pages')
        );

        $sheet = $filesystemRepository->getBySlug('my-first-page');

        $this->assertInstanceOf(Sheet::class, $sheet);
        $this->assertEquals('my-first-page', $sheet->slug);
        $this->assertEquals('My first page', $sheet->title);
        $this->assertEquals('01', $sheet->order);
    }

    /** @test */
    public function it_can_get_a_sheet_with_order_in_a_sub_directory_by_slug()
    {
        $filesystemRepository = new FilesystemRepository(
            new Factory(
                new SlugWithOrderParser(),
                new MarkdownWithFrontMatterParser(new CommonMarkConverter())
            ),
            $this->createFilesystem('pages')
        );

        $sheet = $filesystemRepository->getBySlug('sub/my-sub-page');

        $this->assertInstanceOf(Sheet::class, $sheet);
        $this->assertEquals('sub/my-sub-page', $sheet->slug);
        $this->assertEquals('My sub page', $sheet->title);
        $this->assertEquals('01', $sheet->order);
    }

    /** @test */
    public function it_can_get_a_sheet_with_order_in_a_multisub_directory_by_slug()
    {
        $filesystemRepository = new FilesystemRepository(
            new Factory(
                new SlugWithOrderParser(),
                new MarkdownWithFrontMatterParser(new CommonMarkConverter())
            ),
            $this->createFilesystem('pages')
        );

        $sheet = $filesystemRepository->getBySlug('sub/sub/my-multisub-page');

        $this->assertInstanceOf(Sheet::class, $sheet);
        $this->assertEquals('sub/sub/my-multisub-page', $sheet->slug);
        $this->assertEquals('My multisub page', $sheet->title);
        $this->assertEquals('01', $sheet->order);
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
