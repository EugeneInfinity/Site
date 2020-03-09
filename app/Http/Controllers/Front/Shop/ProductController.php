<?php

namespace App\Http\Controllers\Front\Shop;

use App\Helpers\FacetFilter\FacetFilterBuilder;
use App\Managers\FacetFilterManager;
use App\Managers\ProductManager;
use App\Models\Shop\Attribute;
use App\Models\Shop\Product;
use App\Models\Shop\ProductReview;
use App\Models\Shop\Value;
use App\Models\Taxonomy\Term;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;

class ProductController extends Controller
{
    protected $productManager;

    /**
     * ProductController constructor.
     *
     * @param $productManager
     */
    public function __construct(ProductManager $productManager)
    {
        $this->productManager = $productManager;
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function product($id)
    {
        $product = Product::isPublish()->whereHas('txCategory')->findOrFail($id);

        $reviews = ProductReview::isPublish()->where('product_id', $product->id)->orderByDesc('created_at')->get();//$product->group->reviews()->isPublish()->get();

        list($uniqueAttributes, $valuesTree) = $this->productManager->cardAttributesValuesBuild($product); // TODO: add cached ?

        // TODO: add check AJAX request
        return view('front.products.product', [
            'product' => $product,
            'attributes' => $uniqueAttributes,
            'valuesTree' => $valuesTree,
            'reviews' => $reviews,
        ]);
    }

    /**
     * Get product by category (& category children).
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     * @throws \Throwable
     */
    public function category(Request $request, $id)
    {
        $filter = \FacetFilter::toArray($request->get(FacetFilterBuilder::$filterUrlKey, ''));

        $category = Term::isPublish()->byVocabulary('product_categories')->findOrFail($id);

        /*
         * category + category children
         */
        $categoryIds = array_merge([$category->id], $category->children->pluck('id')->toArray());


        $categoryTemp = Term::byVocabulary('product_categories')->findOrFail($id);
        list($categoryAttributes, $attributeValues) = $this->productManager->facetAttributesValuesBuild($categoryTemp, $categoryIds, $filter);

        /*
         * Products.
         */
        $products = Product::withBase()->whereHas('txCategory')->isPublish()->facetFilter($filter)
            ->byTaxonomies(['product_categories' => $categoryIds])
            ->sortable('created_at')->paginate();

        /*
         * For ajax pagination alias links.
         */
        $products->appends($request->except('page'))
            ->setPath(\UrlAlias::current());

        if ($request->ajax()) {
            return response()->json([
                'message' => trans('notifications.operation.success'),
                'html' => view('front.products.inc.grid-products', compact('products'))->render(),
                'nextPageUrl' => $products->nextPageUrl(),
            ]);
        }

        return view('front.products.products', [
            'category' => $category,
            'categories' => $category->children,
            'products' => $products,
            'facet' => [
                'attributes' => $categoryAttributes,
                'values' => $attributeValues,
            ],
        ]);
    }

    public function search(Request $request)
    {
        if ($request->q) {
            $products = Product::isPublish()
                ->whereHas('txCategory')
                ->with('media', 'urlAlias', 'group.product.media', 'reviews')
                ->where("name", "LIKE", "%{$request->q}%")
                ->limit($request->get('limit', 300))
                ->get();

            $html = view('front.products.inc.search-result', compact('products'))->render();
            if ($request->ajax()) {
                return response()->json([
                    'message' => trans('notifications.operation.success'),
                    'html' => $html,
                ]);
            }

            return $html;
        }

        return '';
    }
}
