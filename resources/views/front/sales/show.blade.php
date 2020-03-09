@extends('front.layouts.app')

@php
    MetaTag::setEntity($sale)->setDefault(['title' => $sale->name]);
@endphp

@section('content')
    <div class="actions actions_action">
        <div class="actions__wrapper">
            {{--
            <div class="action__head">
                <img src="{{ $sale->getFirstMediaUrl('image', 'long') ?: '/its-client/img/action-big.png'}}" alt="">
            </div>
            --}}
            <div class="actions__text">
                <h1 class="actions__name">{{ $sale->name }}</h1>
                <p class="small">
                    {!! $sale->description !!}
                </p>
            </div>
            @if($sales->count())
            <p class="actions__name">Ещё акции</p>
            <div class="actions__content">
                @foreach ($sales as $sale)
                    <a href="{{ route('sale.show', $sale) }}" class="actions__block">
                        <img src="{{ $sale->getFirstMediaUrl('image', 'table') ?: '/its-client/img/action.png' }}" alt="">
                        <h5>{{ $sale->name }}</h5>
                        <p class="small">{{ strip_tags(str_limit($sale->description, 60)) }}</p>
                        <button class="actions__btn btn-gen">Перейти к покупкам</button>
                    </a>
                @endforeach
            </div>
            @endif
        </div>

        @include('front.blocks.recommend-products', [
          'title' => 'Рекомендуемые товары',
       ])

    </div>
@endsection