@extends('front.layouts.app')

@php
    MetaTag::setEntity($product)
        ->setDefault(['title' => $product->name])
        ->setTags([
            'title' => StrToken::setText(optional($product->metaTag)->title)->setEntity($product)->replace(),
            'description' => \Illuminate\Support\Str::limit(StrToken::setText(optional($product->metaTag)->description)->setEntity($product)->replace(), 250, ''),
        ]);
@endphp

@section('content')
<div class="card-product">

    <div class="card-product__wrapper">

        <div class="card-product__nav">
            {!! Breadcrumbs::render('product', $product) !!}
        </div>

        <div class="card-product__head">
            <div class="card-product__head-left">
                <img src="{{ $product->getFirstMediaUrl('images', 'table') ?: '/its-client/img/card-product.png' }}" alt="{{ $product->name }}">
                <a href="#" class="home-content__head-like js-action-click
                    @if(\Favorite::is($product->id)) active @else favorite-action @endif"
                    data-url="{{ route('product-favorite.toggle', $product) }}"
                    data-html-container="#product-favorite"
                    data-seo-action="click_like_button"
                    data-seo-label="{{$product->sku}}"
                >
                    <svg class="icon-svg icon-svg-like "><use xlink:href="/its-client/img/sprite.svg#like"></use></svg>
                    <p class="hide">
                    <div class="hide__wrapper">
                        <span>Добавить в избранное</span>
                    </div>
                    </p>
                </a>
            </div>
            <div class="card-product__head-right">

                @if($textureUrl = optional($product->txCategory->getFirstMedia('texture'))->getUrl())
                <a href="{{ $textureUrl }}" class="link-palitre">
                    <div class="palitre"><img src="/its-client/img/palitre.png" alt="">
                        <div class="hint-palitre">
                            Посмотреть палитру
                        </div>
                    </div>
                </a>
                @endif
                <h1 class="card-product__head-name">{!! $product->name !!}</h1>
                <div class="card-product__head-text">Арт. {{ $product->sku }}</div>
                <div class="card-product__head-block">
                    @for($i = 1; $i < 6; $i++)
                        @if($product->reviews->avg('rating') >= $i)
                            <img src="/its-client/img/star-active.png" alt="star-{{$i}}">
                        @else
                            <img src="/its-client/img/star-off.png" alt="star-{{$i}}">
                        @endif
                    @endfor
                </div>
                <div class="card-product__head-volume">

                    @foreach($attributes as $attributeId => $attribute)
                        <div class="volume">
                            <div class="volume-name">{{ $attribute->title }}:</div>
                            @foreach($valuesTree[$attributeId] as $item)
                                @php
                                    $value = $item['value'];
                                    $valueProduct = $item['product'];
                                @endphp

                                @if($product->values->contains($value))
                                    <a href="#" class="active"><span>{{ $value->value }} {!! $value->suffix !!}</span></a>
                                @elseif($valueProduct === null)
                                    <a class="disabled"><span>{{ $value->value }} {!! $value->suffix !!}</span></a>
                                @else
                                    <a href="{{ route_alias('product.show', $valueProduct) }}"><span>{{ $value->value }} {!! $value->suffix !!}</span></a>
                                @endif
                            @endforeach
                        </div>

                        @if($loop->first && !empty($product->data['delivery_info']))
                            <div class="inform">
                                Информация о доставке
                            </div>
                        @endif
                    @endforeach


                </div>
                <div class="card-product__head-text">
                    {!! $product->data['delivery_info'] ?? '' !!}
                </div>
                <div class="card-product__head-price">
                    Цена: @if($product->getCalculatePrice('price_old'))
                        <span>{{ Currency::format($product->getCalculatePrice('price_old'), $product->currency)  }}</span>
                        @endif
                    {{ Currency::format($product->getCalculatePrice('price'), $product->currency) }}
                </div>
                <div class="card-product__head-text">@if($product->availability) В наличии @else Нет в наличии @endif</div>
                <div class="card-product__head-button">
                    <button class="btn-gen js-action-click buy-action"
                            data-url="{{ route('shopping-cart.add', $product) }}"
                            data-html-container="#product-cart"
                            data-seo-action="click_buy_button"
                            data-seo-label="{{ $product->sku }}"
                    >Добавить в корзину</button>
                    <button class="btn-gen_1 js-fill-fields-modal"
                            data-fields='{"product_id":{{$product->id}}}'
                            data-target="#buy-one-click-modal"
                            data-seo-label="{{ $product->sku }}"
                    >Купить в один клик</button>
                </div>
            </div>
        </div>
        <div class="card-product__content">
            <div class="card-product__content-tabs">
                <div class="line"></div>
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#description" role="tab" aria-controls="description" aria-selected="true">{{ $product->getFrontTabTitle('tab_description', 'Описание') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#application" role="tab" aria-controls="application" aria-selected="false">{{ $product->getFrontTabTitle('tab_application', 'Применение') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="contact-tab" data-toggle="tab" href="#composition" role="tab" aria-controls="composition" aria-selected="false">{{ $product->getFrontTabTitle('tab_composition', 'Состав') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="contact-tab" data-toggle="tab" href="#reviews" role="tab" aria-controls="reviews" aria-selected="false">Отзывы</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                        <div class="tab-first">
                            <div class="card-product__tab-head">
                                {{--<h6>Описание продукта:</h6>--}}
                                <span class="small">{!! $product->description !!}</span>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="application" role="tabpanel" aria-labelledby="application-tab">
                        <div class="tab-first">
                            <div class="card-product__tab-head">
                                {{--<h6>Применение продукта:</h6>--}}
                                <span class="small">{!! $product->data['applying'] ?? '' !!}</span>
                            </div>
                        </div>
                    </div>
                    @if($product->getFrontTabTitle('tab_composition', 'Состав') == 'Обучение')
                    <div class="tab-pane fade" id="composition" role="tabpanel" aria-labelledby="training-tab">
                        <div class="training">
                            <div class="training__wrapper">
                                <div class="training__slider">
                                    <div class="swiper-container swiper-container-training">
                                        <div class="swiper-wrapper">

                                            {!! variable('tab_prod_study', '
                                                <div class="swiper-slide">
                                                    <div href="#" class="training__block">
                                                        <a href="#" class="training__img">
                                                            <img src="/its-client/img/training-1.png" alt="">
                                                            </a>
                                                            <div class="training__free">
                                                                <span>Бесплатно</span>
                                                            </div>

                                                        <a href="#" class="training__name">Мир цвета Ипертин</a>
                                                        <a href="#" class="training__btn btn-gen">Узнать подробнее</a>
                                                    </div>
                                                </div>
                                                <div class="swiper-slide">
                                                    <div href="#" class="training__block">
                                                        <a href="#" class="training__img">
                                                            <img src="/its-client/img/training-2.png" alt="">
                                                            </a>
                                                            <div class="training__free">
                                                                <span>Бесплатно</span>
                                                            </div>

                                                        <a href="#" class="training__name">Колористика от А до Я Бесплатный семинар</a>
                                                        <a href="#" class="training__btn training__btn_big btn-gen">Узнать подробнее</a>
                                                    </div>
                                                </div>
                                                <div class="swiper-slide">
                                                    <div href="#" class="training__block">
                                                        <a href="#" class="training__img">
                                                            <img src="/its-client/img/training-3.png" alt="">
                                                            </a>
                                                            <div class="training__free">
                                                                <span>Бесплатно</span>
                                                            </div>

                                                        <a href="#" class="training__name">Блонды от А до Я</a>
                                                        <a href="#" class="training__btn btn-gen">Узнать подробнее</a>
                                                    </div>
                                                </div>
                                            ') !!}

                                        </div>
                                    </div>
                                    <div class="swiper-button-prev swiper-button-prev-training">
                                        <img src="/its-client/img/inst-arrow.png" alt="">
                                    </div>
                                    <div class="swiper-button-next swiper-button-next-training">
                                        <img src="/its-client/img/inst-arrow.png" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="tab-pane fade" id="composition" role="tabpanel" aria-labelledby="composition-tab">
                        <div class="tab-first tab-first_three">
                            <div class="card-product__tab-head">
                                {{--<h6>Состав продукта:</h6>--}}
                                <span class="small">{!! $product->data['composition'] ?? '' !!}</span>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="tab-pane tab-pane_reviews fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                        @guest
                        <div class="tab-pane__head">
                            <h5>Отзывы: {{ $reviews->count() }}</h5>
                            <button class="btn-gen btn-gen_log-in">Войти и оставить отзыв</button>
                        </div>
                        @endguest

                        @auth
                        <div class="tab-pane__head">
                            <h5>Отзывы: {{ $reviews->count() }}</h5>
                        </div>
                        <form action="{{ route('product-review.store') }}"
                              method="POST"
                              class="js-ajax-form-submit"
                              data-id="review-send"
                        >
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">

                            <div class="wrapper-out">
                                <div class="card-product__write form-group">
                                    <textarea id="card-textarea" maxlength='1501' name="body"></textarea>
                                </div>
                                <div class="block-button">
                                    <span class="number"><span id="card-number">0</span>/1500</span>
                                    <button type="submit" class="btn-gen btn-gen_inner">Оставить отзыв</button>
                                </div>
                                <div class="card-product__rating form-group">
                                    <div class="rating-top">
                                        <div class="rating-top__star-block">
                                            <div class="rating" data-path="/its-client/img/">
                                                <img src="/its-client/img/big-star.png" alt="r1" value="1">
                                                <img src="/its-client/img/big-star.png" alt="r2" value="2">
                                                <img src="/its-client/img/big-star.png" alt="r3" value="3">
                                                <img src="/its-client/img/big-star.png" alt="r4" value="4">
                                                <img src="/its-client/img/big-star.png" alt="r5" value="5">
                                                <input type="text" hidden name="rating" value="">
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </form>
                        @endauth
                        @include('front.products.inc.reviews', ['reviews' => $reviews])
                    </div>
                </div>

                @include('front.blocks.recommend-products', [
                   'title' => 'С этим товаром берут',
                ])
            </div>
        </div>
    </div>
</div>
@endsection