@extends('front.layouts.app')

@php
    MetaTag::setEntity($page)->setDefault(['title' => $page->name]);
@endphp

@section('content')

    <div class="wrapper-map-content">
        <div class="wrapper-map-content__left">
            {!! variable('page_contacts_address') !!}
        </div>
        <div class="wrapper-map-content__right">
            <div class="comunnication">

                <h1 class="communication__title">
                    Мы на связи
                </h1>
                {!! $page->body ?? '' !!}

                <form action="{{ route('form.store') }}" method="POST" class="js-ajax-form-submit" data-id="form-default">
                    @csrf
                    @honeypot
                    <input type="hidden" name="type" value="contacts">
                    <div class="communication__input-block">
                        <div class="input-block__left">
                            <div class="form-group">
                                <input class="input" placeholder="Ваше ФИО" type="text" name="name">
                            </div>
                            <div class="form-group">
                                <input class="input phone1" placeholder="Ваш номер" type="text" name="phone">
                            </div>
                            <div class="form-group">
                                <input class="input" placeholder="Ваш е-mail" type="email" name="email">
                            </div>
                        </div>
                        <div class="input-block__right form-group">
                            <textarea class="input" name="message" placeholder="Текст сообщения"></textarea>
                        </div>
                    </div>
                    <div class="communication__bottom-block">
                        <div class="form-group">
                            <label>
                                <input type="hidden" value="0" name="accept">
                                <input class="checkbox" type="checkbox" value="1" name="accept">
                                <span class="checkbox-custom"></span>
                                <span class="label"> Я согласен на обработку предоставленных мною данных</span>
                            </label>
                        </div>

                        @if(variable('google_captcha_secret'))
                        <div class="form-group">
                            {!! Captcha::display() !!}
                        </div>
                        @endif

                        <button type="submit" class="btn-gen">Отправить </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="map" id="google-map" data-page="contacts">
        <div id="google-container"></div>
    </div>
@endsection

@push('scripts')
    <script
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD9Qv3PhtLRXw8_cP707YTs8NwHukEnf9k">
    </script>

    <script>
        var latitude = {{ variable('contact_latitude', '55.759616') }},
            longitude = {{ variable('contact_longitude', '37.625457') }},
            map_zoom = {{ variable('contact_map_zoom', '18') }};

        var style= [
            {
                "featureType": "all",
                "elementType": "all",
                "stylers": [
                    {
                        "gamma": "0.4"
                    },
                    {
                        "saturation": "-86"
                    }
                ]
            },
            {
                "featureType": "all",
                "elementType": "geometry",
                "stylers": [
                    {
                        "saturation": "-31"
                    },
                    {
                        "lightness": "15"
                    }
                ]
            },
            {
                "featureType": "all",
                "elementType": "geometry.fill",
                "stylers": [
                    {
                        "saturation": "-99"
                    }
                ]
            },
            {
                "featureType": "all",
                "elementType": "labels",
                "stylers": [
                    {
                        "saturation": "-63"
                    },
                    {
                        "gamma": "1.14"
                    }
                ]
            },
            {
                "featureType": "all",
                "elementType": "labels.text",
                "stylers": [
                    {
                        "gamma": "0.92"
                    }
                ]
            },
            {
                "featureType": "all",
                "elementType": "labels.text.fill",
                "stylers": [
                    {
                        "gamma": "1.29"
                    },
                    {
                        "saturation": "-6"
                    }
                ]
            },
            {
                "featureType": "all",
                "elementType": "labels.text.stroke",
                "stylers": [
                    {
                        "gamma": "1.00"
                    }
                ]
            },
            {
                "featureType": "all",
                "elementType": "labels.icon",
                "stylers": [
                    {
                        "weight": "0.00"
                    },
                    {
                        "gamma": "2"
                    },
                    {
                        "lightness": "-29"
                    },
                    {
                        "saturation": "-82"
                    }
                ]
            },
            {
                "featureType": "administrative.country",
                "elementType": "all",
                "stylers": [
                    {
                        "gamma": "1.00"
                    }
                ]
            }
        ]


        var map_options = {
            center: new google.maps.LatLng(latitude, longitude),
            zoom: map_zoom,
            panControl: false,
            zoomControl: false,
            mapTypeControl: false,
            streetViewControl: false,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            scrollwheel: false,
            styles: style
        }

        var map = new google.maps.Map(document.getElementById('google-container'), map_options);

        var marker = new google.maps.Marker ({
            map: map,
            position: {lat: latitude, lng: longitude},
            icon: '/its-client/img/marker.svg'
        })
    </script>
@endpush