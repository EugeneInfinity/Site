@if(isset($recommend_products) && $recommend_products->count())
<div class="home-content__head">
    <h1 class="home-content__head-name">{{ $title ?? 'Рекомендуемые товары' }}</h1>
    <div class="home-content__head-info">
        <div class="swiper-container swiper-container-middle">
            <div class="swiper-wrapper">
                @foreach($recommend_products as $product)
                    <div class="swiper-slide @if ($loop->iteration > 3) swiper-slide-inner @endif">
                        @include('front.products.inc.single-product', ['product' => $product])
                    </div>
                @endforeach
            </div>
        </div>
        <div class="swiper-button-prev swiper-button-prev-middle">
            <img src="/its-client/img/arrow-left.png" alt="">
        </div>
        <div class="swiper-button-next swiper-button-next-middle">
            <img src="/its-client/img/arrow-right.png" alt="">
        </div>
    </div>
    {{--
    <div class="home-content__head-mobile">
        <button>
            <span>Ещё</span>
            <span>Скрыть</span>
            <img src="/its-client/img/arrow-gray.png" alt="">
        </button>
    </div>
    --}}
</div>
@endif