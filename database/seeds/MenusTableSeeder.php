<?php

class MenusTableSeeder extends MenusBaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('menu_items')->delete();
        DB::table('menus')->delete();
        //DB::table('menu_items')->truncate();
        //DB::table('menus')->truncate();

        $this->seedMenu($this->getData());

        $this->command->info('Menu seed success!');
    }

    public function getData(): array
    {
        return [
            [
                'name' => 'Главное меню',
                'system_name' => 'main_menu',
                'data' => [
                    'has_hierarchy' => 1,
                ],
                'safe' => true,
                'children' => [
                    [
                        'name' => 'Акции',
                        'path' => 'sales',
                        'children' => [
                            [
                                'name' => 'Акции 1',
                                'children' => [
                                    ['name' => 'Акции 11'],
                                    ['name' => 'Акции 12'],
                                    ['name' => 'Акции 13'],
                                    ['name' => 'Акции 14'],
                                    ['name' => 'Акции 15'],
                                    ['name' => 'Акции 16'],
                                ],
                            ],
                            [
                                'name' => 'Акции 2',
                                'children' => [
                                    ['name' => 'Акции 21'],
                                    ['name' => 'Акции 22'],
                                    ['name' => 'Акции 23'],
                                    ['name' => 'Акции 24'],
                                ],
                            ],
                            [
                                'name' => 'Акции 3',
                                'children' => [
                                    ['name' => 'Акции 31'],
                                    ['name' => 'Акции 32'],
                                    ['name' => 'Акции 33'],
                                    ['name' => 'Акции 34'],
                                    ['name' => 'Акции 35'],
                                    ['name' => 'Акции 36'],
                                    ['name' => 'Акции 37'],
                                ],
                            ],
                        ]
                    ],
                    ['name' => 'Уход за волосами', 'path' => 'ukhod-za-volosami'],
                    ['name' => 'Стайлинг', 'path' => 'stayling'],
                    ['name' => 'Окрашивание', 'path' => 'okrashivanie'],
                    ['name' => 'Обучение', 'path' => 'study'],
                    ['name' => 'Сотрудничество', 'path' => 'cooperation'],
                    ['name' => 'О компании', 'path' => 'about'],
                ],
            ],
            [
                'name' => 'Слайдер на главной',
                'system_name' => 'slider_in_home',
                'data' => [
                    'has_hierarchy' => 0,
                ],
                'safe' => true,
                'children' => [
                    ['name' => 'Изображение 1', 'path' => 'product/15',],
                    ['name' => 'Изображение 2', 'path' => 'product/13',],
                    ['name' => 'Изображение 3', 'path' => 'product/32',],
                ],
            ],
            [
                'name' => 'Социальные сети',
                'system_name' => 'social_networks',
                'data' => [
                    'has_hierarchy' => 0,
                ],
                'safe' => true,
                'children' => [
                    ['name' => 'ВК', 'path' => 'https://vk.com', 'target' => '_blank',],
                    ['name' => 'FB', 'path' => 'https://www.facebook.com', 'target' => '_blank',],
                    ['name' => 'OK', 'path' => 'https://ok.ru', 'target' => '_blank',],
                    ['name' => 'TW', 'path' => 'https://twitter.com', 'target' => '_blank',],
                ],
            ],
            [
                'name' => 'Информационные разделы',
                'system_name' => 'info_part',
                'data' => [
                    'has_hierarchy' => 0,
                ],
                'safe' => true,
                'children' => [
                    ['name' => 'Оплата и доставка', 'path' => 'payment'],
                    ['name' => 'Подписка на новости', 'path' => '#subscribe-news-modal'],
                    ['name' => 'FAQ', 'path' => 'faq'],
                    ['name' => 'Обратная связь', 'path' => 'contacts'],
                    ['name' => 'Где купить', 'path' => 'buy'],
                    ['name' => 'Политика конфиденциальности', 'path' => 'policy'],
                    ['name' => 'Задать вопрос стилисту', 'path' => 'questions'],
                ],
            ],

        ];
    }
}
