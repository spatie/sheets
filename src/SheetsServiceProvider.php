<?php

namespace Spatie\Sheets;

use Illuminate\Cache\CacheManager;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\ServiceProvider;
use League\CommonMark\CommonMarkConverter;
use Spatie\Sheets\Console\WarmCommand;
use Spatie\Sheets\ContentParsers\MarkdownParser;
use Spatie\Sheets\ContentParsers\MarkdownWithFrontMatterParser;
use Spatie\Sheets\PathParsers\SlugParser;
use Spatie\Sheets\Repositories\CacheRepository;
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

            $this->commands([
                WarmCommand::class,
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/sheets.php', 'sheets');

        $this->app->when(MarkdownWithFrontMatterParser::class)
            ->needs(CommonMarkConverter::class)
            ->give(function () {
                return new CommonMarkConverter();
            });

        $this->app->when(MarkdownParser::class)
            ->needs(CommonMarkConverter::class)
            ->give(function () {
                return new CommonMarkConverter();
            });

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

                $repository = new FilesystemRepository(
                    $factory,
                    $this->app->make(FilesystemManager::class)->disk($config['disk']),
                    $config['extension']
                );

                if ($config['cache'] !== false) {
                    $repository = new CacheRepository(
                        $name,
                        $repository,
                        $this->app->make(CacheManager::class)->store($config['cache'])
                    );
                }

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
            'cache' => config('cache.default'),
            'sheet_class' => Sheet::class,
            'path_parser' => SlugParser::class,
            'content_parser' => MarkdownWithFrontMatterParser::class,
            'extension' => 'md',
        ];

        return array_merge($defaults, $config);
    }
}
