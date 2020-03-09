@extends('front.layouts.app')

@php
    MetaTag::setEntity($page)->setDefault(['title' => $page->name]);
@endphp

@section('content')
    <div class="head-section-faq">
        <div class="container-head">
            <h1 class="container-head__title" > ВОПРОСЫ И КОНТАКТЫ </h1>
        </div>
    </div>
    <div class="wrapper-body-faq">
        <h3 class="title-faq">ЧАСТО ЗАДАВАЕМЫЕ ВОПРОСЫ </h3>
        <div class="total-wrapper">
            <div class="total-wrapper__left">
                <ul class="list">
                    @forelse(\App\Models\Form::forFront('faq')->get() as $item)
                        <li class="list__item">
                            <p class="row-link">
                                <span class="row-link__text">
                                    {!! $item->data['message'] ?? '' !!}
                                </span>
                                <span class="hide">
                                    {!! $item->data['answer'] ?? '' !!}
                                </span>
                            </p>
                            <span class="row-link__arrow">
                                <img src="/its-client/img/down.png" alt="" class="img-first" >
                            </span>
                        </li>
                    @empty
                        <li class="list__item">
                            <p class="row-link">
                                <span class="row-link__text">
                                    Как найти дистрибьютора в моем регионе?
                                </span>
                                <span class="hide">
                                        Заполните заявку здесь и с Вами свяжется коммерческий представитель.
                                </span>
                            </p>
                            <span class="row-link__arrow">
                                    <img src="/its-client/img/down.png" alt="" class="img-first" >
                            </span>
                        </li>
                    @endforelse
                </ul>
            </div>
            <div class="total-wrapper__right">
                <div class="wrapper-inner">
                    <h4 class="wrapper-inner__title"> СВЯЖИТЕСЬ С НАМИ  </h4>
                    {!! $page->body !!}
                </div>
                <div class="wrapper-form">
                    <form action="{{ route('form.store') }}" method="POST" class="js-ajax-form-submit" data-id="form-default">
                        @csrf
                        @honeypot
                        <input type="hidden" name="type" value="faq">
                        <div class="input-group form-group">
                            <p class="input-group__name">ФИО</p>
                            <input class="input" placeholder="" type="input" name="name">
                        </div>
                        <div class="select-group form-group">
                            <div class="form-group form-group_select">
                                <p class="input-group__name">Тема сообщения </p>
                                <div class="validate">
                                    <select name="terms[faq_subjects][]" class="select-personal">
                                        @foreach(\App\Models\Taxonomy\Term::byVocabulary('faq_subjects')->get() as $tern)
                                        <option value="{{$tern->id}}">{{$tern->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="input-group form-group">
                            <p class="input-group__name">Город, область</p>
                            <input class="input" placeholder="" type="input" name="city">
                        </div>
                        <div class="input-group form-group">
                            <p class="input-group__name">Телефон</p>
                            <input class="input phone1" placeholder="" type="input" name="phone">
                        </div>
                        <div class="input-group form-group">
                            <p class="input-group__name">E-mail</p>
                            <input class="input" placeholder="" type="input" name="email">
                        </div>
                        <div class="textarea-group form-group">
                            <p class="textarea-group__name">Сообщение</p>
                            <textarea  class="input" name="message"></textarea>
                            <!-- <div class="textarea-group__consent">
                                <p>
                                Я согласен на обработку предоставленных мною данных
                                </p>
                            </div> -->
                        </div>
                        <div class="button-cooperation">
                            <div class="button-cooperation__consent form-group">
                                <label>
                                    <input type="hidden" name="accept" value="0">
                                    <input class="checkbox" type="checkbox" name="accept" value="1">
                                    <span class="checkbox-custom"></span>
                                    <p class="label">Я согласен на обработку предоставленных мною данных</p>
                                </label>
                            </div>
                            <button type="submit" class="submit-btn btn-gen">
                                Отправить
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @include('front.blocks.recommend-products', [
          'title' => 'Рекомендуемые товары',
       ])
    </div>
@endsection
