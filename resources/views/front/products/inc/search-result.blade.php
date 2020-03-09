@isset($products)
    <div class="header-search__bottom-name">Результаты поиска ({{ $products->count() }})</div>
    <div class="header-search__bottom-blocks">
    @foreach($products as $product)
        @include('front.products.inc.single-product', ['product' => $product])
    @endforeach
    </div>
@endisset