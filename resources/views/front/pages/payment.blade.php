@extends('front.layouts.app')

@php
    MetaTag::setEntity($page)->setDefault(['title' => $page->name]);
@endphp

@section('content')
    <div class="politic">

        <div class="card-product__nav">
        {!! Breadcrumbs::render('page', $page) !!}
        </div>

        <div class="payment">
            <div class="payment__wrapper">
                <div class="payment__head">
                    <div class="payment__block payment__block_first">
                        <h1 class="payment__block-name">
                            СТОИМОСТЬ И СРОКИ ДОСТАВКИ
                        </h1>
                        <p class="payment__block-text">* Узнайте точные данные для своего города через поле ниже</p>
                    </div>
                    <div class="payment__block">
                        <div class="payment__block-name">
                            Москва и область
                        </div>
                        <div class="payment__block-info">
                            <p class="payment__block-text">на следующий день</p>
                            <p class="payment__block-price">от 242р</p>
                        </div>
                    </div>
                    <div class="payment__block">
                        <div class="payment__block-name">
                            Санкт-Петербург и область
                        </div>
                        <div class="payment__block-info">
                            <p class="payment__block-text">от 3 дней до 7 дней</p>
                            <p class="payment__block-price">от 307р</p>
                        </div>
                    </div>
                    <div class="payment__block">
                        <div class="payment__block-name">
                            По России и странам СНГ
                        </div>
                        <div class="payment__block-info">
                            <p class="payment__block-text">от 7 дней </p>
                            <p class="payment__block-price">от 307р</p>
                        </div>
                    </div>
                    <div class="payment__block active">
                        <div class="payment__block-active">
                            <div class="payment__block-name">
                                Бесплатная доставка
                            </div>
                            <p class="payment__block-text">Для всех заказов </p>
                        </div>
                        <div class="payment__block-info">
                            <p class="payment__block-price">от 2 307р</p>
                        </div>
                    </div>
                </div>
                <div class="payment__form">
                    <div class="payment__form-name">Выберите ваш город:</div>
                    <form action="#">
                        <input type="text" class="input">
                        <button class="btn-gen">Найти</button>
                    </form>
                </div>
                <div class="delivery">
                    <div class="delivery__wrapper">
                        <div class="delivery__block">
                            <div class="delivery__block-left">
                                <div class="delivery__block-name delivery__block-name_inner">КУРЬЕРСКАЯ ДОСТАВКА</div>
                                <div class="delivery__block-name">СТОИМОСТЬ ДОСТАВКИ:</div>
                                <div class="delivery__block-name">КУРЬЕРСКАЯ КОМПАНИЯ:</div>
                                <div class="delivery__block-name">СРОКИ ДОСТАВКИ:</div>
                                <div class="delivery__block-name">СПОСОБЫ ОПЛАТЫ:</div>
                            </div>
                            <div class="delivery__block-right">
                                <div class="delivery__block-text delivery__block-text_inner">ПУНКТ САМОВЫВОЗА <img src="/its-client/img/location.png" alt=""></div>
                                <div class="delivery__block-text">250руб</div>
                                <div class="delivery__block-text">CDEK</div>
                                <div class="delivery__block-text">3-5 дней</div>
                                <div class="delivery__block-text">Банковской картой, наличными курьеру</div>
                            </div>
                        </div>
                        <div class="delivery__block">
                            <div class="delivery__block-left">
                                <div class="delivery__block-name delivery__block-name_inner">ПОЧТА РОССИИ</div>
                                <div class="delivery__block-name">СТОИМОСТЬ ДОСТАВКИ:</div>
                                <div class="delivery__block-name">КУРЬЕРСКАЯ КОМПАНИЯ:</div>
                                <div class="delivery__block-name">СРОКИ ДОСТАВКИ:</div>
                                <div class="delivery__block-name">СПОСОБЫ ОПЛАТЫ:</div>
                            </div>
                            <div class="delivery__block-right">
                                <div class="delivery__block-text delivery__block-text_inner">Доставка по месту пребывания</div>
                                <div class="delivery__block-text">250руб</div>
                                <div class="delivery__block-text">CDEK</div>
                                <div class="delivery__block-text">3-5 дней</div>
                                <div class="delivery__block-text">Банковской картой, наличными </div>
                            </div>
                        </div>
                    </div>
                    <div class="delivery__text">
                        Бесплатная доставка в любой регион России при заказе от 2000 рублей!
                    </div>
                </div>
            </div>
        </div>
        <div class="politic__wrapper politic__wrapper_payment">
            <div class="politic__name">СПОСОБЫ ОПЛАТЫ</div>
            <p class="small">1) Наличными при получении заказа</p>
            <p class="small">Вы оплачиваете заказ наличными курьеру или в пункте самовывоза. Внимание: максимальная сумма заказа, подлежащего оплате наличными денежными средствами, не может превышать 40 000 рублей.</p>
            <p class="small">2) Банковской картой</p>
            <p class="small">при получении товара у курьера или в пунктах самовывоза (при наличии технической возможности).</p>
            <p class="small">при онлайн оплате на сайте Интернет-магазина.</p>
            <p class="small">Оплата банковской картой на сайте</p>
            <p class="small">Для оплаты товара банковской картой в Интернет-магазине при оформлении заказа укажите способ оплаты «Оплата банковской картой». Оплата осуществляется непосредственно на сайте сразу после оформления заказа. После подтверждения состава заказа, ваших личных данных и адреса доставки откроется страница, где вам будет предложено ввести данные вашей банковской карты (номер карты, ФИО владельца, срок действия карты, CVV/CVC код)*.</p>
            <p class="small">После ввода данных карты вам останется только проверить их и нажать кнопку «Оплатить». Оплата происходит через авторизационный сервер процессингового центра банка с использованием банковских карт следующих платежных систем: VISA, MasterCard, МИР. </p>
            <p class="small">VisaMasterCardМИР <br> Важно! Официальный Интернет-магазин MATRIX не принимает к оплате виртуальные банковские карты. </p>
            <p class="small">Важно! Официальный Интернет-магазин MATRIX ни при каких обстоятельствах не запрашивает у вас PIN-код вашей карты. Никогда не передавайте этот код третьим лицам (в том числе лицам, представляющимся сотрудниками банка).</p>
            <p class="small">* Передача этих сведений производится с соблюдением всех необходимых мер безопасности. Данные будут сообщены только на авторизационный сервер банка по защищенному каналу (протокол SSL 3.0). Информация передается в зашифрованном виде и сохраняется только на специализированном сервере платежной системы. Сайты и магазины не знают и не хранят данные по Вашей пластиковой карте</p>
            <div class="big-text">ВОЗВРАТ ДЕНЕЖНЫХ СРЕДСТВ </div>
            <p class="small">Для возврата денежных средств Покупателю необходимо выслать письменное уведомление на адрес электронной почты Интернет-магазина.</p>
            <p class="small">Возврат денежных средств Покупателю осуществляется следующими способами: </p>
            <p class="small">1) При оплате наличными</p>
            <p class="small">Денежные средства будут возвращены по выбору Покупателя:</p>
            <p class="small">курьером в момент возврата Товара;</p>
            <p class="small">на банковский счет, указанный Покупателем. </p>
            <p class="small">2) При оплате банковской картой</p>
            <p class="small">Возврат денежных средств осуществляется на банковскую карту Покупателя, указанную в заказе.</p>
            <p class="small">Срок перечисления денежных средств составляет 10 дней. Срок зачисления денежных средств на банковский счет Покупателя зависит от внутреннего регламента банка-получателя.</p>
        </div>

        @include('front.blocks.recommend-products', [
          'title' => 'Рекомендуемые товары',
       ])
    </div>
@endsection
