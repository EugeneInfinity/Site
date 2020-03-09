@foreach($products as $product)
    @include('front.products.inc.single-product', ['product' => $product])
@endforeach