@extends('admin.app')

@php
    $content_header = [
        'page_title' => 'Товары',
        'small_page_title' => '',
        'url_back' => session('admin.products.index'),
        'url_create' => '',
    ]
@endphp

@section('content')
    <section class="content">
        <div class="box">

            <div class="box-header">
                <div class="row">
                    <div class="col-lg-8">
                        <i class="ion ion-clipboard"></i>
                        <h3 class="box-title"> Создание товара</h3>
                    </div>
                </div>
            </div>
            <div class="box-body">
                <!--tabs-->
                <div class="nav-tabs-justified">
                    <ul class="nav nav-tabs">
                        @foreach(['Товар' => route('admin.products.create'), 'Значения атрибутов' => '#', 'SEO' => '#'] as $title => $url)
                            <li class="@if(Request::url() == rtrim($url, '/')) active @else disabled @endif"><a @if(Request::url() != rtrim($url, '/')) && $url != '#')href="{{ $url }}"@endif>{{ $title }}</a></li>
                        @endforeach
                        <li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-gear"></i></a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
                            <br>
                            {!! Form::open([
                                 'route' => 'admin.products.store',
                                 'files' => true
                            ]) !!}
                            @include('admin.shop.products._form')
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
