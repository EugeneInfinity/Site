@extends('front.layouts.app')

@php
    MetaTag::setDefault(['title' => 'Акции']);
@endphp

@section('content')
    <div class="actions">
        <div class="actions__wrapper">
            <div class="politic__nav">
                {!! Breadcrumbs::render('sale.index') !!}
            </div>
            <h1 class="actions__name">Акции</h1>
            <div class="actions__content">
                @forelse ($sales as $sale)
{{--                <a href="{{ route('sale.show', $sale) }}" class="actions__block">--}}
                <a href="{{ $sale->url ? url($sale->url) : '/' }}" class="actions__block">
                    <img src="{{ $sale->getFirstMediaUrl('image', 'table') ?: '/its-client/img/action.png' }}" alt="">
                    <h5>{{ $sale->name }}</h5>
                    <p class="small">{!! strip_tags($sale->description) !!}</p>
                    <button class="actions__btn btn-gen">Перейти к покупкам</button>
                </a>

                @empty
                    <h3>Действующих акций сейчас нет :(</h3>
                @endforelse
            </div>
        </div>

{{--
        @include('front.blocks.recommend-products', [
          'title' => 'Рекомендуемые товары',
        ])
--}}

    </div>
@endsection
