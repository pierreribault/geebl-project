<?php

namespace App\Http\Controllers;

use App\Data\ProductData;
use App\Models\Product;
use Inertia\Inertia;

class ShopController extends Controller
{
    public function show(Product $product)
    {
        return Inertia::render('Shop/Show', [
            'product' => ProductData::fromModel($product),
        ]);
    }

    public function index()
    {
        return Inertia::render('Shop/Index', [
            'products' => ProductData::collection(Product::available()->get()),
        ]);
    }
}
