<?php

namespace Spatie\Sheets;

use Illuminate\Support\ServiceProvider;
use League\CommonMark\CommonMarkConverter;
use Spatie\Sheets\ContentParsers\FrontMatterWithMarkdownParser;

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

        $this->app->when(FrontMatterWithMarkdownParser::class)
            ->needs(CommonMarkConverter::class)
            ->give(function () {
                return new CommonMarkConverter();
            });

        $this->app->singleton(Sheets::class, function () {
            return new Sheets(config('sheets.collections'));
        });

        $this->app->alias(Sheets::class, 'sheets');
    }
}
