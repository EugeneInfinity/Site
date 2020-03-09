@extends('admin.app')


@php
    $content_header = [
        'page_title' => 'Настройки магазина',
        'url_back' => '',
        'url_create' => '',
    ]
@endphp

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-6">



                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">Рекомендуемые товары / Бестселлеры</h3>
                    </div>
                    <form action="{{ route('admin.variable.save') }}" method="POST">
                        <input type="hidden" name="destination" value="{{ Request::fullUrl() }}">
                        <div class="box-body">
                            @csrf
                            @include('admin.fields.field-select2-ajax-autocomplete', [
                                'label' => 'Товары',
                                'data_url' => route('admin.products.autocomplete'),
                                'field_name' => 'vars_json[home_page_bestsellers]',
                                'multiple' => 1,
                                'disabled' => 0,
                                // TODO: simple hardcode!!!
                                'selected' => \App\Models\Shop\Product::whereIn('id', json_decode(variable('home_page_bestsellers', '[]')))->get()->pluck('name', 'id')->toArray(),
                                'old' => old('vars_json.home_page_bestsellers')
                            ])

                            <div class="form-group {{ $errors->has('vars.products_is_bestseller_rating') ? 'has-error' : ''}}">
                                <label for="vars.products_is_bestseller_rating">Считать товар "хитом", если его рейтинг больше указанного</label>
                                <input type="number" class="form-control" name="vars[products_is_bestseller_rating]" value="{{ variable('products_is_bestseller_rating') }}">
                                {!! $errors->first('vars.products_is_bestseller_rating', '<p class="help-block">:message</p>') !!}
                            </div>

                        </div>
                        <div class="box-footer">
                            @include('admin.fields.field-form-buttons')
                        </div>
                    </form>
                </div>

                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">Подарочные наборы</h3>
                    </div>
                    <form action="{{ route('admin.variable.save') }}" method="POST">
                        <input type="hidden" name="destination" value="{{ Request::fullUrl() }}">

                        <div class="box-body">

                            @csrf
                            {{--<input type="hidden" name="group" value="prices">--}}
                            @include('admin.fields.field-select2-ajax-autocomplete', [
                                'label' => 'Товары',
                                'data_url' => route('admin.products.autocomplete'),
                                'field_name' => 'vars_json[product_presents]',
                                'multiple' => 1,
                                'disabled' => 0,
                                // TODO: simple hardcode!!!
                                'selected' => \App\Models\Shop\Product::whereIn('id', json_decode(variable('product_presents', '[]')))->get()->pluck('name', 'id')->toArray(),
                                'old' => old('vars_json.product_presents')
                            ])

                            {{--

                            <div class="form-group">
                            <select multiple style="width: 100%" class="form-control select2 field-drag" name="sss[]">
                                <option selected value="1">Kebab</option>
                                <option selected value="3">Pizza</option>
                                <option value="2">Taco</option>
                                <option value="4">Rogn</option>
                                <option value="5" selected>Spaghetti</option>
                                <option value="6" selected>Pølse</option>
                                <option value="7">Rollerburger</option>
                            </select>

                            </div>
                            --}}

                        </div>
                        <div class="box-footer">
                            @include('admin.fields.field-form-buttons')
                        </div>
                    </form>
                </div>


                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">Акция для подписчиков</h3>
                    </div>
                    <form action="{{ route('admin.variable.save') }}" method="POST">
                        <input type="hidden" name="destination" value="{{ Request::fullUrl() }}">

                        <div class="box-body">
                            @csrf
                            {{--<input type="hidden" name="group" value="prices">--}}
                            <input type="hidden" name="destination" value="{{ Request::fullUrl() }}">
                            @include('admin.fields.field-select2-static', [
                                'label' => 'Акция (id)',
                                'field_name' => 'vars[sale_id_for_subscribers]',
                                'multiple' => 0,
                                'max' => 1,
                                'disabled' => 0,
                                'required' => 0,
                                'attributes' => ["" => "---"] + \App\Models\Shop\Sale::whereIn('type', [2,4,5,6])->pluck('name', 'id')->toArray(),
                                'selected' => variable('sale_id_for_subscribers'),
                            ])
                        </div>
                        <div class="box-footer">
                            @include('admin.fields.field-form-buttons')
                        </div>
                    </form>
                </div>

                <div class="box box-default" style="display: none">
                    <div class="box-header with-border">
                        <h3 class="box-title">Доставки заказа</h3>
                    </div>
                    <form action="{{ route('admin.variable.save') }}" method="POST">
                        <div class="box-body">
                            @csrf
                            {{--<input type="hidden" name="group" value="prices">--}}
                            <input type="hidden" name="destination" value="{{ Request::fullUrl() }}">

                            @include('admin.fields.field-links', [
                               'label' => 'Способы доставки',
                               'field_name' => 'vars_json[delivery_methods]',
                               'key_key' => 'key',
                               'key_value' => 'value',
                               'placeholder_key' => 'Ключ',
                               'placeholder_value' => 'Значение',
                               'items' => json_decode(variable('delivery_methods', '[]'), true),
                           ])
                            <hr>
                            @include('admin.fields.field-links', [
                               'label' => 'Цены за способы доставки, руб.',
                               'field_name' => 'vars_json[delivery_methods_price]',
                               'key_key' => 'key',
                               'key_value' => 'value',
                               'placeholder_key' => 'Ключ',
                               'placeholder_value' => 'Цена',
                               'items' => json_decode(variable('delivery_methods_price', '[]'), true),
                           ])
                        </div>
                        <div class="box-footer">
                            @include('admin.fields.field-form-buttons')
                        </div>
                    </form>
                </div>

                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">Способы оплаты заказа</h3>
                    </div>
                    <form action="{{ route('admin.variable.save') }}" method="POST">
                        <div class="box-body">
                            @csrf
                            {{--<input type="hidden" name="group" value="prices">--}}
                            <input type="hidden" name="destination" value="{{ Request::fullUrl() }}">

                            @include('admin.fields.field-links', [
                               'label' => 'Способы оплаты',
                               'field_name' => 'vars_json[payment_methods]',
                               'key_key' => 'key',
                               'key_value' => 'value',
                               'placeholder_key' => 'Ключ',
                               'placeholder_value' => 'Значение',
                               'items' => json_decode(variable('payment_methods', '[]'), true),
                           ])
                        </div>
                        <div class="box-footer">
                            @include('admin.fields.field-form-buttons')
                        </div>
                    </form>
                </div>

                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">Вкладка "Обучение" на странице товара</h3>
                    </div>
                    <form action="{{ route('admin.variable.save') }}" method="POST">
                        <div class="box-body">
                            @csrf
                            <input type="hidden" name="destination" value="{{ Request::fullUrl() }}">

                            <div class="form-group {{ $errors->has('vars.tab_prod_study') ? 'has-error' : ''}}">
                                {!! Form::label('vars[tab_prod_study]', 'Контент', ['class' => 'control-label']) !!}
                                {!! Form::textarea('vars[tab_prod_study]', variable('tab_prod_study'), ['class' => 'form-control ck-editor ck-small', 'rows' => 5]) !!}
                                {!! $errors->first('vars.tab_prod_study', '<p class="help-block">:message</p>') !!}
                            </div>

                        </div>
                        <div class="box-footer">
                            @include('admin.fields.field-form-buttons')
                        </div>
                    </form>
                </div>

            </div>
            <div class="col-md-6">

                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">Информация для типа доставки "Доставка СДЭК"</h3>
                    </div>
                    <form action="{{ route('admin.variable.save') }}" method="POST">
                        <div class="box-body">
                            @csrf
                            <input type="hidden" name="destination" value="{{ Request::fullUrl() }}">

                            <div class="form-group {{ $errors->has('vars.delivery_cdek_door_desc') ? 'has-error' : ''}}">
                                <label for="vars.delivery_cdek_door_desc">Описание Доставка "до двери"</label>
                                <input type="text" class="form-control" name="vars[delivery_cdek_door_desc]" value="{{ variable('delivery_cdek_door_desc', 'Курьер свяжется с вами для уточнения адреса доставки') }}">
                                {!! $errors->first('vars.delivery_cdek_door_desc', '<p class="help-block">:message</p>') !!}
                            </div>

                        </div>
                        <div class="box-footer">
                            @include('admin.fields.field-form-buttons')
                        </div>
                    </form>
                </div>

                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">Информация для типа доставки "Самовывоз из магазина"</h3>
                    </div>
                    <form action="{{ route('admin.variable.save') }}" method="POST">
                        <div class="box-body">
                            @csrf
                            {{--<input type="hidden" name="group" value="prices">--}}
                            <input type="hidden" name="destination" value="{{ Request::fullUrl() }}">


                            <div class="form-group {{ $errors->has('vars.delivery_pickup_address') ? 'has-error' : ''}}">
                                <label for="vars.delivery_pickup_address">Адрес</label>
                                <input type="text" class="form-control" name="vars[delivery_pickup_address]" value="{{ variable('delivery_pickup_address') }}">
                                {!! $errors->first('vars.delivery_pickup_address', '<p class="help-block">:message</p>') !!}
                            </div>
                            <div class="form-group {{ $errors->has('vars.delivery_pickup_phone') ? 'has-error' : ''}}">
                                <label for="vars.delivery_pickup_phone">Телефон</label>
                                <input type="text" class="form-control" name="vars[delivery_pickup_phone]" value="{{ variable('delivery_pickup_phone') }}">
                                {!! $errors->first('vars.delivery_pickup_phone', '<p class="help-block">:message</p>') !!}
                            </div>
                            <div class="form-group {{ $errors->has('vars.delivery_pickup_work') ? 'has-error' : ''}}">
                                <label for="vars.delivery_pickup_work">Режим работы</label>
                                <input type="text" class="form-control" name="vars[delivery_pickup_work]" value="{{ variable('delivery_pickup_work') }}">
                                {!! $errors->first('vars.delivery_pickup_work', '<p class="help-block">:message</p>') !!}
                            </div>

                        </div>
                        <div class="box-footer">
                            @include('admin.fields.field-form-buttons')
                        </div>
                    </form>
                </div>

                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">Информация для типа доставки "Доставка курьером"</h3>
                    </div>
                    <form action="{{ route('admin.variable.save') }}" method="POST">
                        <div class="box-body">
                            @csrf
                            <input type="hidden" name="group" value="delivery_courier">
                            <input type="hidden" name="destination" value="{{ Request::fullUrl() }}">

                            <div class="form-group {{ $errors->has('vars.delivery_courier_price') ? 'has-error' : ''}}">
                                <label for="vars.delivery_courier_price">Цена доставки, руб.</label>
                                <input type="number" class="form-control" step="0.1" required name="vars[delivery_courier_price]" value="{{ variable('delivery_courier_price', 0) / 100 }}">
                                {!! $errors->first('vars.delivery_courier_price', '<p class="help-block">:message</p>') !!}
                            </div>

                            <div class="form-group {{ $errors->has('vars.delivery_courier_desc') ? 'has-error' : ''}}">
                                <label for="vars.delivery_courier_desc">Описание</label>
                                <input type="text" class="form-control" name="vars[delivery_courier_desc]" value="{{ variable('delivery_courier_desc', 'Доставка по Москве и ближайшему Подмосковью (до 10 км от МКАД) - 350 руб.') }}">
                                {!! $errors->first('vars.delivery_courier_desc', '<p class="help-block">:message</p>') !!}
                            </div>

                        </div>
                        <div class="box-footer">
                            @include('admin.fields.field-form-buttons')
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </section>
@endsection

@push('scripts')

@endpush
