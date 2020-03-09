<div class="header-modal">
    <div class="header-modal__wrapper">
        @if($cartProducts->count())
            <div class="header-modal__head">
                <span>Товаров в корзине:</span>
                <span class="number">{{ \Cart::count() }}</span>
            </div>
            <div class="header-modal__content-repeat">
            @foreach($cartProducts as $product)
                <div class="header-modal__content">
                    <div class="header-modal__content-left">
                        <img src="{{$product->getFirstMediaUrl('images', 'header-modal') ?: '/its-client/img/modal-img.png'}}" alt="">
                    </div>
                    <div class="header-modal__content-right">
                        <h4>{{ $product->name }}</h4>
                        <p>{{ str_limit(strip_tags($product->description), 80) }}</p>
                        <div class="size">
                            <span>{{ $product->valuesStr() }}</span>
                            <span>х</span>
                            <span>{{ $productsCounts[$product->id] }}</span>
                            <span>=</span>
                            <span>{{ Currency::format($product->getCalculatePrice('price') * $productsCounts[$product->id], $product->currency) }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
            </div>
            <div class="header-pay__block">
                <span>ИТОГО:</span>
                <span>{{ Currency::format($total, 'RUB') }}</span>
            </div>
            <div class="header-modal__button">
                <a class="btn-gen"
                   href="{{ route('shopping-cart.index') }}"
                >
                    <button class="btn-gen">Перейти в корзину</button>
                </a>
            </div>
        @else
            <div class="header-modal__head">
                <span>Товаров в корзине нет</span>
            </div>
        @endif
    </div>
</div>