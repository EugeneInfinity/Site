{{--<div class="g-wrapper-basket">--}}

@if($cart['products']->count())
    <div class="g-wrapper-basket__left">
        <h1 class="g-left__title"> Ваша корзина</h1>
        {{-- Товары в корзине --}}
        @foreach($cart['products'] as $product)
            <div class="unit-basket">
                <div class="unit-basket__left">
                    <a href="{{ route_alias('product.show', $product) }}">
                        <img src="{{ $product->getFirstMediaUrl('images', 'cart-page') ?: '/its-client/img/product-image.png' }}" alt=""> {{-- TODO make image --}}
                    </a>
                </div>
                <div class="unit-basket__right">
                    <div class="top-line">
                        <div class="top-line__title">
                            <h4 class="content-title">  {{ $product->name }}</h4>
                        </div>
                        <button class="js-remove-product js-ajax-cart-form-submit" data-url="{{ route('shopping-cart.form') }}" data-html-container="#cart-page-content"> <svg class="icon-svg icon-svg-close "><use xlink:href="/its-client/img/sprite.svg#close"></use></svg> </button>
                    </div>
                    <div class="middle">
                        <p>{{ str_limit(strip_tags($product->description), 50) }}</p>
                    </div>
                    <div class="bottom-line">
                        <div class="bottom-line__left">
                            <span class="bottom-line__left-one">{{ $product->valuesStr() }}</span>
                            <span class="bottom-line__left-two">{{ Currency::format($product->getCalculatePrice('price'), $product->currency) }}</span>
                            {{-- цена со скидкой --}}
                            {{-- {{ Currency::format($cart['product_discounts'][$product->id] ?? 0) }} --}}
                        </div>
                        <div class="bottom-line__right">
                            <button class="bottom-line__right-btn js-set-amount js-ajax-cart-form-submit cart-dec" data-addition="-1" data-url="{{ route('shopping-cart.form') }}" data-html-container="#cart-page-content">-</button>
                            <input type="text" name="products[{{$product->id}}][amount]" class="input amount-product" value="{{ $cart['product_counts'][$product->id] }}" readonly>
                            <button class="bottom-line__right-btn js-set-amount js-ajax-cart-form-submit cart-inc" data-addition="1" data-url="{{ route('shopping-cart.form') }}" data-html-container="#cart-page-content">+</button>
                        </div>
                        <div class="bottom-line__right-mobile">
                            <button class="bottom-line__right-btn js-amount-cart-dec">-</button>
                            <span>{{ $cart['product_counts'][$product->id] }}</span>
                            <button class="bottom-line__right-btn js-amount-cart-inc">+</button>
                            {{--<img src="/its-client/img/down.png" class="js-amount-cart-inc">--}}
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        {{-- Промокод --}}
        <div class="wrapper-apply form-group">
            <input type="input"
                   class="input"
                   name="cart[promocode]"
                   value="{{ session()->get('cart.promocode') }}"
                   placeholder="Промокод"
            >
            <button class="btn-gen js-ajax-cart-form-submit"
                    data-html-container="#cart-page-content"
                    data-url="{{ route('shopping-cart.form') }}"
                    name="purpose"
                    value="promocode"
            ><span>Применить</span>
                <img src="/its-client/img/good.png" alt="">
            </button>
            <div class="promo-block">
                @if(session()->get('cart.promocode'))
                <span>{{ session()->get('cart.promocode') }}</span>
                <button class="js-ajax-cart-form-submit"
                        data-html-container="#cart-page-content"
                        data-url="{{ route('shopping-cart.form', ['remove_promocode' => 1]) }}"
                        {{--name="purpose"--}}
                >×</button>
                @endif
                @if(session()->get('cart.promocode_info'))
                    <p>{!! session()->get('cart.promocode_info')  !!}</p>
                @endif
            </div>
        </div>
    </div>


    <div class="g-wrapper-basket__right">
        {{-- Шаг 1.Имя, телефон, email --}}
        <div class="top-block-right">
            <div class="name-input-block__wrapper">
                <h3 class="name-input-block"> Оформление заказа</h3>
                <p class="mob-item">Шаг 1</p>
            </div>
            <div class="input-block">
                @auth
                    <div class="input-group form-group">
                        <p class="input-group__name">Телефон:*</p>
                        <input class="input phone1" type="input"
                               name="data[delivery][phone]"
                               value="{{ request('data.delivery.phone', auth()->user()->phone) }}"
                               data-mask="{{ config('services.global.phone.input_mask') }}"
                               placeholder="{{ config('services.global.phone.input_placeholder') }}"
                        >
                    </div>
                    <div class="input-group form-group">
                        <p class="input-group__name">E-mail:*</p>
                        <input class="input" type="email" name="data[delivery][email]" value="{{ request('data.delivery.email', auth()->user()->email) }}">
                    </div>
                    <div class="input-group form-group">
                        <p class="input-group__name">Имя:*</p>
                        <input class="input" type="input" name="data[delivery][name]" value="{{ request('data.delivery.name', auth()->user()->full_name) }}">
                    </div>
                @else
                    <div class="input-group form-group">
                        <p class="input-group__name">Телефон:*</p>
                        <input class="input phone1" type="input"
                               name="data[delivery][phone]"
                               value="{{ request('data.delivery.phone') }}"
                               data-mask="{{ config('services.global.phone.input_mask') }}"
                               placeholder="{{ config('services.global.phone.input_placeholder') }}"
                        >
                    </div>
                    <div class="input-group form-group">
                        <p class="input-group__name">E-mail:*</p>
                        <input class="input" type="email" name="data[delivery][email]" value="{{ request('data.delivery.email') }}">
                    </div>
                    <div class="input-group form-group">
                        <p class="input-group__name">Имя:*</p>
                        <input class="input" type="input" name="data[delivery][name]" value="{{ request('data.delivery.name') }}">
                    </div>
                @endauth
            </div>
        </div>

        <div class="middle-block-right">

            {{-- Выбор Города доставки --}}
            <h5 class="delivery-title">Город доставки:</h5>
            <div class="form-group form-group_select">
                <div class="form-group">
                    <select name="data[delivery][city_id]"
                            class="css-select2 js-search-city"
                            data-ajax-url="{{ route('cdek.cities') }}"
                            data-placeholder="Выберите город"
                    >
                        <option value="{{ request('data.delivery.city_id', 44) }}" selected>{{ request('data.delivery.city', 'Москва') }}</option>
                    </select>
                    <input type="hidden" id="cdek-city-name" name="data[delivery][city]" value="{{ request('data.delivery.city', 'Москва') }}">
                    <input type="hidden" name="data[delivery][tariff_zone]" value="{{ request('data.delivery.tariff_zone', 1) }}">
                </div>
            </div>

            {{-- Выбор метода доставки --}}
            <div class="button-line">
                <button class="btn-gen js-select-delivery-method @if(request('data.delivery.method', 'cdek') == 'cdek')) active @endif"
                        value="cdek"
                        data-html-container="#cart-page-content"
                >
                    <span class="btn-gen__top-span">Доставка СДЭК</span>
                </button>
                @if(request('data.delivery.city_id', 44) == 44) {{-- Только для Москвы --}}
                <button class="btn-gen js-select-delivery-method @if(request('data.delivery.method') == 'pickup') active @endif"
                        value="pickup"
                        data-html-container="#cart-page-content"
                >
                    <span class="btn-gen__top-span"> Самовывоз</span>
                </button>
                {{--
                <button class="btn-gen js-select-delivery-method @if(request('data.delivery.method') == 'courier') active @endif"
                        value="courier"
                        data-html-container="#cart-page-content"
                >
                    <span class="btn-gen__top-span">Доставка курьером</span>
                </button>
                    --}}
                @endif
                <input type="hidden" name="data[delivery][method]" value="{{ request('data.delivery.method', 'cdek') }}">
            </div>

            {{-- Тип доставки СДЕК --}}
            @if(request('data.delivery.method', 'cdek') == 'cdek')
                @isset($delivery['cdek_pwz'])
                    <div class="middle-block-right__top-line">
                        <div class="name-input-block__wrapper">
                            <h5 class="delivery-title"> Доставка</h5>
                            <p class="mob-item"><img src="/its-client/img/mob-left.png" alt=""> Шаг 2</p>
                        </div>
                        <label>
                            <input class="checkbox js-select-tariff" type="radio" value="136" name="data[delivery][tariff]"
                               @if(request('data.delivery.tariff', 136) == 136) checked @endif
                            >
                            <span class="checkbox-custom"></span>
                            @isset($delivery['cdek_calculate'][136]['response'])
                                <span class="label">{{ cdek_str_delivery_info($delivery['cdek_calculate'][136]['response'], 'Доставка до пункта самовывоза', Currency::format($purchase['delivery'], 'RUB'), ['city_id' => request('data.delivery.city_id', 44)]) }}</span>
                            @else
                                <span class="label">Доставка до пункта самовывоза</span>
                            @endisset
                        </label>
                        {{-- TODO: За Урал, Крим --}}
                        @isset($delivery['cdek_calculate']['ekonom'])
                        <label>
                            <input class="checkbox js-select-tariff" type="radio" value="234" name="data[delivery][tariff]"
                                   @if(request('data.delivery.tariff', 136) == 234) checked @endif
                            >
                            <span class="checkbox-custom"></span>

                            @isset($delivery['cdek_calculate'][234]['response'])
                                <span class="label">{{ cdek_str_delivery_info($delivery['cdek_calculate'][234]['response'], 'Экономичная доставка до пункта самовывоза', Currency::format($purchase['delivery'], 'RUB'), ['city_id' => request('data.delivery.city_id', 44)]) }}</span>
                            @else
                                <span class="label">Экономичная доставка до пункта самовывоза</span>
                            @endisset
                        </label>
                        @endif
                    </div>

                    <div class="text-block">
                        <div class="hour-work">
                            <p class="hour-work__text"><span class="hour-work__text-left">Адрес:</span><span class="hour-work__text-right">{{ $delivery['cdek_pwz']->Address }}</span></p>
                            <p class="hour-work__text"><span class="hour-work__text-left">Телефон:</span><span class="hour-work__text-right">{{ $delivery['cdek_pwz']->Phone }}</span></p>
                            <p class="hour-work__text"><span class="hour-work__text-left">Режим работы:</span><span class="hour-work__text-right">{{ $delivery['cdek_pwz']->WorkTime }}</span></p>
                        </div>
                        <button class="btn-gen js-show-modal-pwz"
                                data-url="{{ route('cart.cdek.pwz', request('data.delivery.city_id', 44)) }}"
                        >Выбрать другой пункт выдачи
                        </button>
                        <input type="hidden" name="data[delivery][pwz_code]" value="{{ $delivery['cdek_pwz']->Code }}" id="pwz_code">
                        <input type="hidden" name="data[delivery][address]" value="{{ $delivery['cdek_pwz']->FullAddress }}">
                    </div>
                @else
                    <div class="text-block">
                    <div class="middle-block-right__top-line">
                        <div class="name-input-block__wrapper">
                            <h5 class="delivery-title"> Доставка СДЕК по указаному городу невозможна! Обратитесь к Администрации!</h5>
                            <p class="mob-item"><img src="/its-client/img/mob-left.png" alt=""> Шаг 2</p>
                        </div>
                    </div>
                    </div>
                @endisset

            {{-- Тип доставки "Самовывоз" --}}
            @elseif(request('data.delivery.method', 'cdek') == 'pickup')
                <div class="middle-block-right__top-line">
                    <div class="name-input-block__wrapper">
                        <h5 class="delivery-title"> Самовывоз из магазина:</h5>
                        <p class="mob-item"><img src="/its-client/img/mob-left.png" alt=""> Шаг 2</p>
                    </div>
                </div>
                <div class="text-block">
                    <div class="hour-work">
                        @if($address = variable('delivery_pickup_address'))
                        <p class="hour-work__text"><span class="hour-work__text-left">Адрес:</span><span class="hour-work__text-right info-address">{{ $address }} </span></p>
                        @endif
                        @if($phone = variable('delivery_pickup_phone'))
                        <p class="hour-work__text"><span class="hour-work__text-left">Телефон:</span><span class="hour-work__text-right info-phone">{{ $phone }}</span></p>
                        @endif
                        @if($workTime = variable('delivery_pickup_work'))
                        <p class="hour-work__text"><span class="hour-work__text-left">Режим работы:</span><span class="hour-work__text-right info-work-time">{{ $workTime }}</span></p>
                        @endif
                    </div>
                </div>

            {{-- Тип доставки "Доставка курьером" --}}
            @elseif(request('data.delivery.method', 'cdek') == 'courier')
                <div class="middle-block-right__top-line">
                    <div class="name-input-block__wrapper">
                        <h5 class="delivery-title">Адрес доставки для курьера:*</h5>
                    </div>
                    @if(Auth::check() && Auth::user()->contacts->count())
                    <div class="form-group form-group_select">
                        <div class="form-group">
                            <select name="contact_id" class="select-basket js-select-contact" data-minimum-results-for-search="-1">
                                <option value="0" @if(request('contact_id', Auth::user()->contact_id) == 0) selected @endif>Указать новый</option>
                                 @foreach(Auth::user()->contacts as $contact)
                                 <option value="{{ $contact->id }}" @if(Auth::user()->contact_id == $contact->id && request('contact_id', Auth::user()->contact_id)) selected @endif>{{ $contact->full }}</option>
                                 @endforeach
                            </select>
                        </div>
                    </div>
                    @else
                        <input type="hidden" name="contact_id" value="0" class="js-select-contact">
                    @endif
                </div>
                <br>
                <div class="new-input__block user-contacts-block" @if(request('contact_id', Auth::check() ? Auth::user()->contact_id : null)) style="display: none" @endif>
                    <div class="new-input-block">
                        <div class="new-input__group">
                            <div class="input-group form-group">
                                <p class="input-group__name">Город</p>
                                <input class="input" placeholder="" type="input" name="data[delivery][city]" value="{{ request('data.delivery.city') }}">
                            </div>
                            <div class="input-group form-group">
                                <p class="input-group__name">Индекс</p>
                                <input class="input" placeholder="" type="input" name="data[delivery][zip_code]" value="{{ request('data.delivery.zip_code') }}">
                            </div>
                        </div>
                        <div class="new-input__group">
                            <div class="input-group form-group">
                                <p class="input-group__name">Регион, область</p>
                                <input class="input" placeholder="" type="input" name="data[delivery][region]" value="{{ request('data.delivery.region') }}">
                            </div>
                            <div class="input-group form-group">
                                <p class="input-group__name">Адрес</p>
                                <input class="input" placeholder="" type="input" name="data[delivery][address]" value="{{ request('data.delivery.address') }}">
                            </div>
                        </div>
                    </div>
                </div>
                @if($desc = variable('delivery_courier_desc'))
                    <p>{!! $desc !!}</p>
                @endif
            @endif
        </div>

        @if(request('data.delivery.method', 'cdek') == 'cdek')
            @isset($delivery['cdek_pwz'])
                @if(($delivery['cdek_pwz']->Type == 'PVZ'))
                <div class="bottom-block-right">
                    <div class="bottom-block-right__top-line">
                        <label>
                            <input class="checkbox js-select-tariff" type="radio" value="137" name="data[delivery][tariff]"
                               @if(request('data.delivery.tariff', 136) == 137) checked @endif
                            >
                            <span class="checkbox-custom"></span>
                            @isset($delivery['cdek_calculate'][137]['response'])
                                <span class="label">{{ cdek_str_delivery_info($delivery['cdek_calculate'][137]['response'], 'Доставка "до двери"', Currency::format($purchase['delivery'], 'RUB'), ['city_id' => request('data.delivery.city_id', 44)]) }}</span>
                            @else
                                <span class="label">Доставка "до двери"</span>
                            @endisset
                        </label>

                        {{-- TODO: За Урал, Крим --}}
                        @isset($delivery['cdek_calculate']['ekonom'])

                        <br>
                        <label>
                            <input class="checkbox js-select-tariff" type="radio" value="233" name="data[delivery][tariff]"
                                   @if(request('data.delivery.tariff', 136) == 233) checked @endif
                            >
                            <span class="checkbox-custom"></span>
                            @isset($delivery['cdek_calculate'][233]['response'])
                                <span class="label">{{ cdek_str_delivery_info($delivery['cdek_calculate'][233]['response'], 'Экономичная доставка "до двери"', Currency::format($purchase['delivery'], 'RUB', ['city_id' => request('data.delivery.city_id', 44)])) }}</span>
                            @else
                                <span class="label">Экономичная доставка "до двери"</span>
                            @endisset
                        </label>
                        @endif
                        <p>{!! variable('delivery_cdek_door_desc') !!}</p>

                    </div>
                </div>
                @endif
            @endisset
        @endif
        {{--<input type="hidden" name="data[delivery][price]" value="{{ $purchase['delivery'] ?? 0 }}">--}}

        <div class="bottom-block-right">
            <div class="bottom-block-right__content">
                <div class="name-input-block__wrapper">
                    <h5 class="delivery-title">Оплата:* </h5>
                    <p class="mob-item"><img src="/its-client/img/mob-left.png" alt="">Шаг 3</p>
                </div>
                <div class="form-group form-group_select">
                    <div class="form-group">
                        <select class="select-basket js-select-payment-method"
                                name="data[payment][method]"
                                data-minimum-results-for-search="-1">
                            <option value="">Укажите способ оплаты</option>
                            {{-- Если покупатель выбрал г. Москва и самовывоз, то для способа оплаты оставить только банковский перевод. --}}
                            @if(request('data.delivery.city_id', 44) == 44 && request('data.delivery.method', 'cdek') == 'pickup')
                                <option value="yandex" @if(request('data.payment.method') == 'yandex') selected @endif>Банковский перевод</option>
                            {{-- Если покупатель выбрал город из зон 2-7 (любой город кроме Москвы) и способ доставки «до двери», то для способа оплаты оставить только банковский перевод. --}}
                            @elseif(in_array(request('data.delivery.tariff_zone', 1), [2,3,4,5,6,7]) && (request('data.delivery.method', 'cdek') == 'cdek') && (request('data.delivery.tariff', 136) == 137))
                            {{-- Если покупатель выбрал города из зоны 6 или 7, то для способа оплаты оставить только банковский перевод --}}
                                <option value="yandex" @if(request('data.payment.method') == 'yandex') selected @endif>Банковский перевод</option>
                            @elseif(in_array(request('data.delivery.tariff_zone', 1), [6, 7]))
                                <option value="yandex" @if(request('data.payment.method') == 'yandex') selected @endif>Банковский перевод</option>
                            @else
                                @foreach(json_decode(variable('payment_methods', '[]'), true) as $item)
                                    {{--<option value="{{ $item['key'] ?? '' }}" @if(request('data.payment.method', 'yandex') == ($item['key'] ?? '')) selected @endif>{{ $item['value'] ?? '' }}</option>--}}
                                    <option value="{{ $item['key'] ?? '' }}" @if(request('data.payment.method') == ($item['key'] ?? '')) selected @endif>{{ $item['value'] ?? '' }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <input type="hidden" name="data[delivery][price]" value="{{ $purchase['delivery'] }}">
                <p class="hour-work__text"><span class="hour-work__text-left">Товары</span><span class="hour-work__text-right">{{ Currency::format($purchase['products'], 'RUB') }}</span></p>
                <p class="hour-work__text"><span class="hour-work__text-left">Доставка</span><span class="hour-work__text-right">{{ Currency::format($purchase['delivery'], 'RUB') }}</span></p>
                <p class="hour-work__text"><span class="hour-work__text-left">Скидка</span><span class="hour-work__text-right">{{ Currency::format($purchase['discount'], 'RUB') }}</span></p>
                <p class="hour-work__text all"><span class="hour-work__text-left">Итого:</span><span class="hour-work__text-right">{{ Currency::format($purchase['total'], 'RUB') }}</span></p>
                <div class="button-hour">
                    <input type="hidden"
                           class="js-ajax-cart-form-submit"
                           id="reload-cart-page"
                           data-html-container="#cart-page-content"
                           data-url="{{ route('shopping-cart.form') }}"
                    >
                    <button class="btn-gen"
                            type="submit"
                    >Подтвердить заказ</button>
                    <div class="loader-block btn-ajax-loader" style="display: none">
                        <div class="loader">
                        </div>
                    </div>
                    <p>Нажимая на кнопку, вы соглашаетесь с <a href="/policy" target="_blank">
                            правилами обработки  </a>
                        персональных данных
                    </p>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="g-wrapper-basket__left">
        <h2 class="g-left__title"> Ваша корзина пуста</h2>
    </div>
@endif


{{--</div>--}}

@push('scripts')
    <!-------------------------------- Map ------------------------------->
    <script>
        function initCartSelect2() {
            //----------------------------- Выбор города доставки ------------------------------------//
            $('.js-search-city').select2({
                dropdownCssClass: 'select-basket-dropdown',
                language: {
                    noResults: function (params) {
                        return "Не найдено";
                    }
                }
            }).on('select2:select', function (event) {
                var selected = event.params.data
                console.log(selected.tariff_zone)
                $('input#cdek-city-name').val(selected.text)
                $('input[name="data[delivery][tariff_zone]"]').val(selected.tariff_zone)
                $('input[name="data[delivery][pwz_code]"]').val('')
                $('input[name="data[delivery][method]"]').val('cdek')
                $('#reload-cart-page').click()
            });
        }
        initCartSelect2()

        //------------------------- Выбор метода доставки ---------------------//
        $(document).on('click', 'button.js-select-delivery-method', function (e) {
            e.preventDefault()
            $('input[name="data[delivery][method]"]').val($(this).val())
            $('#reload-cart-page').click()
        })

        //------------------------- Выбор метода оплаты ---------------------//
        $(document).on('change', 'select.js-select-payment-method', function () {
            $('#reload-cart-page').click()
        })

        $(document).on('click', '.js-amount-cart-inc', function (e) {
            e.preventDefault()
            $(this).closest('.bottom-line').find('.js-set-amount.cart-inc').click()
        })
        $(document).on('click', '.js-amount-cart-dec', function (e) {
            e.preventDefault()
            $(this).closest('.bottom-line').find('.js-set-amount.cart-dec').click()
        })


        //---------------------------- Yandex map ----------------------------//
        var myMap,
            dataAdresses

        function initMap ()
        {
            if (dataAdresses !== undefined) {
                myMap = new ymaps.Map('location-map', {
                    center: dataAdresses.features[0].geometry.coordinates || [55.76, 37.64],
                    zoom: 10
                }, {
                    searchControlProvider: 'yandex#search'
                }),
                    objectManager = new ymaps.ObjectManager({
                        clusterize: true,
                        gridSize: 32
                    })


                objectManager.objects.options.set('preset', 'islands#greenDotIcon')
                objectManager.clusters.options.set('preset', 'islands#greenClusterIcons')
                myMap.geoObjects.add(objectManager)
                objectManager.add(dataAdresses)
            }
        }
        function setAddress(code, name) {
            var element = document.getElementById('location-input'),
            inputNone = document.getElementById("pwz_code"),
                event = new Event('insert:text')

            element.value = name
            inputNone.value = code
            element.dispatchEvent(event)

            $('#reload-cart-page').click()
            $('#basket-location').modal('hide')
        }



        //--------------------- Показать модалку выбора отделений СДЕК доставки для id-города ----------------------//
        $(document).on('click', '.js-show-modal-pwz', function (e) {
            e.preventDefault()
            var url = $(this).data('url')
            $.ajax({
                url: url,
                method: "GET",
                dataType: "json",
                success: function (result) {
                    console.log('Success Ajax!')
                    $('#basket-location').find('.pwz-list').html(result.html)
                    $('#basket-location').modal('show')

                    dataAdresses = result.coordinates
                    if(myMap !== undefined) {
                        myMap.destroy();
                    }
                    ymaps.ready(initMap);
                }
            })
        })

        //----------------------- Выбор отделения CDEK -------------------------------//
        $(document).on('click', '.pwz-list a', function (e) {
            e.preventDefault()
            $('#pwz_code').val($(this).data('code'))
            $('#reload-cart-page').click()
            $('#basket-location').modal('hide')
        })

        //-------------------- Выбор тарифа CDEK доставки ---------------------------------//
        $(document).on('change', '.js-select-tariff', function () {
            $('#reload-cart-page').click()
        })

        //------------------------- Выбор существующих контактных данных юзера ---------------------//
        $(document).on('change', '.js-select-contact', function () {
            if ($(this).val() != 0 ) {
                $('.user-contacts-block').hide()
            } else {
                $('.user-contacts-block').show()
            }
        })


        $(document).on('addcart', function (e) {
            $('#reload-cart-page').click()
            console.dir(e)
        })

        //------------------------- Увеличить/уменьшить к-ство товара в корзине -------------------//
        $(document).on('click', '.js-set-amount', function () {
            $this = $(this),
            $addition = $this.data('addition'),
            $input = $this.siblings('input')
            $input.val(parseInt($input.val()) + parseInt($addition))
        })

        //-------------------------------- Удалелить товар с корзины ----------------------------------//
        $(document).on('click', '.js-remove-product', function () {
            $(this).closest('.unit-basket').find('.amount-product').val(0)
            $(this).closest('.unit-basket').hide()
        })

        /**
         * TODO: унификовать!
         * Отправка/обновление формы данных корзины.
         */
        $(document).on('click', '.js-ajax-cart-form-submit', function (e) {
            e.preventDefault()

            var $this = $(this),
                $form = $this.closest('form'),//$('#cart-form'),
                formData = $form.serializeArray(),
                method = $this.data('method') || $form.attr('method') || 'POST',
                url = $this.data('url') || $form.attr('action'),
                htmlContainer = $this.data('html-container') || $form.data('html-container'),
                errorClass = 'error',
                seoAction = $this.data("seo-action"),
                seoLabel = $this.data('seo-label');

            if (this.name) {
                formData.push({ name: this.name, value: this.value });
            }

            $.ajax({
                url: url,
                method: method,
                dataType: 'json',
                data: formData,
                beforeSend: function() {
                    // Remove all p.error & .error on form
                    $form.find('p.' + errorClass).remove()
                    $form.find('.' + errorClass).removeClass(errorClass)
                },
                success: function(result) {
                    console.log('Success Ajax!')

                    if (seoAction) {
                        seoActionHandle(seoAction, seoLabel)
                    }

                    if (result && result.html && htmlContainer) {
                        $(htmlContainer).html(result.html)
                    }

                    if (result && result.message) {
                        console.log(result.message)
                        toastr.success(result.message)
                    }

                    if (result && result.action) {
                        switch (result.action) {
                            case 'redirect':
                                window.location = result.destination
                                break
                            case 'reset':
                                $form[0].reset()
                                break
                        }
                    }

                    // Custom Hard-code!!!
                    if (result && result.htmlHeaderCart != undefined) {
                        $('#product-cart').html(result.htmlHeaderCart)
                    }
                    //updateInfoHeaderProd()
                    var prodAmountInFavorite = $('#product-favorite .number').text(),
                        prodAmountInCart = $('#product-cart .number').text()
                    $('.amount-favorites').text(prodAmountInFavorite)
                    $('.amount-cart-products').text(prodAmountInCart)

                    $('.select-basket, .select2').select2({
                        dropdownCssClass: 'select-basket-dropdown',
                    })
                    //visibleContactBlock($('.js-select-contact').val())
                    initCartSelect2()
                    reInitJQueryMask()
                },
                error: function(result) {
                    console.log('Error Ajax!')
                    var response = result.responseJSON;

                    /**
                     * You can set next options:
                     * data-validator-options='{"related_selectors":["[name=last_name]","[name=phone]"], "disable_msg":1}'
                     */
                    if (response && response.errors !== undefined) {
                        $.each(response.errors, function (key, value) {

                            // Replace backend key for frontend.
                            // Example: article.terms.category => article[terms][category]
                            var fieldName = key.replace(/\.|$/g, '][').replace(/]/, '').replace(/\[$/,''),

                                // Field that has an error (by name, except type="hidden").
                                //$fieldWithError = $form.first('[name="' + fieldName + '"]:not([type="hidden"])'),
                                $fieldWithError = $form.find('[name="' + fieldName + '"]:not([type="hidden"])'),

                                // TODO  check is JSON (https://codeblogmoney.com/validate-json-string-using-javascript/)
                                $fieldValidOptions = $fieldWithError.data('validator-options')

                            // Added error class for element with the desired "fieldName".
                            $fieldWithError.addClass(errorClass);

                            // Add class error to related tags.
                            if ($fieldValidOptions && $fieldValidOptions.related_selectors) {
                                $.each($fieldValidOptions.related_selectors, function (i, item) {
                                    $form.find(item).addClass(errorClass);
                                })
                            }

                            // TODO custom: add error class for related field name!
                            if (fieldName == 'password') {
                                $form.find('[name="password_confirmation"]').addClass(errorClass);
                            }

                            // Show error messages.
                            value.forEach(function (item, /*i, value*/) {
                                console.log(key, item)
                                // replace "name" => "first name"
                                var key_ = key.replace("_", " "),
                                    // replace (delete field name!)
                                    errorText = ('<p class="error">'+item+'</p>').replace(key_, "").replace(key, "")

                                if ($fieldValidOptions && $fieldValidOptions.disable_msg) {
                                } else {
                                    $fieldWithError.closest('.form-group').append(errorText)
                                }
                            });

                        });
                    }
                },
                complete: function () {
                    //...
                }
            })
        })

        function reInitJQueryMask() {
            $('input[data-mask]').each(function() {
                var input = $(this),
                    options = {
                        translation: {
                            '_': {
                                pattern: /[0-9]/,
                                fallback: ''
                            }
                        },
                        placeholder: "+7 (___) __-__-__",
                        selectOnFocus: true
                    };

                if (input.attr('data-mask-reverse') === 'true') {
                    options['reverse'] = true;
                }

                if (input.attr('data-mask-maxlength') === 'false') {
                    options['maxlength'] = false;
                }

                input.mask(input.attr('data-mask'), options);
            });
        }

        $(document).keypress(
        function(event){
            if (event.which == 13) {
                event.preventDefault();
            }
        });

    </script>

@endpush

@push('modals')
    <div class="modal modal-request modal-request_location modal-request_news fade" id="basket-location" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="modal-body">
                    <div class="request">
                        <div class="request__wrapper">
                            <div class="request__line">
                                Выберите адрес
                            </div>
                            <div class="location">
                                <div class="location__wrapper">
                                    <form action="#">
                                        <div class="form-group">
                                            <input id="location-input" type="text" class="input" placeholder="Выберите адрес">
                                            <button class="location-close" type="reset" >
                                                <span>&times;</span>
                                            </button>
                                        </div>
                                    </form>
                                    <div class="location-list pwz-list">
                                        <ul>
                                            <li><a href="#">Тест, Таганрогская (ул. Таганрогская, 11 корп. 3)</a></li>
                                            <li><a href="#">Тест, Весенняя (ул. Весенняя, 3, корп. 1)</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="location-map">
                                    <div id="location-map" data-page="contacts" style=" width: 100%; height: 100%; padding: 0; margin: 0;">
                                    </div>
                                </div>
                            </div>
                            <input id="input-none" type="text" class="none" style="display: none">
                        </div>
                    </div>
                </div>
                <button type="button" class="close-loc btn-gen" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">Отмена</span>
                </button>
            </div>
        </div>
    </div>
@endpush