<?php

namespace App\Services\CatalogParser;

/**
 * @author Afanasyev Pavel <bupyc9@gmail.com>
 */
class CatalogParserContext implements CatalogParserInterface
{
    /** @var ParserStrategyInterface */
    private $strategy;

    public function __construct(ParserStrategyInterface $strategy)
    {
        $this->strategy = $strategy;
    }

    public function setStrategy(ParserStrategyInterface $strategy): CatalogParserInterface
    {
        $this->strategy = $strategy;

        return $this;
    }

    public function handle(string $url): void
    {
        $this->strategy->process($url);
    }
}