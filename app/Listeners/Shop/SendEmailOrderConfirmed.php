<?php

namespace App\Listeners\Shop;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEmailOrderConfirmed implements ShouldQueue
{

    use InteractsWithQueue;

    //public $connection = 'sqs';

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $order = $event->order;

        if (variable('mail_to_address')) {
            $mails = array_map(function ($mail) {
                return trim($mail);
            }, explode(',', variable('mail_to_address')));
            \Mail::to($mails)->send(new \App\Mail\CustomMail('Заказ Hipertin', 'emails.admin.after-order-confirm', [
                    'order' => $order,
                ]));
        }

        if ($user = $order->user) {
            \Mail::to($order->user)
                ->send(new \App\Mail\CustomMail('Ваш заказ принят', 'emails.front.after-order-confirm',  [
                    'user' => $user, 'order' => $order,
                ]));
        }

        $this->sendToSendPulse($order);

        $this->sendToBitrix24($order);
    }

    protected function sendToSendPulse($order)
    {
        if ($email = $order->user->email ?? $order->data['delivery']['email'] ?? '') {
            $additionalParams = [];
            if (variable('sendpulse_confirmation_sender_email')) {
                $additionalParams = [
                    'confirmation' => 'force',
                    'sender_email' => variable('sendpulse_confirmation_sender_email'),
                ];
            }

            $mainParams = [
                [
                    'email' => $email,
                    'variables' => [
                        'phone' => $order->user->phone ?? $order->data['delivery']['phone'] ?? '',
                        'name' => optional($order->user)->name ?? '',
                    ],
                ],
            ];

            try {
                if (variable('sendpulse_address_book_id_order')) {
                    app('SendPulse')->addEmails(variable('sendpulse_address_book_id_order'), $mainParams/*, $additionalParams*/);
                }
                if (variable('sendpulse_address_book_id')) {
                    app('SendPulse')->addEmails(variable('sendpulse_address_book_id'), $mainParams, $additionalParams);
                }
            } catch (\Exception $exception) {
                \Log::error($exception->getMessage());
            }
        }
    }

    protected function sendToBitrix24($order)
    {
        if (variable('bitrix24_host') && variable('bitrix24_user') && variable('bitrix24_hook_code')) {
            try {
                $b24 = new \Fomvasss\Bitrix24ApiHook\Bitrix24(variable('bitrix24_host'), variable('bitrix24_user'), variable('bitrix24_hook_code'));

                // Тип (метод) оплаты
                $paymentMethod = '-';
                if (($order->data['payment']['method'] ?? '') == 'upon_receipt') {
                    $paymentMethod = 'Наличные';
                } elseif (($order->data['payment']['method'] ?? '') == 'yandex') {
                    $paymentMethod = 'Банковский перевод';
                }

                // Метод доставки
                $deliveryMethod = '-';
                if (($order->data['delivery']['method'] ?? '') == 'cdek') {
                    $deliveryMethod = 'СДЕК до ПВЗ';
                    if (($order->data['delivery']['tariff'] ?? '') == '137') {
                        $deliveryMethod = 'СДЕК до двери';
                    }
                } elseif (($order->data['delivery']['method'] ?? '') == 'courier') {
                    $deliveryMethod = 'Курьер';
                } elseif (($order->data['delivery']['method'] ?? '') == 'pickup') {
                    $deliveryMethod = 'Самовывоз';
                }

                // Создаем сделку
                $b24Deal = $b24->crmDealAdd([
                    "fields" => [
                        'CATEGORY_ID' => 22, // направление
                        'TITLE' => 'Заказ Hipertin #'.$order->number,
                        'UF_CRM_1556009229' => $order->number,                                      // Номер заказа
                        'UF_CRM_5BE2FEEDAF7DC' => optional($order->ordered_at)->toDateTimeString(),    // Дата оформления
                        //'DATE_CREATE' => optional($order->ordered_at)->toDateTimeString(),        // Дата оформления
                        'UF_CRM_1556007022' => optional($order->txPaymentStatus)->name ?? '-',   // Статус оплаты
                        'OPPORTUNITY' => round($order->getFinalSumStr(false) / 100),                       // Сумма заказа
                        'UF_CRM_1556006288' => round(($order->data['purchase']['discount'] ?? 0) / 100),   // Скидка
                        'UF_CRM_1554383194777' => round(($order->data['purchase']['delivery'] ?? 0) / 100),   // Стоимость доставки
                        'UF_CRM_1556006247' => round($order->getFinalSumStr(false) / 100),                 // Сумма к оплате
                        'UF_CRM_1554391710563' => $order->data['sales']['promocode'] ?? '',       // Промокод

                        'UF_CRM_1556007056' => $paymentMethod,                                      // Метод оплаты
                        'UF_CRM_1556006202' => $deliveryMethod,                                     // Метод доставки
                        'UF_CRM_1556006122' => $order->data['delivery']['address'] ?? '',        // TODO: Адрес доставки заказа
                    ],
                    'params' => ["REGISTER_SONET_EVENT" => "Y"],
                ]);

                // Получаем товар с Bitrix24 (по Sku - XML_ID)
                $b24Products = $b24->crmProductList([
                    'filter' => [
                        'XML_ID' => $order->products->pluck('sku')->toArray(),
                    ],
                    'select' => ['ID', 'XML_ID'],
                ]);

                $prods = [];
                foreach ($b24Products['result'] ?? [] as $prod) {
                    $prods[$prod['XML_ID']] = $prod['ID'];
                }

                $rows = [];
                foreach ($order->products as $product) {
                    if (isset($prods[$product->sku])) {
                        $rows[] = [
                            'PRODUCT_ID' => $prods[$product->sku],
                            'PRICE' => $product->pivot->price / 100,
                            'QUANTITY' => $product->pivot->quantity,
                        ];
                    } else {
                        \Log::error(__METHOD__."Product SKU:$product->sku not found in base Bitrix24 (Order id: $order->id)");
                    }
                }

                // Добавляем к сделки товары
                $b24->crmDealProductrowsSet([
                    'id' => $b24Deal['result'],
                    'rows' => $rows,
                ]);
//\Log::info('Fields', $b24->crmContactFields());
                // Создаем контакт
                $b24Contact = $b24->crmContactAdd([
                    'fields' => [
                        'NAME' => $order->data['delivery']['name'] ?? '',
                        'PHONE' => [
                            ['VALUE' => $order->data['delivery']['phone'] ?? '',]
                        ],
                        'EMAIL' => [
                            ['VALUE' => $order->data['delivery']['email'] ?? '',]
                        ],
                        'ADDRESS_CITY' => $order->data['delivery']['city'] ?? '',
                        'UF_CRM_1527773053452' => [1044], //Направление
                    ],
                ]);

                // Добавляем к сделке контакт
                if (isset($b24Contact['result']) && ($b24ContactId = $b24Contact['result'])) {
                    $b24->crmDealContactItemsSet([
                        'id' => $b24Deal['result'],
                        'items' => [
                            ['CONTACT_ID' => $b24Contact['result']]
                        ]
                    ]);
                }
            } catch (\Exception $exception) {
                \Log::error($exception->getMessage());
                \Log::error("Order ID $order->id");
            }
        }
    }
}
