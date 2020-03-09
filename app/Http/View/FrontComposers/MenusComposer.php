<?php
/**
 * Created by PhpStorm.
 * User: fomvasss
 * Date: 27.01.19
 * Time: 11:51
 */

namespace App\Http\View\FrontComposers;

use App\Models\Menu\MenuItem;
use App\Models\Shop\Product;
use Illuminate\View\View;

class MenusComposer
{
    protected $data = null;

    /**
     * @param \Illuminate\View\View $view
     */
    public function compose(View $view)
    {
        if ($this->data === null) {
            $data = \Cache::remember('front_menus', 10, function () { // TODO cache name
                $menu_items_main_menu = MenuItem::byMenu('main_menu')->get()->toTree(); // TODO dynamic
                $menu_items_social_network = MenuItem::byMenu('social_networks')->with('media')->get();
                $menu_items_info_part = MenuItem::byMenu('info_part')->get();
                $menu_items_slider_in_home = MenuItem::byMenu('slider_in_home')->with('media')->get();


                return compact('menu_items_main_menu', 'menu_items_info_part', 'menu_items_slider_in_home', 'menu_items_social_network');
            });

            $this->data = $data;
        }

        $this->data['product_top'] = $this->getProductTop();

        $view->with($this->data);
    }

    /**
     * @return mixed
     */
    protected function getProductTop()
    {
        if (\Route::is('category.show')) {
            $category_id = \Route::current()->parameter('id');
            $product = \Cache::remember(md5("product.top.category.show/$category_id"), 60, function () use ($category_id) {
                return Product::isPublish()->where('category_id', $category_id)->orderByDesc('rating')->first();
            });
        } else {
            $product = \Cache::remember(md5("product.top"), 60, function () {
                return Product::isPublish()->orderByDesc('rating')->first();
            });
        }

        return $product;
    }
}