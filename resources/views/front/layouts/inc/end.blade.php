<!-- Scripts application -->
<script src="{{ asset('its-client/js/plugins.js') }}"></script>
<script src="{{ asset('its-client/js/script.js') }}"></script>
<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.3/toastr.min.js"></script>
<script>
    var common = {
        visitCountLimit:{{variable('site_visit_count_limit', -1)}},
        visitDurationLimit:{{variable('site_visit_duration_limit', -1)}},
    }
</script>

    {!! variable('front_code_seo_actions', "
<!-- SEO -->
<script>
    function seoActionHandle(seoAction, seoLabel) {
        console.log(seoAction, seoLabel)
        if (typeof dataLayer != 'undefined' && seoAction) {
            switch (seoAction) {
                case 'click_like_button': //Нажатие значка «Нравится» на мини-карточках товаров и внутри карточки товара
                    dataLayer.push({
                        'event': 'auto_event',
                        'event_category': 'like_button',
                        'event_action': 'click_like_button',
                        'event_label': seoLabel,
                        'event_interaction': 'False'
                    });
                    break;
                case 'click_buy_button': //Нажатие кнопки «Купить» на мини-карточках товаров и внутри карточки товара
                    dataLayer.push({
                        'event': 'auto_event',
                        'event_category': 'buy_button',
                        'event_action': 'click_buy_button',
                        'event_label': seoLabel,
                        'event_interaction': 'False'
                    });
                    break;
                case 'click_cart_button': //Нажатие кнопки «Перейти в корзину»
                    dataLayer.push({
                        'event': 'auto_event',
                        'event_category': 'cart_button',
                        'event_action': 'click_cart_button',
                        'event_interaction': 'False'
                    });
                    break;
                case 'send_one_click_buy_button': //Отправка формы «Купить в один клик»
                    dataLayer.push({
                        'event': 'auto_event',
                        'event_category': 'one_click_buy_form',
                        'event_action': 'send_one_click_buy_button',
                        'event_label': seoLabel,
                        'event_interaction': 'False'
                    });
                    break;
                case 'send_order_form': //Отправка формы «Заказа»
                    dataLayer.push({
                        'event': 'auto_event',
                        'event_category': 'order_form',
                        'event_action': 'send_order_form',
                        'event_interaction': 'False'
                    });
                    break;
                case 'send_cooperation_form': //Отправка формы «Сотрудничество»
                    dataLayer.push({
                        'event': 'auto_event',
                        'event_category': 'cooperation_form',
                        'event_action': 'send_cooperation_form',
                        'event_interaction': 'False'
                    });
                    break;
                case 'send_subsctiption_main_page_form': //Отправка формы «Подписаться на новости» на главной странице
                    dataLayer.push({
                        'event': 'auto_event',
                        'event_category': 'subscription_main_page_form',
                        'event_action': 'send_subsctiption_main_page_form',
                        'event_interaction': 'False'
                    });
                    break;
                case 'send_subsctiption_popup_form': //Отправка формы «Подписаться на новости» с модалки
                    dataLayer.push({
                        'event': 'auto_event',
                        'event_category': 'subscription_popup_form',
                        'event_action': 'send_subsctiption_popup_form',
                        'event_interaction': 'False'
                    });
                    break;
            }
        } else {
            console.log('ERROR: Object dataLayer  is undefined')
        }
    }
</script>
    ") !!}

<script src="{{ asset('its-client/js/common.js') }}"></script>
@stack('scripts')

{!! variable('front_code_end_body', '') !!}
</body>
</html>