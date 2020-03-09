@extends('front.layouts.app')

@php
    MetaTag::setDefault(['title' => 'Корзина товаров']);
@endphp

@section('content')

    <div class="header__gray-block">
    </div>

    <div class="basket-container">
        <div class="bready-crumbs">
            <ul class="nav-menu">
                <li><a href="/">Главная</a>/</li>
                <li><span>Корзина</span></li>
            </ul>
        </div>

        <form action="{{ route('shopping-cart.order') }}"
              method="POST"
              data-id="cart-form"
              class="js-ajax-form-submit"
              id="cart-form"
              data-seo-action="send_order_form"
        >
            @csrf
            <div class="g-wrapper-basket" id="cart-page-content">
                @includeWhen($cart['products']->count(), 'front.shopping-cart.inc.cart-content', [
                    'cart' => $cart,
                    'purchase' => $purchase,
                    'delivery' => $delivery,
                ])
            </div>
        </form>

        @includeWhen(isset($recommendation) && $recommendation->count(), 'front.shopping-cart.inc.recommendation', [
            'products' => $recommendation
        ])
    </div>

@endsection
