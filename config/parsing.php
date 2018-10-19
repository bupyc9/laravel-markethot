<?php
/**
 * @author Afanasyev Pavel <bupyc9@gmail.com>
 */

return [
    'items' => [
        [
            'strategy' => \App\Services\CatalogParser\Strategies\MarkethotStrategy::class,
            'url' => 'https://markethot.ru/export/bestsp',
        ],
    ],
];