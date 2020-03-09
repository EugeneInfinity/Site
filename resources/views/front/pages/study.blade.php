@extends('front.layouts.app')

@php
    MetaTag::setEntity($page)->setDefault(['title' => $page->name]);
@endphp

@section('content')
    <section class="vebinar-section">
        <div class="bready-crumbs">
            <{!! Breadcrumbs::render('page', $page) !!}
        </div>
        {!! $page->body !!}
    </section>
    @include('front.blocks.recommend-products', [
      'title' => 'Рекомендуемые товары',
   ])
@endsection
