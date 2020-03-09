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
                <form method="POST" action="{{ route('password.update') }}" data-id="reset-password-reset" class="form ">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="register__form-group form-group">
                        <label for="">E-mail</label>
                        <input class="input" name="email" type="email" value="{{ $email ?? old('email') }}" required autofocus>
                    </div>
                    <div class="register__form-group form-group">
                        <label for="">Пароль</label>
                        <input class="input" name="password" type="password" required>
                    </div>
                    <div class="register__form-group form-group">
                        <label for="">Повторите пароль</label>
                        <input class="input" name="password_confirmation" type="password" required>
                    </div>

                    <div class="register__form-group">
                        <button class="btn-gen_1" type="submit">Восстановить пароль</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
