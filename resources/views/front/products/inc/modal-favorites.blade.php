{{--<div class="header-tabs header-like">--}}
    <div class="header-modal">
        <div class="header-modal__wrapper">
            @if($favoriteProducts->count())
                <div class="header-modal__head">
                    <span>Товаров в избранном: </span>
                    <span class="number">{{ $favoriteProducts->count() }}</span>
                </div>
                <div class="header-modal__content-repeat">
                @foreach($favoriteProducts as $product)
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
                                <span>{{ Currency::format($product->getCalculatePrice('price'), $product->currency) }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
                </div>

                @auth
                <div class="header-modal__button">
                    <a href="{{ route('account.favorites') }}">
                        <button class="btn-gen">Перейти в избранное</button>
                    </a>
                </div>
                @endauth
            @else
                <div class="header-modal__head">
                    <span>Товаров в избранном нет </span>
                </div>
            @endif
        </div>
    </div>
{{--</div>--}}