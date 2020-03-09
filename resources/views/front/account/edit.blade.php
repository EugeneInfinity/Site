@extends('front.layouts.app')

@php
    MetaTag::setDefault(['title' => 'Личные данные']);
@endphp

@section('content')
    <div class="card-product card-product_personal">
        <div class="card-product__wrapper">

            <div class="card-product__nav">
                {!! Breadcrumbs::render('account.edit') !!}
            </div>

            <div class="personal">
                <div class="personal__wrapper">
                    <div class="personal__head">
                        <h1 class="personal__name">Личные данные</h1>
                        <div class="personal__text">Здесь вы можете изменить ваши персональные данные, настроить уведомления, посмотреть историю заказов и др.</div>
                    </div>
                </div>
                <div class="personal__content">
                    <div class="line"></div>
                    <div class="personal__menu">
                        <ul>
                            <li class="active"><a href="{{ route('account.edit') }}">Личные данные</a></li>
                            <li><a href="{{ route('account.history') }}">История заказов</a></li>
                            <li><a href="{{ route('account.favorites') }}">Избранное </a></li>
                        </ul>
                        <a href="#" class="out js-action-click" data-url="{{ route('logout') }}">
                            Выйти
                        </a>
                    </div>
                    <div class="personal__info">
                        <div class="personal-block">

                        @if ($message = Session::pull('success'))
                            <div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <h4><i class="icon fa fa-check"></i> {{ trans('notifications.excellent') }}</h4>
                                {{ $message }}
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                                </ul>
                            </div>
                        @endif

                            <form action="{{ route('account.update') }}" method="POST" class="js-ajax-form-submit" data-id="account-update">
                                @csrf
                                <div class="form-wrapper">
                                    <div class="form-block">
                                        <div class="form-block__name">ПЕРСОНАЛЬНЫЕ ДАННЫЕ</div>
                                        <div class="form-group">
                                            <label for="">Имя, фамилия* </label>
                                            <input class="input" type="text" name="name" value="{{ $user->name }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Фамилия </label>
                                            <input class="input" type="text" name="last_name" value="{{ $user->last_name }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Электронная почта* </label>
                                            <input class="input" type="email" name="email" value="{{ $user->email }}">
                                        </div>
                                        {{--
                                        <div class="form-group form-group_check">
                                            <label>
                                                <input type="hidden" name="data[notifications]" value="0">
                                                <input class="checkbox" type="checkbox" name="data[notifications]" value="1" @if(!empty($user->data['notifications'])) checked @endif>
                                                <span class="checkbox-custom"></span>
                                                <span class="label">Получать уведомления и новости </span>
                                            </label>
                                        </div>
                                        --}}
                                        <div class="form-group">
                                            <label for="">Номер телефона* </label>
                                            <input class="input phone1" type="text" name="phone" value="{{ $user->phone }}">
                                        </div>
                                        <div class="form-group form-group_check">
                                            <label>
                                                <input type="hidden" name="data[subscriber]" value="0">
                                                <input class="checkbox" type="checkbox" name="data[subscriber]" value="1" @if(!empty($user->data['subscriber'])) checked @endif>
                                                <span class="checkbox-custom"></span>
                                                <span class="label">Подписаться на новости и акции</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-block">
                                        <div class="form-block__name">ИЗМЕНЕНИЕ ПАРОЛЯ</div>
                                        <div class="form-group">
                                            <label for="">Старый пароль </label>
                                            <input class="input" autocomplete="off" type="password" name="password_current">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Новый пароль </label>
                                            <input class="input" autocomplete="off" type="password" name="password" data-validator-options='{"relatedSelectors":["[name=password_confirmation]"]}'>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Повторите пароль </label>
                                            <input class="input" autocomplete="off" type="password" name="password_confirmation">
                                        </div>
                                    </div>
                                    <div class="form-block contact-fields">
                                        <div class="form-block__name form-block__name_inner">АДРЕС ДОСТАВКИ ПО УМОЛЧАНИЮ</div>
                                        <div class="form-group form-group_select">
                                            <div class="validate">
                                                <select name="contact_id" class="select-personal">
                                                    @empty($user->contact_id)
                                                    <option value="" selected disabled>Укажите адрес по умолчанию</option>
                                                    @endempty
                                                    @foreach($user->contacts as $contact)
                                                    <option value="{{ $contact->id }}" @if($contact->id == $user->contact_id) selected @endif data-contact="{{ $contact }}">{{ $contact->full }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-block__name">РЕДАКТИРОВАНИЕ АДРЕСА</div>
                                        <div class="form-group">
                                            <label for="">Город </label>
                                            <input class="input" type="text" name="contact[city]" value="{{ optional($user->contact)->city }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Регион, область </label>
                                            <input class="input" type="text" name="contact[region]" value="{{ optional($user->contact)->region }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Индекс </label>
                                            <input class="input" type="text" name="contact[zip_code]" value="{{ optional($user->contact)->zip_code }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Адрес </label>
                                            <input class="input" type="text" name="contact[address]" value="{{ optional($user->contact)->address }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-button">
                                    <button class="btn-gen" type="submit">Сохранить</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection