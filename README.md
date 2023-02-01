# Store & retrieve your static content in plain text files

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/sheets.svg?style=flat-square)](https://packagist.org/packages/spatie/sheets)
![GitHub Workflow Status](https://img.shields.io/github/workflow/status/spatie/sheets/run-tests?label=tests)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/sheets.svg?style=flat-square)](https://packagist.org/packages/spatie/sheets)

Sheets is a Laravel package to store, retrieve & index content stored as text files. Markdown & front matter are supported out of the box, but you can parse & extract data from your files in whatever format you prefer.

Sheets can be added to any existing Laravel application and is a perfect fit for documentation sites & personal blogs.

```md
---
title: Home
---
# Hello, world!

Welcome to Sheets!
```

```php
class SheetController
{
    public function index(Sheets $sheets)
    {
        return view('sheet', [
            'sheet' => $sheets->get('home'),
        ]);
    }

    public function show(string $id, Sheets $sheets)
    {
        return view('sheet', [
            'sheet' => $sheets->get($id),
        ]);
    }
}
```

```blade
@extends('layouts.app', [
    'title' => $sheet->title,
])

@section('main')
    {{ $sheet->contents }}
@endsection
```

## Features

- Allows any document format (by default Markdown files with front matter)
- Stores your content wherever you want (uses Laravel's filesystem component)
- Keeps multiple collections of content (e.g. posts, pages, etc.)
- Casts your document contents to Eloquent-like classes with accessors
- Convention over configuration, near-zero setup if you use the defaults

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/sheets.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/sheets)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

You can install the package via composer:

```bash
composer require spatie/sheets
```

Laravel will auto-discover and register the `SheetsServiceProvider`, so no further setup is required.

After installing, you must publish the `sheets.php` configuration file:

```
php artisan vendor:publish --provider="Spatie\Sheets\SheetsServiceProvider" --tag="config"
```

Finally you must [create your first collection](https://github.com/spatie/sheets#configuring-collections).

## Usage

The `Sheets` instance is available through a facade, helper function, or dependency injection.

```php
use Sheets;

Sheets::all();
```

```php
sheets()->all();
```

```php
use Spatie\Sheets\Sheets;

class SheetsController
{
    public function index(Sheets $sheets)
    {
        return view('sheets', [
            'sheets' => $sheets->all(),
        ]);
    }
}
```

### Creating your first collection

A collection maps to a folder in your filesystem of choice. Sheets will look for a disk configured in `config/filesystems.php` with the same name as the collection—or you can [configure the disk name yourself](#disk).

```php
// config/filesystems.php
return [
    'disks' => [
        // ...
        'posts' => [
            'driver' => 'local',
            'root' => base_path('posts'),
        ],
    ],
];

// config/sheets.php
return [
    'collections' => ['posts'],
];
```

Sheets will create a repository for the `posts` folder in your application.

```
app/
config/
posts/
  hello-world.md
```

```
---
title: Hello, world!
---
# Hello, world!

Welcome to Sheets!
```

A repository has two public methods: `all()` and `get($slug)`. You can get a repository instance through the `collection` method on `Sheets`.

`Repository::all()` will return an `Illuminate\Support\Collection` containing `Spatie\Sheets\Sheet` instances.

```php
$repository = Sheets::collection('posts');

$repository->all();
```

`Repository::get($slug)` returns a single `Sheet` instance or `null` if nothing was found. A sheet's `slug` field contains its filename without an extension.

```php
Sheets::collection('posts')->get('hello-world');
```

A `Sheet` instance is very similar to an Eloquent model. It holds an array of attributes that are exposed as properties. By default it will contain the path as a `slug` field, all front matter data, and a `contents` field containing an HTML representation of the contained Markdown.

```php
$sheet = Sheets::collection('posts')->get('hello-world');

echo $sheet->slug;
// 'hello-world'

echo $sheet->title;
// 'Hello, world!'

echo $sheet->contents;
// '<h1>Hello, world!</h1><p>Welcome to Sheets!</p>'
```

You can create your own `Sheet` implementations with accessors just like Eloquent, but we'll [dive into that later](#sheet-class).

### Configuring collections

Sheets is highly configurable. You can configure each collection separately by using an associative array in `config/sheets.php`.

```php
// config/sheets.php
return [
    'collections' => [
        'pages' => [
            'disk' => null, // Defaults to collection name
            'sheet_class' => Spatie\Sheets\Sheet::class,
            'path_parser' => Spatie\Sheets\PathParsers\SlugParser::class,
            'content_parser' => Spatie\Sheets\ContentParsers\MarkdownWithFrontMatterParser::class,
            'extension' => 'md',
        ],
    ],
];
```

Above is what a collection's default configuration looks like (the configuration we've been working until now). When configuring a collection, every key is optional, if it doesn't exist, Sheets will use one of these values.

#### Disk

The disk name where your content is stored. Disks are configured in `config/filesystems.php`. By default, Sheets will look for a disk with the same name as the collection.

```php
// config/sheets.php
return [
    'collections' => [
        'pages' => [
            'disk' => null, // Uses the 'pages' disk
        ],
    ],
];
```

```php
// config/sheets.php
return [
    'collections' => [
        'pages' => [
            'disk' => 'sheets', // Uses the 'sheets' disk
        ],
    ],
];
```

#### Sheet class

Your content will be casted to `Sheet` instances. The `Sheet` class is similar to a trimmed-down Eloquent model: it holds a set of attributes that are available as properties.

```php
$sheet = Sheets::collection('page')->get('hello-world');

echo $sheet->slug;
// 'hello-world'
```

You can extend the `Sheet` class to add accessors (just like [in Eloquent](https://laravel.com/docs/5.6/eloquent-mutators#accessors-and-mutators)) and custom behavior.

```php
namespace App;

use Spatie\Sheets\Sheet;

class Page extends Sheet
{
    public function getUrlAttribute(): string
    {
        return url($this->slug);
    }
}
```

```php
// config/sheets.php
return [
    'collections' => [
        'pages' => [
            'sheet_class' => App\Page::class,
        ],
    ],
];
```

```php
$sheet = Sheets::collection('pages')->get('hello-world');

echo $sheet->url;
// 'https://example.app/hello-world'
```

#### Path parser

Sheets uses the file path to determine part of the `Sheet` attributes. A path parser is able to parse the path to a set of attributes.

The default path parser is the `SlugParser`, which simply adds a `slug` attribute based on the file name.

```php
namespace Spatie\Sheets\PathParsers;

use Spatie\Sheets\PathParser;

class SlugParser implements PathParser
{
    public function parse(string $path): array
    {
        return ['slug' => explode('.', $path)[0]];
    }
}
```

You can customize the collection's path parser with the `path_parser` option.

```php
// config/sheets.php
return [
    'collections' => [
        'posts' => [
            'path_parser' => Spatie\Sheets\PathParsers\SlugWithDateParser::class,
        ],
    ],
];
```

Above, we configured the path parser for `posts` to the `SlugWithDateParser`, which allows you to prefix your filenames with a date. This is useful for time-sensitive content like blogs.

```
posts/
  2018-05-05.my-first-post.md
```

The above sheet will have two attributes: a `date` containing an `Illuminate\Support\Carbon` instance, and a `slug` `my-first-post`.

You can write your own path parsers by implementing the `Spatie\Sheets\PathParser` interface. Path parsers are instantiated through Laravel's container, so you can inject it's dependencies via the `__construct` method if desired.

#### Content Parser

Content parsers are similar to path parsers, but are in charge of parsing the file's contents.

The default content parser is the `MarkdownWithFrontMatterParser`, which extracts front matter and transforms Markdown to HTML.

```php
class MarkdownWithFrontMatterParser implements ContentParser
{
    /** @var \League\CommonMark\CommonMarkConverter */
    protected $commonMarkConverter;

    public function __construct(CommonMarkConverter $commonMarkConverter)
    {
        $this->commonMarkConverter = $commonMarkConverter;
    }

    public function parse(string $contents): array
    {
        $document = YamlFrontMatter::parse($contents);

        return array_merge(
            $document->matter(),
            ['contents' => $this->commonMarkConverter->convertToHtml($document->body())]
        );
    }
}
```

You can customize the collection's content parser with the `content_parser` option.

```php
// config/sheets.php
return [
    'collections' => [
        'pages' => [
            'content_parser' => Spatie\Sheets\ContentParsers\MarkdownParser::class,
        ],
    ],
];
```

Above, we configured the path parser for `pages` to the `MarkdownParser`, which parses Markdown files _without_ front matter.

You can write your own content parsers by implementing the `Spatie\Sheets\ContentParser` interface. Content parsers are instantiated through Laravel's container, so you can inject it's dependencies via the `__construct` method if desired.

#### Extension

The file extension used in a collection. Defaults to `md`.

#### Default collections

You can call `get` or `all` on the `Sheets` instance without specifying a collection first to query the default collection.

```php
// Return all sheets in the default collection
Sheets::all();
```

You can specify a default collection in `sheets.config`. If no default collection is specified, the default collection will be the first collection registered in the `collections` array.

Here the default collection will implicitly be set to `pages`:

```php
return [
    'default_collection' => null,

    'collections' => [
        'pages',
    ],
];
```

Below the default collection is set to `pages`:

```php
return [
    'default_collection' => 'pages',

    'collections' => [
        'posts',
        'pages',
    ],
];
```

### Route model binding

You can register custom route resolution logic to immediately inject `Sheet` instances in your controller actions.

```php
// app/Providers/RouteServiceProvider.php

public function boot()
{
    parent::boot();

    Route::bind('sheet', function ($path) {
        return $this->app->make(Spatie\Sheets\Sheets::class)
            ->get($path) ?? abort(404);
    });
}
```

Now the router will resolve any `sheet` parameter to a `Sheet` object.

```php
Route::get('/{sheet}', 'SheetsController@show');

class SheetsController
{
    public function show(Sheet $sheet)
    {
        return view('sheet', ['sheet' => $sheet]);
    }
}
```

It might be useful to register specific bindings for other collections.

```php
use Spatie\Sheets\Sheets;

Route::bind('post', function ($path) {
    return $this->app->make(Sheets::class)
        ->collection('posts')
        ->get($path) ?? abort(404);
});
```

The Laravel docs have an entire section on [Route Model Binding](https://laravel.com/docs/5.6/routing#route-model-binding).

### Use subdirectories to organize your sheets

When you want to organize your sheets using (sub)directories, you need to define the route parameter to accept all characters. This way the complete relative path for your sheets will be sent to the controller.

```php
Route::get('/', 'SheetsController@index')->where('sheet', '(.*)');
Route::get('{sheet}', 'SheetsController@show')->where('sheet', '(.*)');
```

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

### Security

If you've found a bug regarding security please mail [security@spatie.be](mailto:security@spatie.be) instead of using the issue tracker.

## Credits

- [Sebastian De Deyne](https://github.com/sebastiandedeyne)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
