# Store & retrieve your static content in plain text files

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/sheets.svg?style=flat-square)](https://packagist.org/packages/spatie/sheets)
[![Build Status](https://img.shields.io/travis/spatie/sheets/master.svg?style=flat-square)](https://travis-ci.org/spatie/sheets)
[![Quality Score](https://img.shields.io/scrutinizer/g/spatie/sheets.svg?style=flat-square)](https://scrutinizer-ci.com/g/spatie/sheets)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/sheets.svg?style=flat-square)](https://packagist.org/packages/spatie/sheets)

Sheets is a Laravel package to store, retrieve & index content stored as text files. Markdown & front matter are supported out of the box, but you can parse & extract data from your files in whatever format you prefer.

Sheets can be added to any existing Laravel application and is a perfect fit for documentation sites & personal blogs.

```md
---
title: Home
---
# Hello, world!

Welcome to sheets!
```

```php
class SheetController
{
    public function index(Sheets $sheets)
    {
        return view('sheet', [
            $sheet => $sheets->get('home'),
        ]);
    }

    public function show(string $id, Sheets $sheets)
    {
        return view('sheet', [
            $sheet => $sheets->get($id),
        ]);
    }
}
```

```blade
@extends('layouts.app', [
    'title' => $sheet->title,
])

@section('main')
    {{ $sheet->content }}
@endsection
```

Alternatively, you can use our router macro to get started without creating a controller.

```php
Route::sheets('/', [
    'sheet' => 'home',
    'view' => 'sheet',
]);

Route::sheets('/{sheet}', 'pages', [
    'view' => 'sheet',
]);
```

### Features

- Allows any document format (by default markdown files with front matter)
- Stores your content wherever you want (uses Laravel's filesystem component)
- Keeps multiple collections of content (e.g. posts, pages, etc.)
- Casts your document contents to Eloquent-like classes with accessors
- Convention over configuration, near-0 setup if you use the defaults

## Installation

You can install the package via composer:

```bash
composer require spatie/sheets
```

Laravel will auto-discover and register the `SheetsServiceProvider`, so no further setup is required.

After installing, you can publish the `sheets.php` configuration file:

```
php artisan vendor:publish --provider="Spatie\Sheets\SheetsServiceProvider" --tag="config"
```

## Usage

You can use the package with a facade, helper function, or with dependency injection.

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

### Default collections

You can call `get` or `all` on the `Sheets` instance without specifying a collection first to query the default collection.

```
// Return all sheets in the default collection
$sheets->all();
```

You can specify a default collection in `sheets.config`. If no default collection is specified, the default collection will be the **first** collection registered in the `collections` array.

```php
return [
    'default_collection' => null,

    'collections' => [
        'posts',
    ],
];
```

In the above example, the default collection will implicitly be set to `posts`.

```php
return [
    'default_collection' => 'pages',

    'collections' => [
        'posts',
        'pages',
    ],
];
```

Here the default collection is set to `pages`.

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email freek@spatie.be instead of using the issue tracker.

## Postcardware

You're free to use this package, but if it makes it to your production environment we highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using.

Our address is: Spatie, Samberstraat 69D, 2060 Antwerp, Belgium.

We publish all received postcards [on our company website](https://spatie.be/en/opensource/postcards).

## Credits

- [Sebastian De Deyne](https://github.com/sebastiandedeyne)
- [All Contributors](../../contributors)

## Support us

Spatie is a webdesign agency based in Antwerp, Belgium. You'll find an overview of all our open source projects [on our website](https://spatie.be/opensource).

Does your business depend on our contributions? Reach out and support us on [Patreon](https://www.patreon.com/spatie).
All pledges will be dedicated to allocating workforce on maintenance and new awesome stuff.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
