<?php

namespace App\Listeners\Form;

use App\Helpers\Sales\PromoCodeGenerator;
use App\Models\Shop\Sale;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendToServices implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $form = $event->form;

        $this->sendToSendPulse($form);

        $this->sendToBitrix24($form);
    }

    protected function sendToSendPulse($form)
    {
        if (!empty($form->data['email'])) {

            if ($form->type == 'subscribers' || //форма подписки
                ($form->type == 'cooperation' && ! empty($form->data['subscribe'])) // форма сотрудничества + отметил "подпис. на новости"
            ) {

                if (variable('sendpulse_address_book_id')) {
                    // Generate promocode
                    $code = '';
                    if (variable('sale_id_for_subscribers') && ($sale = Sale::find(variable('sale_id_for_subscribers')))) {
                        $codeGenerator = new PromoCodeGenerator();
                        $code = $codeGenerator->generateOne();
                        $sale->promoCodes()->create([
                            'code' => $code,
                            'transferred' => true,
                        ]);
                    }

                    $additionalParams = [];
                    if (variable('sendpulse_confirmation_sender_email')) {
                        $additionalParams = [
                            'confirmation' => 'force',
                            'sender_email' => variable('sendpulse_confirmation_sender_email'),
                        ];
                    }
                    try {
                        app('SendPulse')->addEmails(variable('sendpulse_address_book_id'), [
                            [
                                'email' => $form->data['email'],
                                'variables' => [
                                    'phone' => $form->data['phone'] ?? '',
                                    'name' => $form->data['name'] ?? '',
                                    'promocode' => $code,
                                ],
                            ],

                        ], $additionalParams);
                    } catch (\Exception $exception) {
                        \Log::error($exception->getMessage());
                    }
                }
            }
        }
    }

    protected function sendToBitrix24($form)
    {
        if (variable('bitrix24_host') && variable('bitrix24_user') && variable('bitrix24_hook_code')) {

            $subject = config("web-forms.$form->type.email.subject", $form->type);

            try {

                // форма "Сотрудничество"
                if ($form->type == 'cooperation') {
                    $b24 = new \Fomvasss\Bitrix24ApiHook\Bitrix24(variable('bitrix24_host'), variable('bitrix24_user'), variable('bitrix24_hook_code'));

                    $b24->crmLeadAdd([
                        "fields" => [
                            'TITLE' => $subject,
                            'NAME' => $form->data['name'] ?? '',
                            'EMAIL' => [
                                ['VALUE' => $form->data['email'] ?? '',],
                            ],
                            'PHONE' => [
                                ['VALUE' => $form->data['phone'] ?? '']
                            ],
                            'COMMENTS' => $form->data['message'] ?? '',
                            'ADDRESS_CITY' => $form->data['city'],
                            // Город, Адрес??? ADDRESS
                            //'UF_CRM_1556001206' => ['VALUE' => optional($form->terms->where('vocabulary', 'types_trade_services')->first())->name], //Вид торг. услуг
                            'UF_CRM_1556001206' => optional($form->terms->where('vocabulary', 'types_trade_services')->first())->name,
                            //Вид торг. услуг
                            'UF_CRM_1556001258' => empty($form->data['subscribe']) ? false : true,
                            //Подписался на новости
                        ],
                        'params' => ["REGISTER_SONET_EVENT" => "Y"],
                    ]);
                    // форма "Купить в один клик"
                } elseif ($form->type == 'buy_one_click') {
                    $b24 = new \Fomvasss\Bitrix24ApiHook\Bitrix24(variable('bitrix24_host'), variable('bitrix24_user'), variable('bitrix24_hook_code'));

                    if (! empty($form->data['product_id']) && ($product = \App\Models\Shop\Product::find($form->data['product_id']))) {
                        // Создаем сделку
                        $b24Deal = $b24->crmDealAdd([
                            "fields" => [
                                'TITLE' => $subject,
                                'CATEGORY_ID' => 22,
                                // направление
                                'UF_CRM_5BE2FEEDAF7DC' => $form->created_at->toDateTimeString(),
                                // дата заявки
                                //'DATE_CREATE' => $form->created_at->toDateTimeString(),            // дата заявки
                            ],
                            'params' => ["REGISTER_SONET_EVENT" => "Y"],
                        ]);
                        // Получаем товар с Bitrix24 (по Sku - XML_ID)
                        $b24Product = $b24->crmProductList([
                            'filter' => [
                                'XML_ID' => $product->sku,
                            ],
                            'select' => 'ID',
                        ]);
                        // Добавляем к сделки товар
                        if (isset($b24Product['result'][0]['ID'])) {
                            $b24->crmDealProductrowsSet([
                                'id' => $b24Deal['result'],
                                'rows' => [
                                    [
                                        'PRODUCT_ID' => $b24Product['result'][0]['ID'],
                                        'PRICE' => round($product->getCalculatePrice('price') / 100),
                                        'QUANTITY' => 1,
                                    ],
                                ],
                            ]);
                        } else {
                            \Log::error(__METHOD__."Product SKU:$product->sku not found in Bitrix24 base");
                        }
                        // Создаем контакт
                        $b24Contact = $b24->crmContactAdd([
                            'fields' => [
                                'NAME' => $form->data['name'] ?? '',
                                'PHONE' => [
                                    ['VALUE' => $form->data['phone'] ?? '']
                                ],
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
                    }
                }
            } catch (\Exception $exception) {
                \Log::error($exception->getMessage());
                \Log::error("Form ID: $form->id");
            }
        }
    }
}
