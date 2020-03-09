@extends('admin.app')


@php
    $content_header = [
        'page_title' => 'Страницы',
        'url_back' => route('admin.pages.index'),
        'urlCreate' => ''
    ]
@endphp

@section('content')
<section class="content">
    <div class="box">

        <div class="box-header">
            <div class="row">
                <div class="col-lg-12">
                    <i class="ion ion-clipboard"></i>
                    <h3 class="box-title"> Редактирование страницы <strong>{{ $page->name }}</strong></h3>
                    @include('admin.inc.entity-navigation', [
                       'next' => $page->previous() ? route('admin.pages.edit', $page->previous()) : '',
                       'previous' => $page->next() ? route('admin.pages.edit', $page->next()) : '',
                    ])
                </div>
            </div>
        </div>
        <div class="box-body">
            <!--tabs-->
            <div class="nav-tabs-justified">
                <ul class="nav nav-tabs">
                    @foreach(['Страница' => route('admin.pages.edit', $page), 'SEO' => route('admin.pages.seo', $page)] as $title => $path)
                        <li class="@if(Request::url() == rtrim($path, '/')) active @endif"><a href="@if(Request::url() !== rtrim($path, '/')){{ $path }}@else # @endif">{{ $title }}</a></li>
                    @endforeach
                    {{--<li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-gear"></i></a></li>--}}
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_1">
                        <br>
                        @php($tab = isset($tab) ? $tab : request('tab'))
                        @if($tab == 'seo')
                            {!! Form::model($page->metaTag, [
                                'method' => 'POST',
                                'route' => ['admin.pages.seo.save', $page],
                                'files' => true
                            ]) !!}
                            @include('admin.pages._seo', ['model' => $page])
                        @else
                            {!! Form::model($page, [
                                'method' => 'PATCH',
                                'route' => ['admin.pages.update', $page],
                                'files' => true
                            ]) !!}
                            @include('admin.pages._form')
                        @endif
                        {!! Form::close() !!}
                    </div>
                </div>
            </div><!--/tabs-->
        </div>

    </div>
</section>
@stop