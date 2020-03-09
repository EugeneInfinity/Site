@extends('front.layouts.app')

@php
    MetaTag::setDefault(['title' => 'Регистрация на сайте']);
@endphp

@section('content')
    <div class="register">
        <div class="register__wrapper">
            <h1 class="register__name">РЕГИСТРАЦИЯ</h1>
            <div class="register__text">Поля, отмеченные звездочкой, обязательны для заполнения.</div>
            <div class="register__form">
                <form method="POST" action="{{ route('register') }}" data-id="register-client" class="form {{--js-ajax-form-submit--}}">
                    @csrf
                    @honeypot
                    <div class="register__form-group form-group">
                        <input class="input @if ($errors->has('name')) error @endif" value="{{ old('name') }}" name="name"  type="text" placeholder="Имя, фамилия*">
                        @if ($errors->has('name'))<p class="error">{{ $errors->first('name') }}</p>@endif
                    </div>
                    <div class="register__form-group form-group">
                        <input class="input @if ($errors->has('email')) error @endif" value="{{ old('email') }}" name="email" type="text" placeholder="E-mail*">
                        @if ($errors->has('email'))<p class="error">{{ $errors->first('email') }}</p>@endif
                    </div>
                    <div class="register__form-group form-group">
                        <input class="input @if ($errors->has('password')) error @endif" name="password" type="password" placeholder="Пароль*">
                        @if ($errors->has('password'))<p class="error">{{ $errors->first('password') }}</p>@endif
                    </div>
                    <div class="register__form-group form-group">
                        <input class="input @if ($errors->has('password')) error @endif" name="password_confirmation" type="password" placeholder="Подтвердите пароль*">                        @if ($errors->has('name'))<p class="error">{{ $errors->first('name') }}</p>@endif
                    </div>
                    <div class="register__form-group form-group">
                        <label for="">{{-- <span>(Опционально)</span>--}}</label>
                        <input class="input phone1 @if ($errors->has('phone')) error @endif" value="{{ old('phone') }}" name="phone" type="text" placeholder="">
                        @if ($errors->has('phone'))<p class="error">{{ $errors->first('phone') }}</p>@endif
                    </div>

                    <div class="register__form-group register__form-group_select form-group">
                        <label for="">Дата рождения</label>
                        <div class="group date-selects">
                            <input type="hidden" name="date_year" value="{{ date('Y') }}">
                            <select name="date_day" class="select-basket">
                                <option value="{{ old('date_day') }}">День</option>
                                @for($i = 1; $i < 32; $i++)
                                    <option value="{{$i}}">{{$i}}</option>
                                @endfor
                            </select>
                            <select name="date_month" value="{{ old('date_month') }}" class="select-basket">
                                <option value="">Месяц</option>
                                <option value="1">Январь</option>
                                <option value="2">Февраль</option>
                                <option value="3">Март</option>
                                <option value="4">Апрель</option>
                                <option value="5">Май</option>
                                <option value="6">Июнь</option>
                                <option value="7">Июль</option>
                                <option value="8">Август</option>
                                <option value="9">Сентябрь</option>
                                <option value="10">Октябрь</option>
                                <option value="11">Ноябрь</option>
                                <option value="12">Декабрь</option>
                            </select>
                            <input type="hidden" name="birthday" value="{{ old('birthday') }}" value="" class="date-res">{{--2019-02-22--}}
                        </div>
                    </div>

                    <div class="register__form-group register__form-group_check form-group">
                        <label>
                            <input type="hidden" name="data[subscriber]" value="0">
                            <input class="checkbox" type="checkbox" name="data[subscriber]" value="1" @if(old('data.subscriber')) checked @endif>
                            <span class="checkbox-custom"></span>
                            <span class="label">Подписаться на новости и акции</span>
                        </label>
                    </div>
                    <div class="register__form-group register__form-group_inner form-group">
                        <label>
                            <input type="hidden" name="accept" value="0">
                            <input class="checkbox" type="checkbox" name="accept" value="1" @if(old('accept')) checked @endif>
                            <span class="checkbox-custom"></span>
                            <span class="label">Я согласен(-на) с <a href="/policy/"> политикой конфиденциальности*</a></span>
                        </label>
                        @if ($errors->has('accept'))<p class="error">{{ $errors->first('accept') }}</p>@endif
                    </div>
                    @if(variable('google_captcha_secret'))
                    <div class="register__form-group register__form-group_check form-group">
                        {!! Captcha::display() !!}
                        @if ($errors->has('g-recaptcha-response'))<p class="error">{{ $errors->first('g-recaptcha-response') }}</p>@endif
                    </div>
                    @endif
                    <div class="register__form-group">
                        <button class="btn-gen_1" type="submit">Зарегистрироваться</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
