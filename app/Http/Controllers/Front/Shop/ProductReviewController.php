<?php

namespace App\Http\Controllers\Front\Shop;

use App\Http\Requests\Front\Shop\ProductReviewRequest;
use App\Models\Shop\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductReviewController extends Controller
{
    public function index(Request $request)
    {
        $product = Product::isPublish()->findOrFail($request->product_id);
        $reviews = $product->group->reviews;

        $html = view('front.products.inc.reviews', compact('reviews', 'product'))->render();
        if ($request->ajax()) {
            return response()->json([
                'html' => $html,
            ]);
        }

        return $html;
    }

    public function store(ProductReviewRequest $request)
    {
        Product::isPublish()->findOrFail($request->product_id)->reviews()->create([
            'body' => $request->body,
            'product_id' => $request->product_id,
            'rating' => $request->rating,
            'user_id' => $request->user()->id,
        ]);

        return response()->json([
            //'message' => 'Ваш отзыв успешно отправлен на модерацию!',
            'status' => 'success',
            'action' => 'reset',
            //'destination' => $this->redirectTo,
            'html' => view('front.products.inc.search-result', compact('products'))->render(),
        ]);

        return redirect()->back();
    }
}
