<?php

namespace App\Jobs;

use App\Services\CatalogParser\CatalogParserContext;
use App\Services\CatalogParser\ParserStrategyInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class RunParsingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var ParserStrategyInterface
     */
    private $strategy;
    /**
     * @var string
     */
    private $url;

    /**
     * Create a new job instance.
     *
     * @param ParserStrategyInterface $strategy
     * @param string $url
     */
    public function __construct(ParserStrategyInterface $strategy, string $url)
    {
        $this->strategy = $strategy;
        $this->url = $url;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $parserContext = new CatalogParserContext($this->strategy);
        $parserContext->handle($this->url);
    }
}
