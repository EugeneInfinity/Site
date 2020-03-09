@extends('front.layouts.app')

@php
    MetaTag::setEntity($page)->setDefault(['title' => $page->name]);
@endphp

@section('content')
    <div class="about">
        <div class="about__wrapper">
            @if($page->body)
                {!! $page->body !!}
            @else
            <h1 class="about__name">Здоровье волос и достоинство образа поручают Hipertin!</h1>
            <div class="about__text">Наша цель - полностью удовлетворить клиента и предоставить косметику, которая позволит самостоятельно ухаживать за волосами после посещения салона.</div>
            <div class="about__block">
                <div class="about__block-left">
                    <div class="about__block-wrapper">
                        <img src="/its-client/img/about-1.png" alt="">
                        <p>
                            <span class="about__text">Широкий ассортимент Hipertin позволяет проводить процедуры от окрашивания до дальнейшего ухода. Уже не один десяток лет с нами сотрудничают опытные парикмахеры и стилисты.</span>
                        </p>
                    </div>
                    <div class="about__block-wrapper">
                        <img src="/its-client/img/about-2.png" alt="">
                        <p>
                            <span class="about__text">Наша миссия - развивать индустрию профессиональной косметики для волос и отвечать всем нуждам парикмахеров и их клиентов.</span>
                        </p>
                    </div>
                </div>
                <div class="about__block-right">
                    <div class="about__block-wrapper">
                        <img src="/its-client/img/about-3.png" alt="">
                        <p>
                            <span class="about__text">Нам доверяют, потому что мы тщательно следим за качеством выпускаемой продукции. Это отмечено сертификатами стандартов качества и экологической безопасности производства.</span>
                        </p>
                    </div>
                    <div class="about__block-wrapper">
                        <img src="/its-client/img/about-4.png" alt="">
                        <p>
                            <span class="about__text">Мы не пропускаем новые тенденции, обновляем технологии изготовления, учитываем отзывы и желания клиентов. Так появляются наши продукты, которые становятся решением задач клиентов.</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="about__bottom">
                <div class="about__name"> О фабрике </div>
                <div class="about__text">Фабрика HIPERTIN начала производить средства по уходу за волосами в 1944 году. Сегодня – это ведущая и широко известная марка на профессиональном рынке Испании, которая также представлена во многих странах мира. В настоящее время мы совместно участвуем в коммерческих проектах с партнерами из Португалии, Франции, Бельгии, Германии, России, Мексики, Бразилии, США, Израиля, Турции, Сальвадора, Пуэрто-Рико, Китая и других государств. Предприятие, расположенное недалеко от Барселоны, отвечает всем современным требованиям, предъявляемым к производству, и сертифицировано в соответствии со стандартами ISO 9001 (Стандарт системы менеджмента качества) и ISO 14001 (Стандарт качества экологической безопасности производства).</div>
            </div>
            <div class="about__img">
                <img src="/its-client/img/about-img-1.png" alt="">
                <img src="/its-client/img/about-img-2.png" alt="">
            </div>
            @endif
        </div>

    </div>
@endsection
