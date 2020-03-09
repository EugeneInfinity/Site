@if(isset($iPhotos) && $iPhotos->count())
<div class="home-content__head home-content__head_insta">
    <h2 class="home-content__head-name">Instagram</h2>
    <div class="home-content__head-info">
        <div class="swiper-container swiper-container-insta">
            <div class="swiper-wrapper">
                @foreach($iPhotos->chunk(10) as $chunk)
                    @if($chunk->count() < 8) @break @endif
                    <div class="swiper-slide">
                        @foreach($chunk as $item)
                            <p>
                                <img src="{{ $item }}" alt="">
                            </p>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
        <div class="swiper-container swiper-container-instamobile">
            <div class="swiper-wrapper">
                @foreach($iPhotos->chunk(6) as $chunk)
                    <div class="swiper-slide">
                        @foreach($chunk as $item)
                            <p>
                                <img src="{{ $item }}" alt="">
                            </p>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
        <div class="swiper-button-prev swiper-button-prev-insta">
            <img src="/its-client/img/inst-arrow.png" alt="">
        </div>
        <div class="swiper-button-next swiper-button-next-insta">
            <img src="/its-client/img/inst-arrow.png" alt="">
        </div>
        <div class="swiper-button-prev swiper-button-prev-instamobile">
            <img src="/its-client/img/white-insta.png" alt="">
        </div>
        <div class="swiper-button-next swiper-button-next-instamobile">
            <img src="/its-client/img/white-insta.png" alt="">
        </div>
    </div>
</div>
@endif