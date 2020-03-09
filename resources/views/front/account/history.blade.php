@extends('front.layouts.app')

@php
    MetaTag::setDefault(['title' => 'История заказов']);
@endphp

@section('content')
    <div class="card-product card-product_personal card-product_story">
        <div class="card-product__wrapper">

            <div class="card-product__nav">
            {!! Breadcrumbs::render('account.history') !!}
            </div>

            <div class="personal">
                <div class="personal__wrapper">
                    <div class="personal__head">
                        <h1 class="personal__name">История заказов</h1>
                        <div class="personal__text">Здесь вы можете изменить ваши персональные данные, настроить уведомления, посмотреть историю заказов и др.</div>
                    </div>
                </div>
                <div class="personal__content">
                    <div class="line"></div>
                    <div class="personal__menu">
                        <ul>
                            <li><a href="{{ route('account.edit') }}">Личные данные</a></li>
                            <li  class="active"><a href="{{ route('account.history') }}">История заказов</a></li>
                            <li><a href="{{ route('account.favorites') }}">Избранное </a></li>
                        </ul>
                        <a href="#" class="out js-action-click" data-url="{{ route('logout') }}">
                            Выйти
                        </a>
                    </div>
                    <div class="story">
                        @if($orders->count())
                        <div class="story__wrapper">
                            <div class="story__names">
                                <p>№</p>
                                <p>Дата заказа</p>
                                <p>Сумма заказа</p>
                                <p>Статус</p>
                                <p>Статус оплаты</p>
                            </div>
                        </div>
                        <div class="story__collapse">
                            <div class="accordion" id="accordionExample">
                                @foreach($orders as $order)
                                    <div class="card">
                                        <div class="card-header" id="heading{{$order->id}}">
                                            <h5 class="mb-0">
                                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse{{$order->id}}" aria-expanded="true" aria-controls="collapse{{$order->id}}">
                                                    <span>{{ $order->number }}</span>
                                                    <span>{{ $order->created_at->format('d.m.Y H:i') }}</span>
                                                    <span>{{ $order->getFinalSumStr() }}</span>
                                                    <span>{{ optional($order->txStatus)->name }}</span>
                                                    <span>{{ optional($order->txPaymentStatus)->name ?? '-' }}</span>
                                                </button>
                                            </h5>
                                        </div>

                                        <div id="collapse{{$order->id}}" class="collapse {{--show--}}" aria-labelledby="heading{{$order->id}}" data-parent="#accordionExample">
                                            <div class="card-body">
                                                <div class="story-info">
                                                    @foreach($order->products as $product)
                                                    <div class="favorites-info">
                                                        <div class="favorites-block">
                                                            <div class="favorites-block__left">
                                                                <a href="{{ route('product.show', $product) }}">
                                                                    <img src="{{ $product->getFirstMediaUrl('images', 'favorite') ?: '/its-client/img/home-img.png' }}" alt="">
                                                                </a>
                                                                <a href="{{ route('product.show', $product) }}">
                                                                    <h5>{{ str_limit($product->name, 30) }}</h5>
                                                                </a>
                                                                <h5 class="mobile-name">Название товара в несколько новых строчек</h5>
                                                            </div>
                                                            <div class="favorites-block__right">
                                                                <p class="mobile-quantity">Кол-во:<p>
                                                                <p class="size">{{ $product->pivot->quantity }}шт.{{--300мл--}}</p>
                                                                <p class="price">{{ Currency::format($product->pivot->price) }}</p>
                                                                {{--<button class="close"><img src="/its-client/img/close.png" alt=""></button>--}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                    <div class="story-info__blocks">
                                                        <div class="story-info__block">
                                                            <div class="story-info__name">Адрес доставки:</div>
                                                            <div class="story-info__text">
                                                                {{ $order->deliveryAddressStr }}
                                                            </div>
                                                        </div>
                                                        <div class="story-info__block">
                                                            <div class="story-info__name">Доставка</div>
                                                            <div class="story-info__text">{{ $order->getDeliveryMethodStr() }}</div>
                                                        </div>
                                                        <div class="story-info__block">
                                                            <div class="story-info__name">Статус заказа:</div>
                                                            <div class="story-info__text">{{ optional($order->txStatus)->name ?? '-' }}</div>
                                                        </div>
                                                        <div class="story-info__block">
                                                            <div class="story-info__name">Статус оплаты:</div>
                                                            <div class="story-info__text">{{ optional($order->txPaymentStatus)->name }}</div>
                                                        </div>
                                                        <div class="story-info__block">
                                                            <div class="story-info__name">Стоимость доставки</div>
                                                            <div class="story-info__text">{{ Currency::format($order->data['purchase']['delivery'] ?? 0, 'RUB') }}</div>
                                                        </div>
                                                        <div class="story-info__block">
                                                            <div class="story-info__name">Скидка</div>
                                                            <div class="story-info__text">{{ Currency::format($order->data['purchase']['discount'] ?? 0, 'RUB') }}</div>
                                                        </div>
                                                        <div class="story-info__block">
                                                            <div class="story-info__name">Сумма</div>
{{--                                                            <div class="story-info__text">{{ Currency::format($order->price + ($order->data['purchase']['delivery'] ?? 0) - ($order->data['purchase']['discount'] ?? 0), 'RUB') }}</div>--}}
                                                            <div class="story-info__text">{{ $order->getFinalSumStr() }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @else
                            <h3 style="text-align: center; margin: 30px auto;">Заказов пока еще нет :(</h3>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection