<?php

namespace App\Providers;

use App\Helpers\FacetFilter\FacetFilterBuilder;
use App\Helpers\Currency\Currency;
use App\Helpers\Favorites\Facades\Favorite;
use App\Helpers\Menu\Menu;
use App\Helpers\ShoppingCart\Cart;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \App\Models\Shop\Product::observe(\App\Observers\Shop\ProductObserver::class);
        \App\Models\Shop\Sale::observe(\App\Observers\Shop\SaleObserver::class);
        \App\Models\Shop\Order::observe(\App\Observers\Shop\OrderObserver::class);
        \App\Models\Taxonomy\Term::observe(\App\Observers\TermObserver::class);
        \App\Models\Page::observe(\App\Observers\PageObserver::class);

        Validator::extend('password_current', function ($attribute, $value, $parameters, $validator) {
            return \Illuminate\Support\Facades\Hash::check($value, current($parameters));
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(FacetFilterBuilder::class, function ($app) {
            return new FacetFilterBuilder();
        });

        $this->app->singleton(Menu::class, function ($app) {
            return new Menu($app);
        });


        $this->app->bind(Favorite::class);
        foreach (\App\Helpers\Favorites\Favorite::$storageDrivers as $name => $class) {
            $this->app->singleton($class);
        }


        $this->app->bind(Cart::class, function ($app) {
            return new Cart($app);
        });
        foreach ($this->app['config']->get('shopping-cart.storage_drivers') as $name => $class) {
            $this->app->singleton($class);
        }

        //$this->app->bound(Currency::class, function ($app) {
        //    return new Currency($app);
        //});

        // TODO add variable
        if (! $this->app->request->is('admin*')) {
            $this->app['config']->set('currency.currencies.RUB.symbol', ' руб.');
            $this->app['config']->set('currency.currencies.RUB.precision', '0');
        }
    }
}
