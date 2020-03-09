<div class="home-content__head-block">
{{--
    <span style="position: absolute; left: 15px;">
        <a href="{{ route('admin.products.edit', $product) }}" target="_blank" style="font-size: 20px; color: grey;" title="Редактировать">⚙</a>
    </span>
--}}
    @if($product->isBestseller())
    <div class="home-content__head-top">
        Хит
    </div>
    @endif
    <a href="#" class="home-content__head-like js-action-click
       @if(\Favorite::is($product->id)) active @else favorite-action @endif"
       data-url="{{ route('product-favorite.toggle', $product) }}"
       data-html-container="#product-favorite"
       data-seo-action="click_like_button"
       data-seo-label="{{$product->sku}}"
    >
        <svg class="icon-svg icon-svg-like "><use xlink:href="/its-client/img/sprite.svg#like"></use></svg>
        <p class="hide">
            <div class="hide__wrapper">
                <span>Добавить в избранное</span>
            </div>
        </p>
    </a>

    <a href="{{ route_alias('product.show', $product) }}" class="home-content__head-img">
        <img src="{{ $product->getFirstMediaUrl('images', 'table') ?: '/its-client/img/home-img.png' }}" alt="{{ $product->name }} ({{$product->id}})">
    </a>
    <a href="{{ route_alias('product.show', $product) }}" class="home-content__head-foname">
        {{ str_limit($product->name, 55) }}
    </a>
    {{--<div class="home-content__head-text">{{ str_limit(strip_tags($product->description), 50) }}</div>--}}
    <div  class="home-content__head-review">
        @for($i = 1; $i < 6; $i++)
            @if($product->reviews_rating >= $i)
                <img src="/its-client/img/star-active.png" alt="">
            @else
                <img src="/its-client/img/star-off.png" alt="">
            @endif
        @endfor
    </div>
    <div class="home-content__head-price">{{ Currency::format($product->getCalculatePrice('price'), $product->currency) }}</div>
    {{--<div class="home-content__head-price">{{ Currency::format($product->price, $product->currency) }}</div>--}}
    <div class="home-content__head-button">
        <button class="btn-gen js-action-click buy-action"
                data-url="{{ route('shopping-cart.add', $product) }}"
                data-html-container="#product-cart"
                data-seo-action="click_buy_button"
                data-seo-label="{{ $product->sku }}"
        >Купить</button>
    </div>
</div>