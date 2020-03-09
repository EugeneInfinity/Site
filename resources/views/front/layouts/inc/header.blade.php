<header class="header active">
    <div class="header__wrapper">
        <div class="header__mobile">
            <button class="header__mobile-menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
        <a href="/" class="header__logo">
            <img src="/its-client/img/logo-hi.png" alt="">
        </a>
        <div class="header__navbar">
            <nav>

                <ul class="header__navbar-items">
                    @foreach($menu_items_main_menu as $level1)
                        @if($level1->children->count())
                            <li class="menu">
                                <a href="{{ $level1->url }}" {{ $level1->targetStr }} class="dekstop-menu">{{ $level1->name }}</a>
                                <button class="menu-btn"><img src="/its-client/img/arrow-gray.png" alt=""></button>
                                <ul class="mobile-menu">
                                    @foreach($level1->children as $level2)
                                        <li class="menu-1 @if($loop->last) menu-1_last @endif">
                                            <a href="{{ $level2->url }}" {{ $level2->targetStr }}>{{ $level2->name }}</a>
                                            @if($level2->hasChildren())
                                                <button class="menu-btn-1"><img src="/its-client/img/arrow-gray.png" alt=""></button>
                                                <ul class="mobile-menu-1">
                                                    @foreach($level2->children as $level3)
                                                        <li><a href="{{ $level3->url }}" {{ $level3->targetStr }}>{{ $level3->name }}</a></li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </li>
                                        @if($loop->last && isset($product_top))
                                            <div class="menu-block">
                                                <a href="{{ route_alias('product.show', $product_top) }}">
                                                    <img src="{{ $product_top->getFirstMediaUrl('images', 'favorite') ?: '/its-client/img/home-img.png' }}" alt="{{ $product_top->name }} ({{$product_top->id}})">
                                                </a>
                                                <h5>{{ str_limit($product_top->name, 35) }}</h5>
                                                <p>{{ str_limit(strip_tags($product_top->description), 50) }}</p>
                                                <a href="{{ route_alias('product.show', $product_top) }}">Подробнее<img src="/its-client/img/link-arrow.png" alt=""></a>
                                            </div>
                                        @endif
                                    @endforeach
                                </ul>
                            </li>
                        @else
                            <li class="menu"><a href="{{ $level1->url }}">{{ $level1->name }}</a></li>
                        @endif
                    @endforeach
                </ul>

            </nav>
        </div>
        <div class="header__interface">
            <div class="header__interface-items">
                @auth
                <a href="{{ route('start') }}" class="header__interface-item user active" data-btn="user">
                    <svg class="icon-svg icon-svg-user "><use xlink:href="/its-client/img/sprite.svg#user"></use></svg>
                </a>
                @else
                <a href="#" class="header__interface-item user" data-btn="user">
                    <svg class="icon-svg icon-svg-user "><use xlink:href="/its-client/img/sprite.svg#user"></use></svg>
                </a>
                <div class="header-tabs header-user">
                    <div class="header-modal">
                        <div class="header-modal__wrapper">
                            <form method="POST" action="{{ route('login') }}" class="js-ajax-form-submit">
                                @csrf
                                <div class="header-user__name">
                                    Вход
                                </div>
                                <div class="header-user__group form-group">
                                    <input class="input" type="text" name="login" placeholder="Логин, телефон">
                                </div>
                                <div class="header-user__group form-group">
                                    <input class="input" name="password" type="password" placeholder="Пароль">
                                </div>
                                <div class="header-user__group form-group">
                                    <button class="btn-gen" type="submit" name="destination" value="/">Вход</button>
                                </div>
                                <span>или</span>
                                <div class="header-user__group">
                                    <a href="/register/" class="btn-gen">Зарегистрироваться</a>
                                </div>
                                <span>
                                    <a href="/password/reset/"
                                       style="display: inline; background: none; color: rgb(106, 106, 106);"
                                    >Восстановить пароль</a>
                                </span>

                            </form>
                        </div>
                    </div>
                </div>
                @endauth
                <button class="header__interface-item like @if(\Favorite::count()) active @endif" data-btn="like">
                    <svg class="icon-svg icon-svg-like "><use xlink:href="/its-client/img/sprite.svg#like"></use></svg>
                    <span class="number amount-favorites"></span>
                </button>

                <div class="header-tabs header-like" id="product-favorite">
                    @include('front.products.inc.modal-favorites')
                </div>

                <button class="header__interface-item pay @if(\Cart::count()) active @endif" data-btn="pay">
                    <svg class="icon-svg icon-svg-basket "><use xlink:href="/its-client/img/sprite.svg#basket"></use></svg>
                    <span class="open-menu">
                        <svg class="icon-svg icon-svg-basket-act "><use xlink:href="/its-client/img/sprite.svg#basket-act"></use></svg>
                    </span>
                    <span class="number amount-cart-products"></span>
                </button>

                <div class="header-tabs header-pay" id="product-cart">
                    @include('front.products.inc.modal-cart')
                </div>

                <div class=" header-tabs header-search__wrapper header-search">
                    @include('front.products.inc.modal-search')
                </div>

                <button class="header__interface-item search" data-btn="search">
                    <svg class="icon-svg icon-svg-search "><use xlink:href="/its-client/img/sprite.svg#search"></use></svg>
                    <span class="open-menu">
                            <svg class="icon-svg icon-svg-search-act "><use xlink:href="/its-client/img/sprite.svg#search-act"></use></svg>
                        </span>
                </button>
            </div>
        </div>
    </div>
</header>

<div class="header__gray-block">
</div>