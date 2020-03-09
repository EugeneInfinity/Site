@extends('front.layouts.app')

@php
    MetaTag::setDefault(['title' => 'Избранное']);
@endphp

@section('content')
    <div class="card-product card-product_personal">
        <div class="card-product__wrapper">

            <div class="card-product__nav">
            {!! Breadcrumbs::render('account.favorites') !!}
            </div>

            <div class="personal">
                <div class="personal__wrapper">
                    <div class="personal__head">
                        <h1 class="personal__name">Избранное</h1>
                        <div class="personal__text">Здесь вы можете изменить ваши персональные данные, настроить уведомления, посмотреть историю заказов и др.</div>
                    </div>
                </div>
                <div class="personal__content">
                    <div class="line"></div>
                    <div class="personal__menu">
                        <ul>
                            <li><a href="{{ route('account.edit') }}">Личные данные</a></li>
                            <li><a href="{{ route('account.history') }}">История заказов</a></li>
                            <li class="active"><a href="{{ route('account.favorites') }}">Избранное </a></li>
                        </ul>
                        <a href="#" class="out js-action-click" data-url="{{ route('logout') }}">
                            Выйти
                        </a>
                    </div>
                    <div class="favorites-info">
                        @forelse($products as $product)
                        <div class="favorites-block">
                            <div class="favorites-block__left">
                                <a href="{{ route('product.show', $product) }}">
                                    <img src="{{ $product->getFirstMediaUrl('images', 'favorite') ?: '/its-client/img/home-img.png' }}" alt="{{ $product->name }}">
                                </a>
                                <a href="{{ route('product.show', $product) }}">
                                    <h5>{{ str_limit($product->name, 30) }}</h5>
                                </a>
                                <h5 class="mobile-name">{{ $product->name }}</h5>
                            </div>
                            <div class="favorites-block__right">
                                <div class="favorites-block__right-hide">Кол-во:</div>
                                    <div class="favorites-block__right-block">
                                    <p class="size">{{ $product->valuesStr() }}</p>
                                    <p class="price">{{ Currency::format($product->getCalculatePrice('price'), $product->currency) }}</p>
                                </div>
                                <button class="btn-gen js-action-click"
                                    data-url="{{ route('shopping-cart.add', $product) }}"
                                    data-html-container="#product-cart">
                                    <span>В корзину</span> <img src="/its-client/img/basket.png" alt="">
                                </button>
                                <button class="close js-action-click" data-url="{{ route('product-favorite.remove', $product) }}"><span>Удалить</span> <img src="/its-client/img/close.png" alt=""></button>
                            </div>
                        </div>
                        @empty
                            <h3 style="text-align: center; margin: 30px auto;">Избаранных товаров пока еще нет :(</h3>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection