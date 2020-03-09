<?php

namespace App\Http\Controllers\Front\Shop;

use App\Models\Shop\Sale;
use App\Http\Controllers\Controller;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::whereShow(true)->isPublish()->orderBy('end_at')->get();

        return view('front.sales.index', compact('sales'));
    }

    public function show($id)
    {
        $sale = Sale::whereShow(true)->isPublish()->findOrFail($id);

        $sales = Sale::whereShow(true)->isPublish()->where('id', '<>', $sale->id)
            ->inRandomOrder()->limit(3)->get();

        return view('front.sales.show', compact('sale', 'sales'));
    }
}
