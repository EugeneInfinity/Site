@extends('front.layouts.app', [
    // for on scroll pagination
    "bodyAttrs" => "data-next-page-url={$products->nextPageUrl()}
                    data-content-container='.show-more-content-container'
                    data-show-more-loader='.show-more-loader'
                    class='show-more-scroll-container'"
])

@php

    MetaTag::setEntity($category)->setDefault(['title' => $category->name . " - {$products->total()} товаров"]);
    $filter = \FacetFilter::toArray(request()->get(\App\Helpers\FacetFilter\FacetFilterBuilder::$filterUrlKey, ''));

    if (request()->has(\App\Helpers\FacetFilter\FacetFilterBuilder::$filterUrlKey)) {
        MetaTag::setTags([
            'title' => StrToken::setText("[term:facetFilter:firstAttr] [term:facetFilter:firstValue] Hipertin – купить в официальном интернет-магазине")->setEntity($category)->replace(),
            'og_title' => StrToken::setText("[term:facetFilter:firstAttr] [term:facetFilter:firstValue] Hipertin – купить в официальном интернет-магазине")->setEntity($category)->replace(),
//            'title' => StrToken::setText($category->metaTag->title)->setEntity($category)->replace(),
        ]);
    } else {
        MetaTag::setTags([
            'title' => StrToken::setText(optional($category->metaTag)->title ?? '')->setEntity($category)->replace(),
            'og_title' => StrToken::setText(optional($category->metaTag)->title ?? '')->setEntity($category)->replace(),
        ]);
    }

    FacetFilter::setUrlPath(\UrlAlias::current());
@endphp

@section('content')
<div class="product">
    <div class="product__wrapper">
        <div class="product-left">
            {!! Breadcrumbs::render('category', $category) !!}
            <div class="product-left__content">
                <div class="mobile-filter">Фильтр <img src="/its-client/img/filter.png" alt=""></div>
                <div class="product-filter__repeat">
                    <div class="mobile-filter-block">
                        <div class="mobile-filter__name">Фильтр товаров</div>
                        <div class="mobile-filter__close"><img src="/its-client/img/close.png" alt=""></div>
                    </div>

                    @if($categories->count())
                    <div class="product-filter">
                        <div class="product-filter__name">Категория</div>
                        <div class="product-filter__block-repeat">
                            @foreach($categories as $category)
                            <div class="product-filter__block">
                                <label>
                                    <input class="checkbox facet-filter" type="checkbox" name="checkbox" @if(\FacetFilter::has('category', $category->slug)) checked @endif data-url="{{ \FacetFilter::build('category', $category->slug) }}">
                                    <span class="checkbox-custom"></span>
                                    <span class="label">{{ $category->name }}</span>
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if($facet['attributes']->count())
                        @foreach($facet['attributes'] as $attribute)
                        <div class="product-filter">
                            <div class="product-filter__name">{{ $attribute->title }}</div>
                            <div class="product-filter__block-repeat">
                                @foreach($facet['values']->where('attribute_id', $attribute->id) as $value)
                                <div class="product-filter__block">
                                    <label>
                                        <input class="checkbox facet-filter" type="checkbox" name="checkbox" @if(\FacetFilter::has($attribute->slug, $value->slug)) checked @endif  data-url="{{ \FacetFilter::build($attribute->slug, $value->slug) }}">
                                        <span class="checkbox-custom"></span>
                                        <span class="label">{{ $value->value }}{!! $value->suffix !!}</span>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    @endif

                    @if(FacetFilter::issetFilter())
                    <div class="product-button">
                        <a href="{{ \FacetFilter::reset() }}" class="btn-gen">Очистить фильтр</a>
                    </div>
                    @endif

                </div>
            </div>
        </div>
        <div class="product-right">
            <div class="product-right__head">
                <span class="select-filter__name">Сортировать по:&nbsp;</span>
                <select class="select-filter js-sortable-action" id="select2-filter" name="state">
                    <option value="{{ \Overrides\SortableLink::urlWithoutSort() }}">По умолчанию</option>
                    <option value="{{ \Overrides\SortableLink::url('name', 'asc') }}" @if(\Overrides\SortableLink::currentColumnDirection('name', 'asc')) selected @endif data-img="icon-arrow-top">Название</option>
                    <option value="{{ \Overrides\SortableLink::url('name', 'desc') }}" @if(\Overrides\SortableLink::currentColumnDirection('name', 'desc')) selected @endif data-img="icon-arrow-bottom">Название</option>
                    <option value="{{ \Overrides\SortableLink::url('price', 'asc') }}" @if(\Overrides\SortableLink::currentColumnDirection('price', 'asc')) selected @endif data-img="icon-arrow-top">Цена</option>
                    <option value="{{ \Overrides\SortableLink::url('price', 'desc') }}" @if(\Overrides\SortableLink::currentColumnDirection('price', 'desc')) selected @endif data-img="icon-arrow-bottom">Цена</option>
                    <option value="{{ \Overrides\SortableLink::url('rating', 'asc') }}" @if(\Overrides\SortableLink::currentColumnDirection('rating', 'asc')) selected @endif data-img="icon-arrow-top">Рейтинг</option>
                    <option value="{{ \Overrides\SortableLink::url('rating', 'desc') }}" @if(\Overrides\SortableLink::currentColumnDirection('rating', 'desc')) selected @endif data-img="icon-arrow-bottom">Рейтинг</option>
                </select>
            </div>
            <div class="product-right__content show-more-content-container">
                @include('front.products.inc.grid-products', ['products' => $products])
            </div>

            <div class="loader-block show-more-loader" style="display: none">
                <div class="loader">
                </div>
            </div>

            {{--
            <div style="text-align: center;">
            @if($products->nextPageUrl())
            <a href="#"
               class="btn btn-default show-more-btn"
               data-next-page-url="{{ $products->nextPageUrl() }}"
               data-content-container=".show-more-content-container"
            >
                Показать еще...
            </a>
            @endif
            </div>
            --}}
{{--
            <div class="align-content-center">
                {!! $products->links() !!}
            </div>
--}}

        </div>


    </div>
</div>
@endsection

@push('scripts')
    <script>
        $('select.js-sortable-action').on('change', function () {
            window.location.href = $(this).val()
        })

        $('.facet-filter').on('click', function () {
            window.location.href = $(this).data('url')
        })
    </script>
@endpush