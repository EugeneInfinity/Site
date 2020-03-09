@extends('admin.app')


@php
    $content_header = [
        'page_title' => 'Настройки внешних API-сервисов',
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
                        <i class="fa fa-taxi"></i>
                        <h3 class="box-title">Доставка "CDEK"</h3>
                    </div>
                    <form action="{{ route('admin.variable.save') }}" method="POST">
                        <div class="box-body">
                            @csrf
                            {{--<input type="hidden" name="group" value="prices">--}}
                            <input type="hidden" name="destination" value="{{ Request::fullUrl() }}">
                            <div class="form-group {{ $errors->has('vars.cdek_account') ? 'has-error' : ''}}">
                                <label for="vars.cdek_account">СДЭК-акаунт</label>
                                <input type="text" class="form-control" name="vars[cdek_account]" value="{{ variable('cdek_account') }}">
                                {!! $errors->first('vars.cdek_account', '<p class="help-block">:message</p>') !!}
                            </div>
                            <div class="form-group {{ $errors->has('vars.cdek_password') ? 'has-error' : ''}}">
                                <label for="vars.cdek_password">СДЭК-пароль</label>
                                <input type="text" class="form-control" name="vars[cdek_password]" value="{{ variable('cdek_password') }}">
                                {!! $errors->first('vars.cdek_password', '<p class="help-block">:message</p>') !!}
                            </div>

                            @include('admin.fields.field-select2-ajax-autocomplete', [
                               'label' => 'ID города отправителя (SenderCityId - из базы СДЭК)',
                               'field_name' => 'vars[cdek_sender_city_id]',
                               'data_url' => route('cdek.cities'),
                               'selected' => variable('cdek_sender_city_id'),
                           ])

                            {!! link_to('https://confluence.cdek.ru/pages/viewpage.action?pageId=15616129', null, ['target' => '_blank']) !!}
                        </div>
                        <div class="box-footer">
                            @include('admin.fields.field-form-buttons')
                        </div>
                    </form>
                </div>

                <div class="box box-default">
                    <div class="box-header with-border">
                        <i class="fa fa-bold"></i>
                        <h3 class="box-title">API "Bitrix24"</h3>
                    </div>
                    <form action="{{ route('admin.variable.save') }}" method="POST">
                        <div class="box-body">
                            @csrf
                            {{--<input type="hidden" name="group" value="prices">--}}
                            <input type="hidden" name="destination" value="{{ Request::fullUrl() }}">
                            <div class="form-group {{ $errors->has('vars.bitrix24_host') ? 'has-error' : ''}}">
                                <label for="vars.bitrix24_host">Хост</label>
                                <input type="url" class="form-control" name="vars[bitrix24_host]" value="{{ variable('bitrix24_host') }}">
                                {!! $errors->first('vars.bitrix24_host', '<p class="help-block">:message</p>') !!}
                            </div>
                            <div class="form-group {{ $errors->has('vars.bitrix24_user') ? 'has-error' : ''}}">
                                <label for="vars.bitrix24_user">Пользователь</label>
                                <input type="number" class="form-control" name="vars[bitrix24_user]" value="{{ variable('bitrix24_user') }}">
                                {!! $errors->first('vars.bitrix24_user', '<p class="help-block">:message</p>') !!}
                            </div>
                            <div class="form-group {{ $errors->has('vars.bitrix24_hook_code') ? 'has-error' : ''}}">
                                <label for="vars.bitrix24_hook_code">Код веб-хука</label>
                                <input type="text" class="form-control" name="vars[bitrix24_hook_code]" value="{{ variable('bitrix24_hook_code') }}">
                                {!! $errors->first('vars.bitrix24_hook_code', '<p class="help-block">:message</p>') !!}
                            </div>

                            {!! link_to('https://helpdesk.bitrix24.ru/open/5408147/', null, ['target' => '_blank']) !!}
                        </div>
                        <div class="box-footer">
                            @include('admin.fields.field-form-buttons')
                        </div>
                    </form>
                </div>

                <div class="box box-default">
                    <div class="box-header with-border">
                        <i class="fa fa-google"></i>
                        <h3 class="box-title">Google reCaptcha (v2)</h3>
                    </div>
                    <form action="{{ route('admin.variable.save') }}" method="POST">
                        <div class="box-body">
                            @csrf
                            <input type="hidden" name="destination" value="{{ Request::fullUrl() }}">
                            <div class="form-group {{ $errors->has('vars.google_captcha_sitekey') ? 'has-error' : ''}}">
                                <label for="vars.google_captcha_sitekey">Ключ сайта (Sitekey)</label>
                                <input type="text" class="form-control" name="vars[google_captcha_sitekey]" value="{{ variable('google_captcha_sitekey') }}">
                                {!! $errors->first('vars.google_captcha_sitekey', '<p class="help-block">:message</p>') !!}
                            </div>
                            <div class="form-group {{ $errors->has('vars.google_captcha_secret') ? 'has-error' : ''}}">
                                <label for="vars.google_captcha_secret">Секретный ключ (Secret)</label>
                                <input type="text" class="form-control" name="vars[google_captcha_secret]" value="{{ variable('google_captcha_secret') }}">
                                {!! $errors->first('vars.google_captcha_secret', '<p class="help-block">:message</p>') !!}
                            </div>

                            {!! link_to('https://developers.google.com/recaptcha/', null, ['target' => '_blank']) !!}
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
                        <i class="fa fa-share-alt-square"></i>
                        <h3 class="box-title">SendPulse</h3>
                    </div>
                    <form action="{{ route('admin.variable.save') }}" method="POST">
                        <div class="box-body">
                            @csrf
                            <input type="hidden" name="destination" value="{{ Request::fullUrl() }}">
                            <div class="form-group {{ $errors->has('vars.sendpulse_user_id') ? 'has-error' : ''}}">
                                <label for="vars.sendpulse_user_id">User ID</label>
                                <input type="text" class="form-control" name="vars[sendpulse_user_id]" value="{{ variable('sendpulse_user_id') }}">
                                {!! $errors->first('vars.sendpulse_user_id', '<p class="help-block">:message</p>') !!}
                            </div>
                            <div class="form-group {{ $errors->has('vars.sendpulse_secret') ? 'has-error' : ''}}">
                                <label for="vars.sendpulse_secret">Secret</label>
                                <input type="text" class="form-control" name="vars[sendpulse_secret]" value="{{ variable('sendpulse_secret') }}">
                                {!! $errors->first('vars.sendpulse_secret', '<p class="help-block">:message</p>') !!}
                            </div>

                            <div class="form-group {{ $errors->has('vars.sendpulse_confirmation_sender_email') ? 'has-error' : ''}}">
                                <label for="vars.sendpulse_confirmation_sender_email">Адрес отправителя, от которого отправится письмо подтверждение</label>
                                <input type="email" class="form-control" name="vars[sendpulse_confirmation_sender_email]" value="{{ variable('sendpulse_confirmation_sender_email') }}">
                                {!! $errors->first('vars.sendpulse_confirmation_sender_email', '<p class="help-block">:message</p>') !!}
                                <p class="help-block small">* Адрес должен быть подтвержденным в личном кабинете в <strong>{!! link_to('https://login.sendpulse.com/emailservice/senders/', 'настройках сервиса в меню') !!}</strong>. (настройки сервиса ->адреса отправителя)</p>
                            </div>

                            @php
                            $addressBooks = [];
                            $hasError = true;
                            if (variable('sendpulse_user_id') && variable('sendpulse_secret')) {
                                try {
                                    $books = app('SendPulse')->listAddressBooks();
                                    foreach ($books as $a) {
                                        $addressBooks[$a->id] = "$a->name ($a->id)";
                                    }
                                    $hasError = false;
                                } catch (Exception $e) {
                                }
                            }
                            @endphp

                            @if($hasError)
                                <div class="callout callout-warning">
                                    <h4>Внимание!</h4>
                                    <p>Не верно указаны настройки API или нет связи с SendPulse.</p>
                                </div>
                            @else
                            <div class="form-group">
                                <label for="vars.sendpulse_address_book_id">ID адресной книги "Подписались на новость"</label>
                                {!! Form::select('vars[sendpulse_address_book_id]', ['' => 'Укажите адрессную книгу']+$addressBooks, variable('sendpulse_address_book_id'), ['class' => 'form-control']) !!}
                                {!! $errors->first('vars.sendpulse_address_book_id', '<p class="help-block">:message</p>') !!}
                            </div>

                            <div class="form-group">
                                <label for="vars.sendpulse_address_book_id_order">ID адресной книги для "Сделали заказ"</label>
                                {!! Form::select('vars[sendpulse_address_book_id_order]', ['' => 'Укажите адрессную книгу']+$addressBooks, variable('sendpulse_address_book_id_order'), ['class' => 'form-control']) !!}
                                {!! $errors->first('vars.sendpulse_address_book_id_order', '<p class="help-block">:message</p>') !!}
                            </div>
                            @endif

                            {!! link_to('https://sendpulse.ua/integrations/api', null, ['target' => '_blank']) !!}
                        </div>
                        <div class="box-footer">
                            @include('admin.fields.field-form-buttons')
                        </div>
                    </form>
                </div>


                <div class="box box-default">
                    <div class="box-header with-border">
                        <i class="fa fa-instagram"></i>
                        <h3 class="box-title">Instagram</h3>
                        <div class="pull-right box-tools">
                            <a href="{{ route('admin.service.cache-clear', ['key' => 'insta_photos']) }}" class="pull-right btn btn-default" title="Сбросить кэш">
                                <i class="fa fa-eraser"></i></a>
                        </div>
                    </div>
                    <form action="{{ route('admin.variable.save') }}" method="POST">
                        <div class="box-body">
                            @csrf

                            <input type="hidden" name="destination" value="{{ Request::fullUrl() }}">
                            <div class="form-group {{ $errors->has('vars.insta_username') ? 'has-error' : ''}}">
                                <label for="vars.insta_username">Username</label>
                                <input type="text" class="form-control" name="vars[insta_username]" value="{{ variable('insta_username', 'hipertin.ru') }}">
                                {!! $errors->first('vars.insta_username', '<p class="help-block">:message</p>') !!}
                            </div>
                            <div class="form-group {{ $errors->has('vars.insta_photo_count') ? 'has-error' : ''}}">
                                <label for="vars.insta_photo_count">Количество фото</label>
                                <input type="number" class="form-control" min="0" step="1" name="vars[insta_photo_count]" value="{{ variable('insta_photo_count', 30) }}">
                                {!! $errors->first('vars.insta_photo_count', '<p class="help-block">:message</p>') !!}
                            </div>
                            <div class="form-group {{ $errors->has('vars.insta_photo_cache') ? 'has-error' : ''}}">
                                <label for="vars.insta_photo_cache">Время кеширования запроса, мин.</label>
                                <input type="number" class="form-control" min="0" step="1" name="vars[insta_photo_cache]" value="{{ variable('insta_photo_cache', 30) }}">
                                {!! $errors->first('vars.insta_photo_cache', '<p class="help-block">:message</p>') !!}
                            </div>

                            {!! link_to('https://www.instagram.com', null, ['target' => '_blank']) !!}
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
