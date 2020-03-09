<?php

namespace App\Http\Controllers\Front\Shop;

use App\Cart\ProductCart;
use App\Events\Shop\OrderConfirmed;
use App\Helpers\ShoppingCart\Favorite;
use App\Helpers\ShoppingCart\CartCookieStorageDriver;
use App\Helpers\ShoppingCart\CartEloquentStorageDriver;
use App\Http\Requests\Front\Shop\ShoppingCartCartOrderRequest;
use App\Http\Requests\Front\Shop\ShippingCartFormRequest;
use App\Mail\PlainMail2;
use App\Managers\OrderManager;
use App\Managers\UserManager;
use App\Models\Shop\Order;
use App\Models\Shop\Product;
use App\Models\Shop\Sale;
use App\Models\Shop\SalePromoCode;
use App\Models\User;
use App\Services\Cdek;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ShoppingCartController extends Controller
{
    protected $orderManager;

    protected $userManager;

    /**
     * ShoppingCartController constructor.
     */
    public function __construct(OrderManager $orderManager, UserManager $userManager)
    {
        $this->orderManager = $orderManager;

        $this->userManager = $userManager;
    }

    /**
     * Show products in cart.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if (empty(\Cart::getIds())) {
            return redirect('/');
        }

        list($cart, $purchase, $delivery) = $this->getCartData($request);

        $recommendation = $this->getRecommendationProduct($cart['products']);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('front.shopping-cart.inc.cart-content', compact('user', 'cart', 'purchase', 'delivery'))->render(),
            ]);
        }

        return view('front.shopping-cart.index', compact('cart', 'purchase', 'delivery', 'recommendation'));
    }

    /**
     * Add product to cart.
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Throwable
     */
    public function add(Request $request, $id, $amount = 1)
    {
        $product = Product::isPublish()->findOrFail($id);
        \Cart::add($product->id, $amount);

        $destination = $request->session()->pull('destination', \URL::previous());
        if ($request->ajax()) {
            return response()->json([
                'message' => 'Товар добавлен в корзину',
                'html' => view('front.products.inc.modal-cart')->render(),
            ]);
        }

        return redirect()->to($destination)
            ->with('success', trans('notifications.store.success'));
    }

    /**
     * Remove product from the Order-cart.
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     * @param int $count
     * @return int
     */
    public function remove(Request $request, $id, $amount = null)
    {
        $product = Product::isPublish()->findOrFail($id);

        if (!\Cart::remove($product->id, $amount)) {
            // Product does not exists in the cart in needed count
        }

        $destination = $request->session()->pull('destination', \URL::previous());
        if ($request->ajax()) {
            return response()->json([
                'message' => 'Товар удален с корзины',
                //'action' => 'redirect',
                //'html' => view('front.products.inc.modal-favorites')->render(),
                'destination' => $destination,
            ]);
        }

        return redirect()->to($destination)
            ->with('success', trans('notifications.destroy.success'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function clear(Request $request)
    {
        \Cart::clear();

        $destination = $request->session()->pull('destination', \URL::previous());
        if ($request->ajax()) {
            return response()->json([
                'message' => trans('notifications.store.success'),
                'action' => 'redirect',
                'destination' => $destination,
            ]);
        }

        return redirect()->to($destination)
            ->with('success', trans('notifications.store.success'));
    }

    /**
     * Save & confirm order.
     *
     * @param \App\Http\Requests\Front\Shop\ShoppingCartCartOrderRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function order(ShoppingCartCartOrderRequest $request)
    {
        if (Auth::check()) {
            // Пользователь авторизован
            $user = Auth::user();
        } elseif($user = User::where('email', $request->input('data.delivery.email') ?: "0")->orWhere('phone', $request->input('data.delivery.phone') ?: "0")->first()) {
            // Пользователь с указанным емейл/телефоному уже есть в БД, ему и будет заказ
            \Cart::setCurrentUserId($user->id)->merge(['eloquent', 'cookie']);
            \Cart::storage('eloquent');
            $request->session()->put('destination', route('home'));
        } else {
            // Создаем нового пользователя и логинем его
            $user = $this->userManager->create($request->input('data.delivery.name'), $request->input('data.delivery.email'), $request->input('data.delivery.phone'));
            Auth::loginUsingId($user->id, true);
            \Cart::storage('eloquent');
        }

        if (empty($user->phone)) {
            $user->phone = $request->input('data.delivery.phone');
            $user->save();
        }

        list($cart, $purchase, $delivery, $sales) = $this->getCartData($request);

        $deliveryData = [
            'method' => $request->input('data.delivery.method'),
            'name' => $request->input('data.delivery.name'),
            'email' => $request->input('data.delivery.email'),
            'phone' => $request->input('data.delivery.phone'),
            'city' => $request->input('data.delivery.city'),
            'price' => $delivery['price'],
        ];

        if ($request->input('data.delivery.method') == 'cdek') {
            $deliveryData['address'] = $request->input('data.delivery.address');
            $deliveryData['zip_code'] = $request->input('data.delivery.pwz_code');
            $deliveryData['tariff'] = $request->input('data.delivery.tariff');

        } elseif($request->input('data.delivery.method') == 'pickup') {
            $deliveryData['address'] = variable('delivery_pickup_address');

        } elseif ($request->input('data.delivery.method') == 'courier') {
            if ($request->contact_id) {
                $contact = $user->contacts()->where('id', $request->contact_id)->firstOrFail();
            } else {
                $contact = $user->contact()->create([
                    'name' => $request->input('data.delivery.name'),
                    'email' => $request->input('data.delivery.email'),
                    'phone' => $request->input('data.delivery.phone'),

                    'zip_code' => $request->input('data.delivery.zip_code'),
                    'region' => $request->input('data.delivery.region'),
                    'address' => $request->input('data.delivery.address'),
                ]);
            }
            $user->contact_id = $contact->id;
            $user->save();

            $deliveryData = array_merge($deliveryData, [
                'zip_code' => $contact->zip_code,
                'region' => $contact->region,
                'address' => $contact->address,
                'city' => $contact->city,
            ]);
        }

        $order = Order::firstOrCreate([
            'user_id' => $user->id,
            'type' => Order::TYPE_CART,
            'status' => 'order_new', // TODO safe status
        ]);

        $order->setAttribute('data->delivery', $deliveryData);
        $order->setAttribute('data->purchase', $purchase);
        $order->setAttribute('data->payment', ['method' => $request->input('data.payment.method')]);
        $order->setAttribute('data->sales', $sales->toArray() + ['promocode' => session()->get('cart.promocode')]);  // TODO
        $order->setAttribute('type', Order::TYPE_ORDER);
        $order->setAttribute('ordered_at', \Carbon\Carbon::now());
        $order->save();

        // Add present prod to order
        if (($presentId = session()->get('cart.product_present_id')) && Product::find($presentId)) {
            $order->products()->attach([$presentId => [
                'quantity' => 1,
                'price' => 0,
            ]]);
        }

        $codeValue = $request->input('cart.promocode');
        if ($codeValue && ($promoCode = SalePromoCode::isAvailable()->isActive()->where('code', $codeValue)->first())) {
            $promoCode->increment('used_count');
        }
        session()->forget('cart.promocode');
        session()->forget('cart.promocode_info');
        session()->forget('cart.product_present_id');

        event(new OrderConfirmed($order));

        $destination = $request->session()->pull('destination', route('account.history'));
        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'action' => 'redirect',
                'destination' => $destination,
            ]);
        }

        return redirect()->to($destination)
            ->with('success', trans('notifications.store.success'));
    }

    /**
     * Update shopping cart.
     *
     * @param \App\Http\Requests\Front\Shop\ShippingCartFormRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Throwable
     */
    public function form(ShippingCartFormRequest $request)
    {
        if ($request->has('products')) {
            foreach ($request->products as $id => $data) {
                \Cart::update($id, $data['amount'] ?? 1);
            }
        }

        list($cart, $purchase, $delivery) = $this->getCartData($request);

        $destination = $request->session()->pull('destination', \URL::previous());
        if ($request->ajax()) {
            return response()->json([
                'message' => trans('notifications.update.success'),
                //'action' => 'redirect',
                //'destination' => $destination,
                'html' => view('front.shopping-cart.inc.cart-content', compact('cart', 'purchase', 'delivery'))->render(),
                'htmlHeaderCart' => view('front.products.inc.modal-cart')->render(),
            ]);
        }

        return redirect()->to($destination)
            ->with('success', trans('notifications.destroy.success'));
    }


    protected function getCartData(Request $request)
    {
        // ID-ы всех товаров в корзине
        $productsIdInCart = \Cart::getIds();

        // модели товаров которые есть в корзине
        $cartProducts = Product::isPublish()
            ->whereIn('id', $productsIdInCart)->get();

        // товары в корзины
        $productsCounts = \Cart::get();

        // категории товаров в корзине
        $categoriesIdCartProducts = $cartProducts->pluck('category_id')->toArray();

        // общая сумма товаров
        $cartSumTotalProducts = $cartProducts->map(function ($product) use ($productsCounts) {
            return $product->getCalculatePrice('price') * $productsCounts[$product->id]; // TODO currency
        })->sum();

        // полученная скидка
        $discountSum = 0;

        // премененные промокоды (промокод)
        $promoCodesId = [];
        if ($request->get('remove_promocode')) {
            session()->forget('cart.promocode');
        } elseif ($request->purpose == 'promocode' && ($code = $request->input('cart.promocode'))) {
            session()->put('cart.promocode', $code); // TODO array
            $promoCodesId[] = $code;
        } elseif (session()->get('cart.promocode')) {
            $promoCodesId[] = session()->get('cart.promocode');
        }

        // TODO: refactoring

        // доступные акции
        $sales = Sale::isPublish()
            ->select('id', 'name', 'start_at', 'end_at', 'type', 'discount', 'discount_type', 'dateless', 'data')
            ->where(function ($qSales) use ($promoCodesId, $productsIdInCart, $categoriesIdCartProducts, $cartSumTotalProducts) {
                $qSales
                    // бесплатная доставка
                    ->orWhere('type', Sale::TYPE_FREE_SHIPPING_CONDITIONS)

                    // бесплатная доставка по промокоду
                    ->orWhere(function ($qSales2) use ($promoCodesId) {
                        $qSales2->where('type', Sale::TYPE_PROM_CODE_FREE_ORDER)
                            ->whereHas('promoCodes', function ($qPromoCodes) use ($promoCodesId) {
                                $qPromoCodes->whereIn('code', $promoCodesId);
                            });
                    })

                     // скидка на товары
                    ->orWhere(function ($qSales2) use ($productsIdInCart, $categoriesIdCartProducts) {
                        $qSales2->where('type', Sale::TYPE_PRODUCT)
                            ->whereHas('products', function ($products) use ($productsIdInCart) {
                                $products->whereIn('model_id', $productsIdInCart);
                            })->orWhereHas('terms', function ($terms) use ($categoriesIdCartProducts) {
                                $terms->whereIn('model_id', $categoriesIdCartProducts);
                            });
                        })

                    // скидка на продукты по промокоду
                    ->orWhere(function ($qSales2) use ($productsIdInCart, $promoCodesId, $categoriesIdCartProducts) {
                        $qSales2->where('type', Sale::TYPE_PROM_CODE_PRODUCT)
                            ->whereHas('promoCodes', function ($qPromoCodes) use ($promoCodesId) {
                                $qPromoCodes->isActive()->whereIn('code', $promoCodesId);
                            })->where(function ($qSales3) use ($productsIdInCart, $categoriesIdCartProducts) {
                                $qSales3->whereHas('products', function ($products) use ($productsIdInCart, $categoriesIdCartProducts) {
                                    $products->whereIn('model_id', $productsIdInCart);
                                })->orWhereHas('terms', function ($terms) use ($categoriesIdCartProducts) {
                                    $terms->whereIn('model_id', $categoriesIdCartProducts);
                                });
                            });
                        })

                    // товар в подарок по промокоду к товарам или к сумме заказа
                    ->orWhere(function ($qSales2) use ($productsIdInCart, $promoCodesId, $categoriesIdCartProducts, $cartSumTotalProducts) {
                        $qSales2->where('type', Sale::TYPE_PROM_CODE_PRODUCT_PRESENT)
                            ->whereHas('promoCodes', function ($qPromoCodes) use ($promoCodesId) {
                                $qPromoCodes->isActive()->whereIn('code', $promoCodesId);
                            })->where(function ($qSale3) use ($productsIdInCart, $categoriesIdCartProducts, $cartSumTotalProducts) {
                                $qSale3->where(function ($qSales3) use ($productsIdInCart, $categoriesIdCartProducts, $cartSumTotalProducts) {
                                    $qSales3->whereHas('products', function ($products) use ($productsIdInCart, $categoriesIdCartProducts, $cartSumTotalProducts) {
                                        $products->whereIn('model_id', $productsIdInCart);
                                    })->orWhereHas('terms', function ($terms) use ($categoriesIdCartProducts) {
                                        $terms->whereIn('model_id', $categoriesIdCartProducts);
                                    });
                                })->orWhere('data->min_sum', '<=', $cartSumTotalProducts);
                            });
                    })

                    // скидка на сумму заказа по промокоду
                    ->orWhere(function ($qSales2) use ($promoCodesId) {
                        $qSales2->where('type', Sale::TYPE_PROM_CODE_DISCOUNT_SUM_ORDER)
                            ->whereHas('promoCodes', function ($qPromoCodes) use ($promoCodesId) {
                                $qPromoCodes->whereIn('code', $promoCodesId);
                            });
                    });
            })->get();

        // стоимость доставки
        $delivery['price'] = 0;

        $deliveryMethod = $request->input('data.delivery.method', 'cdek');
        $deliveryCityId = $request->input('data.delivery.city_id', 44);
        $deliveryTariff = $request->input('data.delivery.tariff', 136);
        $deliveryPwzCode = $request->input('data.delivery.pwz_code');
        $paymentMethod = $request->input('data.payment.method', 'yandex');

        //\Log::info('productsIdInCart:'.count(\Cart::getIds()));
        if (count($productsIdInCart)) {
            if ($deliveryMethod == 'cdek') {
                //\Log::info('CDEK');
                $cdek = new Cdek();
                $pwzItems = $cdek->getPwzFromCache($deliveryCityId)->getItems();
                if (count($pwzItems)) {
                    //\Log::info('CDEK PWZ');
                    $senderCityId = variable('cdek_sender_city_id');

                    // формируем параметры товаров для передечи в калькулятор сдек
                    $params = ['weight', 'length', 'width', 'height', 'volume'];
                    $productsCdekParams = [];
                    foreach ($cartProducts as $product) {
                        for ($i = 0; $i < $productsCounts[$product->id]; $i++) {
                            $prodParams = [];
                            foreach ($params as $param) {
                                if (! empty($product->data[$param])) {
                                    $prodParams[$param] = $product->data[$param];
                                }
                            }
                            $productsCdekParams[] = $prodParams;
                        }
                    }

//                    foreach (Order::$cdekTarifs as $tarif => $title) {
//                        $calculateCdek = $cdek->getCalculationDeliveryPriceFromCache([
//                            $senderCityId,
//                            $deliveryCityId,
//                            $tarif,
//                            $productsCdekParams,
//                        ]);
//
//                        $delivery['cdek_calculate'][$tarif]['response'] = $calculateCdek;
//                        if ($calculateCdek) {
//                            $delivery['cdek_calculate'][$tarif]['price'] = $calculateCdek->getPrice() * 100; // коп.;
//                            if ($deliveryTariff == $tarif) {
//                                $delivery['price'] = $delivery['cdek_calculate'][$tarif]['price'];
//                            }
//                        }
//                    }

                    $calculateCdek = $cdek->getCalculationDeliveryPriceFromCache([
                        $senderCityId,$deliveryCityId,$deliveryTariff,$productsCdekParams,
                    ]);

                    // результат калькулятора СДЕК для тарифов (для выбраного)
                    if ($calculateCdek) {
                        $delivery['price'] = $calculateCdek->getPrice() * 100; // коп.
                        $delivery['cdek_calculate'][$deliveryTariff]['response'] = $calculateCdek;
                    }

                    // существует ли эконом доставка
                    if ($calculateCdek = $cdek->getCalculationDeliveryPriceFromCache([
                        $senderCityId,$deliveryCityId,234,$productsCdekParams,
                    ], ['no_log' => true])) {
                        $delivery['cdek_calculate']['ekonom'] = true;
                    }

                    // пункт выдачи заказов
                    if ($deliveryPwzCode) {
                        $delivery['cdek_pwz'] = $cdek->getPwz($deliveryCityId, $deliveryPwzCode);
                    } else {
                        $delivery['cdek_pwz'] = array_first($pwzItems);
                    }
                }
                // добавляем 1% к сумме доставки
                $delivery['price'] = round($delivery['price'] + $cartSumTotalProducts / 100);
                // к сумме доставки прибавлять еще 4% от суммы заказа
                if ($paymentMethod == 'upon_receipt') {
                    $delivery['price'] = round($delivery['price'] + $cartSumTotalProducts * 4 / 100);
                }

            } elseif ($deliveryMethod == 'pickup') {
                $delivery['price'] = 0;
            } elseif ($deliveryMethod == 'courier') {
                $delivery['price'] = variable('delivery_courier_price', 0);
            } else {
                \Log::error('NOT SELECT METHOD');
            }
        }

        $saleInfoMsg = [];
        // Дейстует бесплатная доставка
        //if ($sale = $sales->where('type', Sale::TYPE_FREE_SHIPPING_CONDITIONS)->first()) {
        //
        //    $delivery['price'] = 0;
        //    $saleInfoMsg[] = $sale->data['msg_after_prepare'] ?? 'На сайте дейстует бесплатная доставка.';
        //}

        // Бесплатная доставка с условиями
        if ($sale = $sales->where('type', Sale::TYPE_FREE_SHIPPING_CONDITIONS)->first()) {

            // + учитывать сумму товаров в заказе
            $conditionMinSum = true;
            if (!empty($sale->data['min_sum'])) {
                $conditionMinSum = $sale->data['min_sum'] <= $cartSumTotalProducts;
            }

            // + учитывать доставку до пункта самовывоза (136)
            $conditionDeliveryPwz = true;
            if (!empty($sale->data['only_delivery_pwz'])) {
                $conditionDeliveryPwz = $deliveryTariff == 136;
            }

            // + учитывать тарифную зону - все кроме 6, 7
            $conditionTariffZoneWithout_6_7 = true;
            if (!empty($sale->data['tariff_zone_without_6_7'])) {
                $conditionTariffZoneWithout_6_7 = in_array($request->input('data.delivery.tariff_zone', 1), [1,2,3,4,5]);
            }

            if ($conditionMinSum && $conditionDeliveryPwz && $conditionTariffZoneWithout_6_7) {
                $delivery['price'] = 0;
                $saleInfoMsg[] = $sale->data['msg_after_prepare'] ?? 'Бесплатная доставка c условиями активирована!';
            } else {
                $sales = $sales->whereNotIn('id', $sales->where('type', Sale::TYPE_FREE_SHIPPING_CONDITIONS)->pluck('id')->toArray());
            }
        }

        // Акция на бесплатную доставку по промокоду
        if ($sale = $sales->where('type', Sale::TYPE_PROM_CODE_FREE_ORDER)->first()) {
            //$delivery['price'] = 0;
            $discountSum += $delivery['price'];
            $saleInfoMsg[] = $sale->data['msg_after_prepare'] ?? 'Бесплатная доставка активирована!';
        }

        // TODO: Скидка на цену товаров - старая/новая цена
        if ($sales->where('type', Sale::TYPE_PRODUCT)->count()) {
            //session()->put('cart.promocode_info', 'Скидка 10% активирована');
            //\Log::info("Акция на товары\n", $sales->where('type', Sale::TYPE_PRODUCT)->toArray());
        }

        // Скидка на цену товаров по промокодам (и в которых нет скидки, старой цены)
        if ($sale = $sales->where('type', Sale::TYPE_PROM_CODE_PRODUCT)->first()) {
            if ((!empty($sale->data['min_sum']) && $sale->data['min_sum'] <= $cartSumTotalProducts) || empty($sale->data['min_sum'])) {

                // Все товары которым будет скидка
                $productsByTxCategory = $sale->terms->map(function ($term) {
                        return $term->productsHasCategory->pluck('id');
                    })->flatten(1)->toArray();
                $productsBySale = $sale->products()->isPublish()->whereIn('id', $productsIdInCart)->get()->pluck('id')->toArray();
                $saleProdIds = array_filter(array_merge($productsByTxCategory, $productsBySale), function ($id) use ($productsIdInCart) {
                    return in_array($id, $productsIdInCart);
                });

                // в каких нет скидки
                $cartProductsHasNotPriceOld = $cartProducts->whereIn('id', $saleProdIds)
                ->filter(function ($product) {
                    if ($product->getCalculatePrice('discount') <= 0) {
                        return $product;
                    }
                });
                foreach ($cartProductsHasNotPriceOld as $product) {
                    $countProdInCart = $productsCounts[$product->id];
                    if ($sale->discount_type == Sale::DISCOUNT_TYPE_SUM) {
                        $discountSum += $sale->discount * $countProdInCart;
                    } elseif ($sale->discount_type == Sale::DISCOUNT_TYPE_PERCENT) {
                        $discountSum += ($product->getCalculatePrice('price') * $sale->discount / 100) * $countProdInCart;
                    }
                    $cart['product_discounts'][$product->id] = $discountSum;
                }
                if ($cartProductsHasNotPriceOld->count()) {
                    $saleInfoMsg[] = $sale->data['msg_after_prepare'] ?? 'Скидка на товары по промокоду активирована!';
                    session()->put('cart.promocode_info', implode('.', $saleInfoMsg));
                    $sales = $sales->where('id', '<>', $sale->id);
                }

            } else {
                $sales = $sales->where('id', '<>', $sale->id);
            }
        }

        // Скидка на сумму заказа по промокоду (с условиями)
        if ($sale = $sales->where('type', Sale::TYPE_PROM_CODE_DISCOUNT_SUM_ORDER)->first()) {

            // модели товаров которые есть в корзине, в которых нет скидки (старой цены)
            $cartProductsHasNotPriceOld = Product::isPublish()
                ->whereIn('id', $productsIdInCart)->get()->filter(function ($product) {
                    if ($product->getCalculatePrice('discount') <= 0) {
                        return $product;
                    }
                });
            $cartSumTotalProductsHasNotPriceOld = $cartProductsHasNotPriceOld->map(function ($product) use ($productsCounts) {
                return $product->getCalculatePrice('price') * $productsCounts[$product->id];
            })->sum();

            if ((!empty($sale->data['min_sum']) && $sale->data['min_sum'] <= $cartSumTotalProductsHasNotPriceOld) || empty($sale->data['min_sum'])) {
                if ($sale->discount_type == Sale::DISCOUNT_TYPE_SUM) {
                    $discountSum += $sale->discount;
                } elseif ($sale->discount_type == Sale::DISCOUNT_TYPE_PERCENT) {
                    $discountSum += $cartSumTotalProductsHasNotPriceOld * $sale->discount / 100;
                }
                $saleInfoMsg[] = $sale->data['msg_after_prepare'] ?? 'Промокод активирован!';
            } else {
                $sales = $sales->where('id', '<>', $sale->id);
            }
        }

        // Товар в подарок по промокоду
        session()->remove('cart.product_present_id');
        if ($sale = $sales->where('type', Sale::TYPE_PROM_CODE_PRODUCT_PRESENT)->first()) {
            if (isset($sale->data['product_present'])) {
                $saleInfoMsg[] = $sale->data['msg_after_prepare'] ?? 'Товар в подарок по промокоду активирован!';
                session()->put('cart.product_present_id', $sale->data['product_present']);
            } 
        }

        session()->put('cart.promocode_info', implode('.', $saleInfoMsg));

        $purchase['products'] = $cartSumTotalProducts;
        $purchase['delivery'] = $delivery['price'];
        $purchase['discount'] = $discountSum;
        $purchase['total'] = $cartSumTotalProducts + $delivery['price'] - $discountSum;

        $cart['products'] = $cartProducts;
        $cart['product_counts'] = $productsCounts;

        return [$cart, $purchase, $delivery, $sales];
    }

    /**
     * @param $productsInCart
     * @return mixed
     */
    protected function getRecommendationProduct($productsInCart)
    {
        return Product::withBase()->isPublish()
            ->whereIn('category_id', $productsInCart->pluck('category_id')->toArray())
            ->whereNotIn('id', $productsInCart->pluck('id')->toArray())
            ->inRandomOrder()->limit(10)->get();
    }

    /**
     * Для модалки.
     *
     * @param $city
     * @param \App\Services\Cdek $cdek
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function cdekPwz($city, Cdek $cdek)
    {
        $pwzItems = $cdek->getPwzFromCache($city);

        $coordinates = ['type' => 'FeatureCollection'];
        $features = [];
        foreach ($pwzItems as $item) {
            $features[] = [
                'type' => 'Feature',
                'id' => $item->Code,
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [$item->coordY, $item->coordX]
                ],
                'properties' => [
                    'balloonContentBody' => "<p>Адрес: <span>$item->Address</span></p><p><em>Телефон:</em>  <span>$item->Phone</span></p><p>Режим работы: <span>$item->WorkTime</span></p><p><button class='map-button' onclick='setAddress(\"$item->Code\", \"$item->Name\")'>Выбрать</button></p>",
                    //'clusterCaption' => "<strong>Еще одна</strong> метка",
                    //'hintContent' => $item->City,
                ],
            ];
        }

        $coordinates['features'] = $features;

        return response()->json([
            'html' => view('front.shopping-cart.inc.cdek-pwz-items-for-modal', compact('pwzItems'))->render(),
            'coordinates' => $coordinates,
        ]);
    }
}
