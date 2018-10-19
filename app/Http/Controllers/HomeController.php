<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $products = Product::query()->orderBy('id', 'desc')->limit(20)->get();

        return view('home', ['products' => $products]);
    }
}
