<?php

namespace App\Console\Commands;

use App\Jobs\RunParsingJob;
use App\Services\CatalogParser\ParserStrategyInterface;
use Illuminate\Console\Command;

class RunParsingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parsing:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run parsing';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(): void
    {
        $items = config('parsing.items');

        foreach ($items as $item) {
            $strategy = new $item['strategy'];
            if (!$strategy instanceof ParserStrategyInterface) {
                $this->error("Strategy `{$item['strategy']}` not implement interface " . ParserStrategyInterface::class);
                continue;
            }
            if (!\filter_var($item['url'], FILTER_VALIDATE_URL)) {
                $this->error("Invalid url `{$item['url']}`");
                continue;
            }

            RunParsingJob::dispatch($strategy, $item['url']);
        }
    }
}
