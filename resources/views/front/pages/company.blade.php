@extends('front.layouts.app')

@php
    MetaTag::setEntity($page)->setDefault(['title' => $page->name]);
@endphp

@section('content')

    <div class="company">
        <div class="company__wrapper">
        @if($page->body)
            {!! $page->body !!}
        @else
            <div class="company__content company__content_first">
                <div class="company__block">
                    <img src="/its-client/img/about-company-1.png" alt="">
                    <div class="company__info">
                        <h1 class="company__name">
                            Верность традициям
                        </h1>
                        <p class="company__text">
                            Бренд Hipertin зародился как небольшое семейное предприятие в 1944 году недалеко от Барселоны.
                        </p>
                        <p class="company__text">
                            За 75 лет развития компания заслужила репутацию надежного европейского производителя профессиональной косметики для волос.
                        </p>
                    </div>
                </div>
            </div>
            <div class="company__content company__content_bg">
                <div class="company__block company__block_line">
                    <div class="company__info">
                        <h2 class="company__name">
                            Высокие стандарты
                        </h2>
                        <p class="company__text">
                            Hipertin с первого дня основания разрабатывала косметику для профессионалов и их клиентов, поэтому к продукции изначально предъявлялись строгие требования. Технологи бренда располагают современной лабораторией и оборудованием, и контролируют каждый этап производства. Продукция отвечает не только косметическим, но и испанским медицинским стандартам, которые считаются одними из самых высоких в Европе.
                        </p>
                    </div>
                </div>
            </div>
            <div class="company__content company__content_three">
                <div class="company__block">
                    <img src="/its-client/img/about-company-2.png" alt="">
                    <div class="company__info">
                        <h2 class="company__name">
                            Превосходное соотношение цены и качества
                        </h2>
                        <p class="company__text">
                            Компания внимательно следит за пожеланиями клиентов. Полный контроль над собственным производством позволяет Hipertin поддерживать оправданные цены.
                        </p>
                    </div>
                </div>
            </div>
            <div class="company__content  company__content_inner company__content_bg">
                <h2 class="company__name">
                    Без консервантов и искусственных ароматизаторов
                </h2>
                <div class="company__block">
                    <img class="dekstop" src="/its-client/img/about-company-3.png" alt="">
                    <img class="mobile" src="/its-client/img/company-mobile.png" alt="">
                    <div class="company__info">
                        <p class="company__text">
                            Средства для ухода за волосами от Hipertin создаются с использованием натуральных ингредиентов, прошедших несколько стадий очистки. Это повышает содержание в их составе активных веществ, которые увеличивают эффективность продуктов. Средства для стайлинга и серии для окрашивания содержат только протестированные гипоаллергенные компоненты, поэтому они деликатно воздействуют на волос и не травмируют его.
                        </p>
                    </div>
                </div>
            </div>
            <div class="company__content">
                <div class="company__block company__block_reverse">
                    <div class="company__info">
                        <h2 class="company__name">
                            Широкий выбор продукции
                        </h2>
                        <p class="company__text">
                            Ассортимент Hipertin способен полностью обеспечить работу салона. Бренд предлагает большой выбор продуктов для осветления, окрашивания, химической завивки, ухода за волосами и стайлинга. И мастера, и их клиенты найдут необходимые им средства.
                        </p>
                    </div>
                    <img src="/its-client/img/about-company-4.png" alt="">
                </div>
            </div>
            <div class="company__content company__content_last company__content_bg">
                <div class="company__block">
                    <img src="/its-client/img/about-company-5.png" alt="">
                    <div class="company__info">
                        <h2 class="company__name">
                            Забота об <br> окружающей среде
                        </h2>
                        <p class="company__text">
                            Косметика Hipertin не тестируется на животных. Производство бренда сертифицировано в соответствии с европейскими стандартами качества экологической безопасности.
                        </p>
                    </div>
                </div>
            </div>
        @endif
        </div>
    </div>
@endsection
