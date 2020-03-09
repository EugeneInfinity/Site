@extends('front.layouts.app')

@php
    MetaTag::setDefault(['title' => 'Вход на сайт']);
@endphp

@section('content')
    <div class="register">
        <div class="register__wrapper">
            <h1 class="register__name">ВХОД</h1>
            <div class="register__text">Все поля обязательны, если нет пометки «Опционально»</div>
            <div class="register__form">
                <form method="POST" action="{{ route('login') }}" data-id="register-validate" class="form js-ajax-form-submit">
                    @csrf
                    <div class="register__form-group form-group">
                        <label for="">E-mail, телефон</label>
                        <input class="input" name="login" type="text">
                        {{--<span class="necessarily">Обязательное поле*</span>--}}
                    </div>
                    <div class="register__form-group form-group">
                        <label for="">Пароль</label>
                        <input class="input" name="password" type="password">
                    </div>
                    <div class="register__form-group register__form-group_check form-group">
                        <label>
                            <input class="checkbox" type="checkbox" name="remember" value="1">
                            <span class="checkbox-custom"></span>
                            <span class="label">Запомнить меня</span>
                        </label>
                    </div>
                    <div class="register__form-group">
                        <button class="btn-gen_1" type="submit">Вход</button>
                    </div>
                </form>
                <div class="register__text">
                <a href="/password/reset/"
                   style="display: inline; background: none; color: rgb(106, 106, 106);"
                >Восстановить пароль</a>
                </div>
            </div>
        </div>
    </div>
@endsection
