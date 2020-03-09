<?php
/**
 * Created by PhpStorm.
 * User: fomvasss
 * Date: 08.03.19
 * Time: 12:33
 */

namespace App\Services;

use CdekSDK\Requests\CalculationRequest;
use CdekSDK\Requests\CitiesRequest;
use CdekSDK\Requests\PvzListRequest;
use CdekSDK\Requests\RegionsRequest;

class Cdek
{
    protected $client;

    /**
     * Cdek constructor.
     *
     * @param $client
     */
    public function __construct()
    {
        //$this->client = new \CdekSDK\CdekClient(config('services.cdek.account'), config('services.cdek.password'));
        $this->client = app(\CdekSDK\CdekClient::class);
    }

    /**
     * Почтовые отделения для города.
     *
     * @param int $cityId
     * @return array
     */
    public function getPwz(int $cityId, string $pwzCode = '')
    {
        $request = new PvzListRequest();
        $request->setCityId($cityId);
        $request->setType(PvzListRequest::TYPE_ALL);
        $request->setCashless(true);
        $request->setCodAllowed(true);
        $request->setDressingRoom(true);

        $response = $this->client->sendPvzListRequest($request);
        if ($response->hasErrors()) {
            \Log::error(__METHOD__." Error CDEK response");
            // обработка ошибок
        }

        if ($pwzCode) {
            foreach ($response as $item) {
                if ($item->Code == $pwzCode) {
                    return $item;
                }
            }
            return null;
        }

        return $response;
    }

    public function getPwzFromCache(int $cityId, string $pwzCode = '')
    {
        $response = \Cache::remember(md5(serialize([$cityId, $pwzCode])), 5, function () use ($cityId, $pwzCode) {
           return $this->getPwz($cityId, $pwzCode);
        });

        return $response;
    }

    /**
     * Калькулятор стоимости доставки.
     *
     * @return float|null
     */
    public function getCalculationDeliveryPrice(array $params, array $options = [])
    {
        //$request = new CalculationRequest();
        $request = CalculationRequest::withAuthorization();

        //$request->setSenderCityId('44')
        //    ->setReceiverCityPostCode('652632')
        //    ->addTariffToList(1,2)
        //    ->addTariffToList(8,1)
        //    ->addPackage([
        //        'weight' => 0.2,
        //        'length' => 25,
        //        'width'  => 15,
        //        'height' => 10,
        //    ]);
        //
        //$response = $this->client->sendCalculationWithTariffListRequest($request);
        //dd($response);

        list($senderCityId, $receiverCityId, $tariffId, $productsParams) = $params;

        $request
            ->setSenderCityId((int)$senderCityId)
            ->setReceiverCityId((int)$receiverCityId)
            ->setTariffId((int)$tariffId)
            ;

        foreach ($productsParams as $product) {
            $request->addPackage($product);
        }

        $response = $this->client->sendCalculationRequest($request);

        if ($response->hasErrors()) {
            // обработка ошибок
            if (empty($options['no_log'])) {
                //dd($request);
                \Log::error(__METHOD__." Error CDEK response: " . serialize($response->getErrors()));
            }
            return 0;
        }

        /** @var \CdekSDK\Responses\CalculationResponse $response */
        return $response;
    }

    public function getCalculationDeliveryPriceFromCache(array $params, array $options = [])
    {
        $cacheKey = md5(serialize($params));
        $response = \Cache::remember($cacheKey, 10, function () use ($params) {
            return $this->getCalculationDeliveryPrice($params);
        });

        return $response ?: $this->getCalculationDeliveryPrice($params, $options);
    }
}