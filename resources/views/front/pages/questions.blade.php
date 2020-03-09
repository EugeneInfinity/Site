@extends('front.layouts.app')

@php
    MetaTag::setEntity($page)->setDefault(['title' => $page->name]);
@endphp

@section('content')
    <div class="question">
        <div class="question__wrapper">
            <h1 class="question__name">
                <span>Есть вопросы по продукту или его использованию? Задайте их здесь.</span>
                <span>Пожалуйста, опишите подробно детали вашего обращения. <br>
                        Наши специалисты обработают ваш вопрос, и мы опубликуем его вместе ответом ниже.
                </span>
                <span>Ваше обращение и ответ на него будут видны всем пользователям сайта.</span>
            </h1>
            <div class="question__form">
                <form action="{{ route('form.store') }}" method="POST" class="js-ajax-form-submit" data-id="form-default">
                    <input type="hidden" name="type" value="questions">
                    @csrf
                    @honeypot
                    <div class="form-wrapper">
                        <div class="form-left">
                            <div class="form-group">
                                <label for="">ФИО</label>
                                <input class="input" name="name" type="text">
                            </div>
                            <div class="form-group">
                                <label for="">E-mail </label>
                                <input class="input" type="text" name="email">
                            </div>
                        </div>
                        <div class="form-right form-group">
                            <label for="">Вопрос</label>
                            <textarea class="input" name="message"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <button class="btn-gen" type="submit">Отправить</button>
                    </div>
                </form>
            </div>
            <div class="question__content">
                @forelse(\App\Models\Form::forFront('questions')->get() as $item)
                    <div class="question__content-block">
                        <div class="question__content-question">
                            <div class="question__content-head">
                                <div class="question__content-name">{{ $item->data['name'] ?? '' }}</div>
                                <div class="question__content-date">{{ $item->created_at->format('d.m.Y') }}</div>
                            </div>
                            <div class="question__content-text">{{ $item->data['message'] ?? '' }}</div>
                        </div>
                        @isset($item->data['answer'])
                        <div class="question__content-answer">
                            <div class="question__content-text">{{ $item->data['answer'] ?? '' }}</div>
                        </div>
                        @endisset
                    </div>
                @empty
                    <div class="question__content-question">
                        <div class="question__content-head">
                            <div class="question__content-name">МАРГАРИТА</div>
                            <div class="question__content-date">28.10.18</div>
                        </div>
                        <div class="question__content-text">Здравствуйте,хочу начать работать с продукцией Матрикс!Подскажите с какого семинара мне начать знакомство с продукцией?Опыт работы 6 лет,работала на другой марке.</div>
                    </div>
                @endforelse
            </div>
        </div>
        @include('front.blocks.recommend-products', [
          'title' => 'Рекомендуемые товары',
       ])
    </div>
@endsection
