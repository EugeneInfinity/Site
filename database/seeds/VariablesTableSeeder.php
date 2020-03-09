<?php

use Illuminate\Database\Seeder;

class VariablesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('variables')->truncate();

        DB::table('variables')->insert([
            [
                'key' => 'app_name',
                'value' => config('app.name'),
            ],
            [
                'key' => 'mail_from_address',
                'value' => 'no-reply@info.net',
            ],
            [
                'key' => 'mail_from_name',
                'value' => config('app.name'),
            ],
            [
                'key' => 'mail_to_address',
                'value' => 'bob@example.com',
            ],
            [
                'key' => 'mail_to_name',
                'value' => 'Bob Dilan',
            ],
            [
                'key' => 'company_email',
                'value' => 'namemail@mail.com',
            ],
            [
                'key' => 'company_phone',
                'value' => '+7-900-000-00-00',
            ],
            [
                'key' => 'company_work_schedule',
                'value' => 'Пн-Пт, с 10:05 до 18:05',
            ],
            [
                'key' => 'text_info_delivery',
                'value' => 'Текст о доставке, на карточку товаров',
            ],
            [
                'key' => 'contact_latitude',
                'value' => '55.759616',
            ],
            [
                'key' => 'contact_longitude',
                'value' => '37.625457',
            ],
            [
                'key' => 'contact_map_zoom',
                'value' => '18',
            ],
            [
                'key' => 'home_page_bestsellers',
                'value' => '["2","3","5","8","4"]',
            ],
            [
                'key' => 'url_cookie_info',
                'value' => '#',
            ],
            [
                'key' => 'delivery_methods',
                'value' => '[{"key":"pickup","value":"\u0421\u0430\u043c\u043e\u0432\u044b\u0432\u043e\u0437","safe":"1"},{"key":"cdek","value":"CDEK","safe":"1"},{"key":"post_russia","value":"\u041f\u043e\u0447\u0442\u0430 \u0420\u043e\u0441\u0441\u0438\u0438","safe":"1"}]',
            ],
            [
                'key' => 'delivery_methods_price',
                'value' => '[{"key":"pickup","value":"0","safe":"1"},{"key":"cdek","value":"300","safe":"1"},{"key":"post_russia","value":"250","safe":"1"}]',
            ],
            [
                'key' => 'payment_methods',
                'value' => '[{"key":"upon_receipt","value":"\u041e\u043f\u043b\u0430\u0442\u0430 \u043f\u0440\u0438 \u043f\u043e\u043b\u0443\u0447\u0435\u043d\u0438\u0438","safe":"1"},{"key":"yandex","value":"\u042f\u043d\u0434\u0435\u043a\u0441.\u0414\u0435\u043d\u044c\u0433\u04382: \u0412\u044b\u0434\u0430\u0447\u0430 \u0437\u0430\u043a\u0430\u0437\u0430 \u043f\u043e \u043f\u0430\u0441\u043f\u043e\u0440\u0442\u0443","safe":"1"}]',
            ],
            [
                'key' => 'faq_questions',
                'value' => '[{"question":"\u041a\u0430\u043a \u043d\u0430\u0439\u0442\u0438 \u0434\u0438\u0441\u0442\u0440\u0438\u0431\u044c\u044e\u0442\u043e\u0440\u0430 \u0432 \u043c\u043e\u0435\u043c \u0440\u0435\u0433\u0438\u043e\u043d\u0435?","answer":"\u0417\u0430\u043f\u043e\u043b\u043d\u0438\u0442\u0435 \u0437\u0430\u044f\u0432\u043a\u0443 \u0437\u0434\u0435\u0441\u044c \u0438 \u0441 \u0412\u0430\u043c\u0438 \u0441\u0432\u044f\u0436\u0435\u0442\u0441\u044f \u043a\u043e\u043c\u043c\u0435\u0440\u0447\u0435\u0441\u043a\u0438\u0439 \u043f\u0440\u0435\u0434\u0441\u0442\u0430\u0432\u0438\u0442\u0435\u043b\u044c."}]',
            ],
            [
                'key' => 'products_is_bestseller_rating',
                'value' => '3',
            ],
            [
                'key' => 'page_home_subscribe_text',
                'value' => '<h2>Подписаться на новости</h2><p class="home-content__bottom-text">Подпишитесь и получайте последние обновления Hipertin. Укажите ваш почтовый адрес, чтобы получать самые актуальные новости о запусках, новинках и акциях.</p><p class="home-content__bottom-text">Оставьте свой e-mail и получите скидку 10% на следующий заказ!</p>',
            ],

            [
                'key' => 'delivery_pickup_address',
                'value' => 'г. Москва, 1-й варшавский проезд, 2 стр. 9а',
            ],[
                'key' => 'delivery_pickup_phone',
                'value' => '+79111111111, +7222222222',
            ],[
                'key' => 'delivery_pickup_work',
                'value' => 'Вс 10:00-16:00, Сб 10:00-16:00, Пн-Пт 10:00-20:00',
            ],[
                'key' => 'page_contacts_address',
                'value' => '<h2 class="address-title">Адрес</h2><div class="adress-wrapper"><div class="other-block"><p class="adress-wrapper__uni">Россия, Москва</p><p class="adress-wrapper__uni">Кронштадтский бульвар, д. 14с3</p><p class="adress-wrapper__uni">Метро Водный стадион</p></div><div class="other-block"><p class="adress-wrapper__uni">Тел.: Адрес: +7 (499) 506-72-11</p><p class="adress-wrapper__uni">Часы работы: 10:00-18:30</p></div></div>',
            ],
        ]);

        \Cache::forget('laravel.variables.cache');

        $this->command->info('Vars seed success!');
    }
}
