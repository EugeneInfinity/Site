@extends('front.layouts.app')

@php
    MetaTag::setDefault(['title' => '404 - Страница не найдена!']);
@endphp

@section('content')
    <div class="header__gray-block">
    </div>

    <div class="error-page">
        <div class="error-page__wrapper">
            <div class="error-page__error">404 {{ $exception->getMessage() }}</div>
            <div class="error-page__name">Извините, что-то пошло не так...</div>
            <div class="error-page__text">Страница не найдена</div>
            {{--<div class="error-page__text">{!! (app()->isLocal() && isset($exception) && ($exception->getMessage()) ? $exception->getMessage() : '') !!}</div>--}}
        </div>
    </div>
@endsection
