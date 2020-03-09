{{-- куки при первом посещении --}}
<div class="cookies" hidden>
    <div class="cookies__wrapper">
        <button class="button-close"><img src="/its-client/img/close.png" alt=""></button>
        <div class="cookies__text">
            Использую данный сайт, Вы даете согласие на использование нами Cookies с целью сбора статистики посещаемости сайта и предоставления рекламы с учетом Ваших интересов
            <a href="{{ variable('url_cookie_info', '#') }}">Подробнее</a>
        </div>
    </div>
</div>

{{-- Подписка на рассылку успешно оформлена --}}
<div class="modal modal-request fade" id="subscribe-modal-success" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="modal-body">
                <div class="request">
                    <div class="request__wrapper">
                        <div class="request__name">Вы подписались на новости Hipertin</div>
                        <div class="request__text">Скоро вам придет письмо с промокодом на скидку 10% на следующий заказ</div>
                        <div class="request__button">
                            <a href="#" class="btn-gen" data-dismiss="modal" aria-label="Close">Хорошо</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Подписка на рассылку не оформлена --}}
<div class="modal finish-modal fade" id="subscribe-modal-warning" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="modal-body">
                <div class="request">
                    <div class="request__wrapper">
                        <div class="request__name">Извините, но вы уже подписаны на новостную рассылку</div>
                        <div class="request__button">
                            <a href="#" class="btn-gen" data-dismiss="modal" aria-label="Close">Понятно</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Личный кабинет успешно сохранен --}}
<div class="modal modal-request modal-request_info modal-request_basket fade"
     id="edit-modal-success" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="modal-body">
                <div class="request">
                    <div class="request__wrapper">
                        <div class="request__name">Новые данные успешно сохранены</div>
                        <div class="request__button">
                            <a href="#" class="btn-gen" data-dismiss="modal" aria-label="Close">Отлично</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Отзыв оставлен успешно --}}
<div class="modal finish-modal finish-modal_feedback fade" id="review-modal-success" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="request">
                    <div class="request__wrapper">
                        <div class="request__name">Спасибо за ваш отзыв!</div>
                        <div class="request__text">Он был направлен на модерацию.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal modal-request modal-request_thank fade" id="register-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="modal-body">
                <div class="request">
                    <div class="request__wrapper">
                        <div class="request__name">Заявка отправлена, спасибо!</div>
                        <div class="request__text">После обработки наш менеджер свяжется с вами для уточнения</div>
                        <div class="request__button">
                            <a href="#" class="btn-gen" data-dismiss="modal" aria-label="Close">Хорошо</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Успешная регистрация --}}
<div class="modal finish-modal finish-modal_thank fade" id="register-modal-success" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="request">
                    <div class="request__wrapper">
                        <div class="request__img">
                            <img src="/its-client/img/logo-big.png" alt="">
                        </div>
                        <div class="request__name">Спасибо за регистрацию!</div>
                        <div class="request__button">
                            <a href="/" class="btn-gen_1">Вернуться на главную </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Купить в один клик --}}
<div class="modal finish-modal finish-modal_issue fade" id="buy-one-click-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="request">
                    <div class="request__wrapper">
                        <form action="{{ route('form.store') }}"
                              method="POST"
                              class="js-ajax-form-submit"
                              data-id="buy-one-click"
                              data-seo-action="send_one_click_buy_button"
                              data-seo-label=""
                        >
                            <input type="hidden" name="type" value="buy_one_click">
                            <input type="hidden" name="product_id" value="">
                            @csrf
                            @honeypot
                            <div class="top">
                                <h3 class="top__title"> Оформить заказ</h3>
                                <p class="top__small-title"> Оставьте ваше имя и телефон, мы свяжемся с вами в ближайшее время </p>
                            </div>
                            <div class="content">
                                <div class="input-group form-group">
                                    <p class="input-group__name">Ваше имя*</p>
                                    <input class="input" placeholder="" type="input" name="name">
                                </div>
                                <div class="input-group form-group">
                                    <p class="input-group__name">Ваш телефон* </p>
                                    <input class="input phone1" placeholder="" type="input" name="phone">
                                </div>
                            </div>
                            <div class="bottom">
                                <button class="btn-gen" type="submit"> Купить в один клик  </button>
                            </div>
                            <div class="request__info">
                                Нажимая кнопку “Купить в один клик”, вы соглашаетесь с
                                <a href="#">правилами обработки</a>
                                ваших персональных данных.
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Успешный заказ в один клик && корзина --}}
<div class="modal modal-request modal-request_basket fade" id="basket-modal-success" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="modal-body">
                <div class="request">
                    <div class="request__wrapper">
                        <div class="request__name">Спасибо за заказ!</div>
                        <div class="request__text">Наш менеджер свяжется с вами для уточнения</div>
                        <div class="request__button">
                            <a href="#" class="btn-gen" data-dismiss="modal" aria-label="Close">Продолжить покупки</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Успешная отправка формы Сотрудничество --}}
<div class="modal modal-request modal-request_basket fade" id="form-modal-cooperation-success" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="modal-body">
                <div class="request">
                    <div class="request__wrapper">
                        <div class="request__name">Спасибо за заявку!</div>
                        <div class="request__text">Наш менеджер свяжется с вами для уточнения</div>
                        <div class="request__button">
                            <a href="#" class="btn-gen" data-dismiss="modal" aria-label="Close">Продолжить покупки</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Успешная отправка форм --}}
<div class="modal modal-request modal-request_basket fade" id="form-modal-default-success" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="modal-body">
                <div class="request">
                    <div class="request__wrapper">
                        <div class="request__name">Спасибо за заявку!</div>
                        <div class="request__text">Наши менеджеры в ближайшее время обработают вашу заявку</div>
                        <div class="request__button">
                            <a href="#" class="btn-gen" data-dismiss="modal" aria-label="Close">Продолжить покупки</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Подписка на новости --}}
<div class="modal modal-request modal-request_news fade" id="subscribe-news-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="modal-body">
                <div class="request">
                    <div class="request__wrapper">
                        <div class="request__line">
                            Подпишитесь на нашу новостную рассылку
                        </div>
                        <div class="request__text">Оставьте свой e-mail и <span>получите скидку 10%</span> на следующий заказ!</div>
                        <form action="{{ route('form.store') }}"
                              data-id="home-subscribe"
                              data-seo-action="send_subsctiption_popup_form"
                              class="js-ajax-form-submit"
                        >
                            @csrf
                            @honeypot
                            <input type="hidden" name="type" value="subscribers">
                            <div class="form-group">
                                <input type="email" name="email" class="input" placeholder="E-mail*">
                            </div>
                            <div class="form-group">
                                <label>
                                    <input type="hidden" value="0" name="subscribe">
                                    <input class="checkbox" type="checkbox" name="subscribe" value="1">
                                    <span class="checkbox-custom"></span>
                                    <p class="label">Подписаться на новости и акции</p>
                                </label>
                            </div>
                            <div class="form-group">
                                <label>
                                    <input type="hidden" value="0" name="accept">
                                    <input class="checkbox" type="checkbox" value="1" name="accept">
                                    <span class="checkbox-custom"></span>
                                    <p class="label">Я согласен(-на) с <a href="/policy/"> политикой конфиденциальности*</a></p>
                                </label>
                            </div>
                            <div class="request__button form-group">
                                <button href="#" type="submit"
                                        class="btn-gen_1"
                                >Подписаться</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@stack('modals')