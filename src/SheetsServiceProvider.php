<?php

namespace Spatie\Sheets;

use Illuminate\Support\ServiceProvider;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\ConverterInterface;
use League\CommonMark\MarkdownConverterInterface;
use Spatie\Sheets\ContentParsers\MarkdownWithFrontMatterParser;
use Spatie\Sheets\PathParsers\SlugParser;
use Spatie\Sheets\Repositories\FilesystemRepository;

class SheetsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/sheets.php' => config_path('sheets.php'),
            ], 'config');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/sheets.php', 'sheets');

        if (! $this->app->bound(CommonMarkConverter::class)) {
            $this->app->singleton(CommonMarkConverter::class);
            $this->app->alias(CommonMarkConverter::class, MarkdownConverterInterface::class);
            $this->app->alias(CommonMarkConverter::class, ConverterInterface::class);
        }

        $this->app->singleton(Sheets::class, function () {
            $sheets = new Sheets();

            foreach (config('sheets.collections', []) as $name => $config) {
                if (is_int($name)) {
                    $name = $config;
                    $config = [];
                }

                $config = $this->mergeCollectionConfigWithDefaults($name, $config);

                $factory = new Factory(
                    $this->app->make($config['path_parser']),
                    $this->app->make($config['content_parser']),
                    $config['sheet_class']
                );

                $repository = $this->app->make($config['repository'], compact('factory', 'config'));

                $sheets->registerCollection($name, $repository);
            }

            if (config('sheets.default_collection')) {
                $sheets->setDefaultCollection(config('sheets.default_collection'));
            }

            return $sheets;
        });

        $this->app->alias(Sheets::class, 'sheets');
    }

    protected function mergeCollectionConfigWithDefaults(string $name, array $config): array
    {
        $defaults = [
            'disk' => $name,
            'sheet_class' => Sheet::class,
            'path_parser' => SlugParser::class,
            'content_parser' => MarkdownWithFrontMatterParser::class,
            'extension' => 'md',
            'repository' => FilesystemRepository::class,
        ];

        return array_merge($defaults, $config);
    }
}
