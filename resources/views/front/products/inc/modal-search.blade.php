{{--<div class=" header-tabs header-search__wrapper header-search">--}}
    <div class="header-search__head">
        <form action="{{ route('product.search') }}" method="GET" class="js-ajax-form-submit" data-html-container=".header-search__bottom">
            <input type="text" class="input" name="q" value="" placeholder="Название товара">
            <button type="submit" class="btn-gen">Поиск</button>
        </form>
    </div>
    <div class="header-search__bottom">

    </div>
{{--</div>--}}