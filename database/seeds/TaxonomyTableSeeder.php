<?php

use Illuminate\Database\Seeder;

class TaxonomyTableSeeder extends Seeder
{
    protected $termModel;

    protected $vocabularyModel;

    /**
     * TaxonomyTableSeeder constructor.
     */
    public function __construct()
    {
        $this->termModel = config('taxonomy.models.term');
        $this->vocabularyModel = config('taxonomy.models.vocabulary');
    }
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->seedVocabularies($this->getData());
    }

    /**
     * Specify the taxonomy structure you need.
     *
     * @return array
     */
    public function getData()
    {
        // This options is example:
        return [
            [
                'system_name' => 'product_categories',
                'name' => 'Категории товаров',
                'description' => 'Категории товаров магазина',
                'options' => json_encode([
                    'has_hierarchy' => 1,
                ]),
                'terms' => [
                    ['name' => 'Уход за волосами',
                        'terms' => [
                            ['name' => 'Кондеционер', ],
                            ['name' => 'Маска', ],
                            ['name' => 'Несмиваемый уход', ],
                            ['name' => 'Бальзам', ],
                            ['name' => 'Лосьон', ],
                            ['name' => 'Масло', ],
                            ['name' => 'Крем', ],
                            ['name' => 'Сыворотки', ],
                            ['name' => 'Наборы', ],
                        ],
                    ],['name' => 'Стайлинг',

                    ],
                    ['name' => 'Окрашивание',
                        'terms' => [
                            ['name' => 'Для всех типов волос', ],
                            ['name' => 'Для жырних волос', ],
                            ['name' => 'Для сухих волос', ],
                        ],
                    ],
                ],
            ],
            [
                'system_name' => 'order_statuses',
                'name' => 'Статусы заказов',
                'description' => 'Статусы заказов',
                'options' => json_encode([
                    'has_hierarchy' => 0,
                ]),
                'terms' => [
                    ['name' => 'Новый заказ', 'description' => '', 'system_name' => 'order_new', 'safe' => 1, 'options' => json_encode(['admin_style' => 'label label-info'])],
                    ['name' => 'Отправлен клиенту', 'description' => '', 'system_name' => 'order_shipping', 'options' => json_encode(['admin_style' => 'label label-warning'])],
                    ['name' => 'Успешно получен', 'description' => '', 'system_name' => 'order_accept', 'safe' => 1, 'options' => json_encode(['admin_style' => 'label label-success'])],
                    ['name' => 'Отклонен', 'description' => '', 'system_name' => 'order_rejected', 'safe' => 1, 'options' => json_encode(['admin_style' => 'label label-danger'])],
                    ['name' => 'Отказ', 'description' => '', 'system_name' => 'order_refund', 'options' => json_encode(['admin_style' => 'label label-danger'])],
                ],
            ],
            [
                'system_name' => 'payment_statuses',
                'name' => 'Статусы оплат',
                'description' => 'Статусы оплаты товаров/услуг',
                'terms' => [
                    ['name' => 'Не оплачено', 'description' => 'Новый платеж, ожидает оплаты', 'system_name' => 'payment_new', 'safe' => 1, 'options' => json_encode(['admin_style' => 'label label-warning'])],
                    ['name' => 'Оплата успешна', 'description' => 'Оплата проведена успешно', 'system_name' => 'payment_success', 'safe' => 1, 'options' => json_encode(['admin_style' => 'label label-success'])],
                    ['name' => 'Ошибка оплаты', 'description' => 'Оплата не проведена', 'system_name' => 'payment_fail', 'safe' => 1, 'options' => json_encode(['admin_style' => 'label label-danger'])],
                ],
            ],
            [
                'system_name' => 'types_trade_services',
                'name' => 'Виды торговых услуг',
                'description' => 'Виды торгивельних услуг',
                'terms' => [
                    ['name' => 'Покупка',],
                    ['name' => 'Продажа',],
                    ['name' => 'Реклама',],
                ],
            ],
            [
                'system_name' => 'faq_subjects',
                'name' => 'Темы для FAQ',
                'description' => 'Темы "ЧАСТО ЗАДАВАЕМЫЕ ВОПРОСЫ"',
                'terms' => [
                    ['name' => 'Покупка товара',],
                    ['name' => 'Магазины',],
                    ['name' => 'Доставка и оплата',],
                ],
            ],
        ];
    }

    /**
     * @param array $vocabularies
     */
    protected function seedVocabularies(array $vocabularies)
    {
        foreach ($vocabularies as $item) {
            $vocabulary = $this->vocabularyModel::updateOrCreate([
                'system_name' => $item['system_name'], // TODO: For this test name is unique !!!
            ], [
                'name' => $item['name'],
                'description' => $item['description'] ?? null,
                'options' => $item['options'] ?? null,
            ]);
            // $this->command->info("Vocabulary saved: $vocabulary->name ($vocabulary->id)");

            if (! empty($item['terms'])) {
                $this->seedTerms($item['terms'], $vocabulary);
            }
        }
    }

    /**
     * @param array $terms
     * @param int $vocabulary
     * @param null $parentId
     */
    protected function seedTerms(array $terms, $vocabulary, $parent_id = null)
    {
        foreach ($terms as $item) {
            $term = $this->termModel::updateOrCreate([
                'name' => $item['name'], // TODO: For this test name is unique !!!
            ], [
                'system_name' => isset($item['system_name']) ? str_slug($item['system_name'], '_') : null,
                'description' => $item['description'] ?? null,
                'options' => $item['options'] ?? null,
                'vocabulary' => $vocabulary->system_name,
                'parent_id' => $parent_id,
                'safe' => $item['safe'] ?? 0,
            ]);

            // $this->command->info(" - Term saved: $term->name ($term->id)");

            if (! empty($item['terms'])) {
                $this->seedTerms($item['terms'], $vocabulary, $term->id);
            }
        }
    }
}
