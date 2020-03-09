@extends('front.layouts.app')

@php
    MetaTag::setDefault(['title' => 'Восстановление пароля']);
@endphp

@section('content')
    <div class="register">
        <div class="register__wrapper">
            <h1 class="register__name">ВОССТАНОВЛЕНИЕ ПАРОЛЯ</h1>
            <br>
            <div class="register__form">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form method="POST" action="{{ route('password.email') }}" data-id="reset-password-email" class="form {{--js-ajax-form-submit--}}">
                    @csrf
                    <div class="register__form-group form-group">
                        <label for="">Ваш E-mail</label>
                        <input class="input" name="email" type="email">
                        {{--<span class="necessarily">Обязательное поле*</span>--}}
                    </div>
                    <div class="register__form-group">
                        <button class="btn-gen_1" type="submit">Отправить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
