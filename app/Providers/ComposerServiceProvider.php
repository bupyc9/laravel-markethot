<?php

namespace App\Providers;

use App\Http\ViewComposers\CategoryComposer;
use Illuminate\Support\ServiceProvider;

/**
 * @author Afanasyev Pavel <bupyc9@gmail.com>
 */
class ComposerServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        \View::composer('categories', CategoryComposer::class);
    }
}