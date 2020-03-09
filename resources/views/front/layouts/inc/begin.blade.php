<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <link rel="apple-touch-icon" sizes="180x180" href="/its-client/img/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/its-client/img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/its-client/img/favicon-16x16.png">
    <link rel="manifest" href="/its-client/img/site.webmanifest">
    <link rel="mask-icon" href="/its-client/img/safari-pinned-tab.svg" color="#d9c68d">
    <meta name="msapplication-TileColor" content="#d9c68d">
    <meta name="theme-color" content="#ffffff">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    {!! variable('front_code_in_head', '') !!}

    {!!
        MetaTag::setPath(\Fomvasss\UrlAliases\Facades\UrlAlias::current(false))
        ->setDefault([
            //'fb_app_id' => config('services.fb_id'), //config('meta-tags.default.fb_app_id'),
            'og_site_name' => config('app.name'),
            'og_url' => \Fomvasss\UrlAliases\Facades\UrlAlias::current(),
            'og_type' => 'article',
            //'og_image' => 'default-og-img.jpg',
        ])->render()
    !!}

    <!-- Styles application -->
    <link rel="stylesheet" href="{{ asset('its-client/css/plugins.css') }}">
    <link rel="stylesheet" href="{{ asset('its-client/css/style.css') }}">
    @stack('styles')
</head>
<body @isset($bodyAttrs) {!! $bodyAttrs  !!} @endisset>
{!! variable('front_code_start_body', '') !!}