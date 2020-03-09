<footer class="footer">
    <div class="footer__wrapper">
        <div class="footer__block">
            <ul>
                @foreach($menu_items_main_menu as $item)
                <li><a href="{{ $item->url }}" {{ $item->targetStr }}>{{ $item->name }}</a></li>
                @endforeach
            </ul>
        </div>
        <div class="footer-mobile">
            <button><span> Информационные разделы</span> <img src="/its-client/img/arrow-down.png" alt=""> </button>
        </div>
        <div class="footer__block footer__block_second">
            <ul>
                @foreach($menu_items_info_part as $item)
                <li><a href="{{ $item->url }}" {{ $item->targetStr }}>{{ $item->name }}</a></li>
                @endforeach
            </ul>
        </div>
        <div class="footer__block footer__block_last">
            <ul>
                <li>{!! variable('company_email') !!}</li>
                <li>{!! variable('company_work_schedule') !!}</li>
                <li>{!! variable('company_phone') !!}</li>
            </ul>
            <div class="footer__sitebar">
                <span>Мы в соцсетях</span>
                <div class="footer__sitebar-items">
                    @foreach($menu_items_social_network as $item)
                        <a href="{{ $item->url }}" class="footer__sitebar-item" {{ $item->targetStr }}>
                            <img src="{{ optional($item->getFirstMedia('image'))->getUrl() ?? '/its-client/img/insta.png' }}" alt="{{ $item->name }}">
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</footer>