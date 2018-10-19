<?php

namespace App\Services\CatalogParser;

/**
 * @author Afanasyev Pavel <bupyc9@gmail.com>
 */
interface ParserStrategyInterface
{
    public function process(string $url);
}