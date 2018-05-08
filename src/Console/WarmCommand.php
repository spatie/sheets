<?php

namespace Spatie\Sheets\Console;

use Spatie\Sheets\Sheet;
use Spatie\Sheets\Sheets;
use Illuminate\Console\Command;

class WarmCommand extends Command
{
    protected $signature = 'sheets:warm {--collection=}';

    protected $description = 'Warm up the caches for Sheets collections';

    /** @var \Spatie\Sheets\Sheets */
    protected $sheets;

    public function __construct(Sheets $sheets)
    {
        parent::__construct();

        $this->sheets = $sheets;
    }

    public function handle()
    {
        $this->info('Start warming cache...');

        if ($this->option('collection')) {
            $this->warm($this->option('collection'));

            return;
        }

        $this->sheets->getRegisteredCollectionNames()->each(function (string $collectionName) {
            $this->warm($collectionName);
        });
    }

    protected function warm(string $collectionName)
    {
        $this->sheets->collection($collectionName)->all()->each(function (Sheet $sheet) use ($collectionName) {
            $this->sheets->collection($collectionName)->get($sheet->slug);
        });

        $this->info("Warmed up cache for {$this->option('collection')}");
    }
}
