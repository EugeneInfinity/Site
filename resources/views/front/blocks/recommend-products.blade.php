@if(isset($recommend_products) && $recommend_products->count())
    <div class="recomendation-slider">
        <h2 class="home-content__head-name">{{ $title ?? 'Рекомендуемые товары' }}</h2>
        <div class="swiper-container swiper-container-recomend">
            <div class="swiper-wrapper">
                @foreach($recommend_products as $product)
                    <div class="swiper-slide">
                        @include('front.products.inc.single-product', ['product' => $product])
                    </div>
                @endforeach
            </div>
        </div>
        <div class="swiper-button-prev swiper-button-prev-recomend">
            <img src="/its-client/img/arrow-left.png" alt="">
        </div>
        <div class="swiper-button-next swiper-button-next-recomend">
            <img src="/its-client/img/arrow-right.png" alt="">
        </div>
    </div>
@endif

