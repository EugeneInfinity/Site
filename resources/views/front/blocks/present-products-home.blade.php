@if(isset($present_products) && $present_products->count())
<div class="home-content__head home-content__head_present">
    <h2 class="home-content__head-name">{{ $title ?? 'Подарочные наборы' }}</h2>
    <div class="home-content__head-info">
        <div class="swiper-container swiper-container-present">
            <div class="swiper-wrapper">
                @foreach($present_products as $product)
                    <div class="swiper-slide @if ($loop->iteration > 3) swiper-slide-inner @endif">
                        @include('front.products.inc.single-product', ['product' => $product])
                    </div>
                @endforeach
            </div>
        </div>
        <div class="swiper-button-prev swiper-button-prev-present">
            <img src="/its-client/img/arrow-left.png" alt="">
        </div>
        <div class="swiper-button-next swiper-button-next-present">
            <img src="/its-client/img/arrow-right.png" alt="">
        </div>
    </div>
</div>
@endif