<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    /**
     * @param Request $request
     * @return View
     * @throws \InvalidArgumentException
     */
    public function index(Request $request): View
    {
        $q = $request->query('q');

        $products = Product::query()
            ->orWhere('name', 'like', '%' . $q . '%')
            ->orWhere('description', 'like', '%' . $q . '%')
            ->orderBy('name', 'asc')
            ->paginate(20);

        return view('products', ['title' => 'Поиск', 'products' => $products]);
    }
}
