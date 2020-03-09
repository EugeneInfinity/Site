@extends('front.layouts.app')

@php
    MetaTag::setEntity($page)->setDefault(['title' => $page->name]);
@endphp

@section('content')
    {{--
    <div class="head-section">
        <div class="gray-block"></div>
        <div class="container-head">
            <img src="/its-client/img/big-girl.png" alt="" class="container-head__img">
            <h3 class="container-head__title" >XОТИТЕ НАЧАТЬ РАБОТАТЬ С НАМИ?  </h3>
        </div>
    </div>
    --}}
    <div class="body-section">
        <div class="body-wrapper">
            <h1 class="body-wrapper__title">
                ЗАПОЛНИТЕ ЗАЯВКУ, И НАШ МЕНЕДЖЕР СВЯЖЕТСЯ С ВАМИ В БЛИЖАЙШЕЕ ВРЕМЯ
            </h1>
            <div class="wrapper-content">
                <form action="{{ route('form.store') }}"
                      method="POST"
                      class="js-ajax-form-submit"
                      data-id="form-default"
                      data-seo-action="send_cooperation_form"
                >
                    @csrf
                    @honeypot
                    <input type="hidden" name="type" value="cooperation">
                    <div class="input-group form-group">
                        <p class="input-group__name">ФИО*</p>
                        <input class="input" name="name" placeholder="" type="input">
                    </div>
                    <div class="input-group form-group">
                        <p class="input-group__name">E-mail*</p>
                        <input class="input" name="email" placeholder="" type="input">
                    </div>
                    <div class="input-group form-group">
                        <p class="input-group__name">Город, область*</p>
                        <input class="input" name="city" placeholder="" type="input">
                    </div>
                    <div class="input-group form-group">
                        <p class="input-group__name">Телефон*</p>
                        <input class="input phone1" name="phone" placeholder="" type="input">
                    </div>

                    <div class="select-group form-group">
                        <div class="form-group form-group_select">
                            <p class="input-group__name">Вид торговых услуг*</p>
                            <div class="validate">
                                <select name="terms[types_trade_services][]" required class="select-personal">
                                    <option value="">----</option>
                                    @foreach(\App\Models\Taxonomy\Term::byVocabulary('types_trade_services')->get() as $tern)
                                        <option value="{{$tern->id}}">{{$tern->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="textarea-group">
                        <div class="form-group">
                            <p class="textarea-group__name">Комментарий</p>
                            <textarea  class="input" name="message"></textarea>
                        </div>

                        <div class="textarea-group__consent form-group">
                            <label>
                                <input type="hidden" name="subscribe" value="0">
                                <input class="checkbox" type="checkbox" name="subscribe" value="1">
                                <span class="checkbox-custom"></span>
                                <p class="label">Подписаться на новости и акции</p>
                            </label>
                        </div>

                        <div class="textarea-group__consent form-group">
                            <label>
                                <input type="hidden" name="accept" value="0">
                                <input class="checkbox" type="checkbox" name="accept" value="1">
                                <span class="checkbox-custom"></span>
                                <p class="label">Я согласен(-на) с <a href="#">политикой конфиденциальности*</a></p>
                            </label>
                        </div>

                    </div>
                    <div class="button-cooperation">
                        <button type="submit" class="submit-btn btn-gen">
                            Отправить
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
