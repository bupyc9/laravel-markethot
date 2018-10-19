<?php

namespace App\Services\CatalogParser;

/**
 * @author Afanasyev Pavel <bupyc9@gmail.com>
 */
interface CatalogParserInterface
{
    public function setStrategy(ParserStrategyInterface $strategy);

    public function handle(string $url): void;
}