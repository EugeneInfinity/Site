@extends('admin.app')

@php
    $content_header = [
        'page_title' => 'Акции',
        'small_page_title' => '',
        'url_back' => session('admin.sales.index'),
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
                        <h3 class="box-title"> Создание акции</h3>
                    </div>
                </div>
            </div>
            <div class="box-body">
                <!--tabs-->
                <div class="nav-tabs-justified">
                    <ul class="nav nav-tabs">
                        @foreach(['Акция' => route('admin.sales.create'), 'Опции' => '#', 'SEO' => '#'] as $title => $url)
                            <li class="@if(Request::url() == rtrim($url, '/')) active @else disabled @endif"><a @if(Request::url() != rtrim($url, '/') && $url != '#')href="{{ $url }}"@endif>{{ $title }}</a></li>
                        @endforeach
                        <li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-gear"></i></a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
                            <br>
                            {!! Form::open([
                                 'route' => 'admin.sales.store',
                                 'files' => true
                            ]) !!}
                            @include('admin.shop.sales._form')
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
