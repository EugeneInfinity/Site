@extends('front.layouts.app')

@php
    MetaTag::setEntity($page)->setDefault(['title' => 'Главная страница - '.variable('app_name')])
@endphp

@section('content')
<div class="home">
    <div class="home__wrapper">
        {{-- Слайдер --}}
        @include('front.blocks.slider')

        <div class="home-content">
            {{-- Бестселлеры --}}
            @include('front.blocks.recommend-products-home', ['title' => 'Бестселлеры',])

            {{-- Подарочные наборы --}}
            @include('front.blocks.present-products-home')

            {{-- Преимучества --}}
            @include('front.blocks.advantages')
        </div>

        {{-- Инстаграм --}}
        @include('front.blocks.instagram')

        {{-- Подписаться --}}
        <div class="home-content home-content_bottom">
            <div class="home-content__bottom">
                <div class="home-content__bottom-left">
                    
                    {!! variable('page_home_subscribe_text') !!}

                    <form action="{{ route('form.store') }}"
                          method="POST"
                          data-id="home-subscribe"
                          class="js-ajax-form-submit"
                          data-seo-action="send_subsctiption_main_page_form"
                    >
                        @csrf
                        @honeypot
                        <input type="hidden" name="type" value="subscribers">
                        <div class="form-group">
                            <input name="email" type="email" placeholder="E-mail*">
                        </div>
                        <div class="form-group form-group_check">
                            <label>
                                <input type="hidden" name="accept" value="0">
                                <input class="checkbox" type="checkbox" name="accept" value="1">
                                <span class="checkbox-custom"></span>
                                <p class="label">Я согласен(-на) с <a href="/policy/"> политикой конфиденциальности*</a></p>
                            </label>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn-gen">Подписаться</button>
                        </div>

                    </form>
                </div>
                <div class="home-content__bottom-right">
                    <img src="/its-client/img/home-bottom.png" alt="">
                </div>
            </div>
        </div>

        {{-- Почему Hipertin? --}}
        <div class="why-hipertin">
            <div class="why-hipertin__wrapper">
                <h2 class="home-content__head-name">Почему Hipertin?</h2>
                <p class="why-hipertin__text">Компания Hipertin - это семейный бизнес, основанный недалеко от Барселоны в 1944 году. Создатели бренда поставили себе цель - вывести на новый уровень рынок профессиональной косметики для волос в Испании. Спустя 75 лет Hipertin доверяют 100000 стилистов и их клиентов по всей Европе.</p>
                <p class="why-hipertin__text">Компания изготавливает косметику только из протестированных высококачественых <br> компонентов и поддерживает приятные и оправданные цены на свою продукцию.</p>
                <p class="why-hipertin__text">В России косметика Hipertin появилась в 2009 году. Клиенты <br> полюбили продукцию бренда за качество и эффективность.</p>
                <p class="why-hipertin__text">
                    Сайт
                    <a href="/" target="_blank">hipertin.ru</a>
                    - официальное представительство бренда в России.
                </p>
            </div>
        </div>

    </div>
</div>
@endsection
