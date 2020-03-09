<?php

namespace App\Rules;

use App\Models\Shop\Sale;
use Illuminate\Contracts\Validation\Rule;

class SalePromoCode implements Rule
{
    protected $message = '';

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $this->message = 'The validation error message.';

        if (! ($promoCode = \App\Models\Shop\SalePromoCode::where('code', $value)->first())) {
            $this->message = 'Значение промокода введено неверно!';

            return false;
        }

        if (! \App\Models\Shop\SalePromoCode::where('code', $value)->isAvailable()->first()) {
            $this->message = 'Срок действия промокода истек!';

            return false;
        }

        if (! \App\Models\Shop\SalePromoCode::where('code', $value)->where(function ($c) {
            $c->whereColumn('used_limit', '>', 'used_count')->orWhere('used_limit', 0);
        })->first()) {
            $this->message = 'Промокод уже использован!';

            return false;
        }

        if ($promocode = \App\Models\Shop\SalePromoCode::where('code', $value)->first()) {
            $sale = $promocode->sale;

            if (!empty($sale->data['min_sum']) || $sale->data['min_sum'] == 0) {

                // Скидка на цену товаров по промокодам (и в которых нет скидки, старой цены)
                if ($sale->type == Sale::TYPE_PROM_CODE_PRODUCT || $sale->type == Sale::TYPE_PROM_CODE_DISCOUNT_SUM_ORDER) {
                    $sumProdInCart = $this->sumProdInCartWhereHasNotPriceOld();
                } else {
                    $sumProdInCart = $this->sumProdInCart();
                }



                // заказ не подходит по сумме товаров
                // или акция типа "подарок" и не для одного товара не можно Применять подарок
                if ($sale->data['min_sum'] >= $sumProdInCart || ($sale->type == Sale::TYPE_PROM_CODE_PRODUCT_PRESENT && !$this->getCartProds()->intersect($sale->products))) {
                    $this->message = 'Сумма заказа меньше необходимой для активации промокода!';
                    session()->forget('cart.promocode');
                    return false;
                }
            }
        }



        return true;
    }

    protected function sumProdInCartWhereHasNotPriceOld()
    {
        $cartProducts = $this->getCartProds();

        $productsCounts = \Cart::get();

        $cartProducts = $cartProducts->filter(function ($product) {
            if ($product->getCalculatePrice('discount') <= 0) {
                return $product;
            }
        });

        return $cartProducts->map(function ($product) use ($productsCounts) {
            return $product->getCalculatePrice('price') * $productsCounts[$product->id];
        })->sum();
    }

    protected function sumProdInCart()
    {
        $cartProducts = $this->getCartProds();

        $productsCounts = \Cart::get();

        return $cartProducts->map(function ($product) use ($productsCounts) {
            return $product->getCalculatePrice('price') * $productsCounts[$product->id]; // TODO currency
        })->sum();
    }

    protected function getCartProds()
    {
        $productsIdInCart = \Cart::getIds();

        return \App\Models\Shop\Product::isPublish()
            ->whereIn('id', $productsIdInCart)->get();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
