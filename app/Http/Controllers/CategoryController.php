<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * @param string $code
     * @param string|null $childCode
     * @return View
     * @throws \InvalidArgumentException
     */
    public function index(string $code, string $childCode = null): View
    {
        if ($childCode) {
            $category = Category::whereCode($childCode)
                ->with(
                    [
                        'parent' => function (HasOne $query) use ($code) {
                            $query->where('code', '=', $code);
                        },
                    ]
                )
                ->firstOrFail();
        } else {
            $category = Category::whereCode($code)->firstOrFail();
        }

        $products = Product::query()
            ->whereHas(
                'categories',
                function (Builder $query) use ($category) {
                    $query->where('categories.id', '=', $category->id);
                }
            )
            ->orderBy('name', 'asc')
            ->paginate(20);

        return view('products', ['title' => $category->name, 'products' => $products]);
    }
}
