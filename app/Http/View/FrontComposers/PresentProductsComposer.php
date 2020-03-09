<?php
/**
 * Created by PhpStorm.
 * User: fomvasss
 * Date: 27.01.19
 * Time: 11:51
 */

namespace App\Http\View\FrontComposers;

use App\Cart\ProductCart;
use Illuminate\View\View;

class PresentProductsComposer
{
    /**
     * @param \Illuminate\View\View $view
     */
    public function compose(View $view)
    {
        $present_products = \Cache::remember(serialize(variable('product_presents', '[]')), 10, function () {
            return \App\Models\Shop\Product::withBase()
                ->isPublish()->whereIn('id', json_decode(variable('product_presents', '[]')))->get();
        });

        $view->with(compact('present_products'));
    }
}