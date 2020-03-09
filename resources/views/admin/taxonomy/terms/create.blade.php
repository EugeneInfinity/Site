@extends('admin.app')

@php
    $content_header = [
        'page_title' => $vocabulary->name,
        'url_back' => session('admin.terms.index'),
        'urlCreate' => ''
    ]
@endphp

@section('content')
<section class="content">
    <div class="box">

        <div class="box-header">
            <i class="ion ion-clipboard"></i>
            <h3 class="box-title">Создание термина словаря <strong>{{ $vocabulary->name }}</strong></h3>
        </div>
        <div class="box-body">

            <!--tabs-->
            <div class="nav-tabs-justified">
                <ul class="nav nav-tabs">
                    @foreach(['Термин' => route('admin.terms.create'), 'SEO' => '#'] as $title => $url)
                        <li class="@if(Request::url() == rtrim($url, '/')) active @else disabled @endif"><a @if(Request::url() != rtrim($url, '/')) && $url != '#')href="{{ $url }}"@endif>{{ $title }}</a></li>
                    @endforeach
                    {{--<li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-gear"></i></a></li>--}}
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active">
                        <br>
                        {!! Form::open([
                             'route' => 'admin.terms.store',
                             'files' => true
                        ]) !!}
                        @include('admin.taxonomy.terms._form')
                        {!! Form::close() !!}
                    </div>
                </div>
            </div><!--/tabs-->
        </div>
    </div>
</section>
@stop
