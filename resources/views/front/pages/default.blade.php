@extends('front.layouts.app')

@php
    MetaTag::setEntity($page)->setDefault(['title' => $page->name]);
@endphp

@section('content')
    <div class="politic politic_buy">
        <div class="politic__wrapper default-page">

            <div class="politic__nav">
                {!! Breadcrumbs::render('page', $page) !!}
            </div>

            {!! $page->body !!}

        </div>
        @include('front.blocks.recommend-products', [
          'title' => 'Рекомендуемые товары',
       ])
    </div>
@endsection
