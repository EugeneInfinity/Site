@if($menu_items_slider_in_home->count())
    <div class="home__head">
        <div class="swiper-container swiper-container-head">
            <div class="swiper-wrapper">
                @foreach($menu_items_slider_in_home as $item)
                    <div class="swiper-slide" data-swiper-autoplay="{{ variable('home_swiper_autoplay', 3000) }}">
                        <a href="{{ $item->url }}" @if($item->target)target="{{ $item->target }}@endif">
                            <img class="dekstop" src="{{ $item->getFirstMedia('img_desktop') ? $item->getFirstMediaUrl('img_desktop') : '/its-client/img/slider-dekstop.jpeg' }}" alt="">
                            <img class="tablet" src="{{ $item->getFirstMedia('img_tablet') ? $item->getFirstMediaUrl('img_tablet') : '/its-client/img/slider-tablet.jpeg' }}" alt="">
                            <img class="mobile" src="{{ $item->getFirstMedia('img_mobile') ? $item->getFirstMediaUrl('img_mobile') : '/its-client/img/slider-mobile.jpeg' }}" alt="">
                        </a>
                        <!-- <button class="home__head-button">Подробнее</button> -->
                    </div>
                @endforeach
            </div>
            <div class="swiper-pagination swiper-pagination-head"></div>
        </div>
    </div>
@endif