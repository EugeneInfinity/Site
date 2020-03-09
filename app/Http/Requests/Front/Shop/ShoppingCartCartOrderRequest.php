<?php

namespace App\Http\Requests\Front\Shop;

use App\Http\Requests\BaseFormRequest;
use App\Models\Shop\Order;
use App\Rules\SalePromoCode;
use Illuminate\Foundation\Http\FormRequest;

class ShoppingCartCartOrderRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $paymentMethodKeys = array_column(json_decode(variable('payment_methods', '[]'), true), 'key');

        $rules = [
            'data.delivery.method' => 'required|in:cdek,pickup,courier',
            'data.payment.method' => 'required|in:'.implode(',', $paymentMethodKeys),
            'data.delivery.phone' => ['required', 'regex:/^'.config('services.global.phone.pattern').'$/i'],
            'data.delivery.email' => 'required|email',//|unique:users,email,'.\Auth::id(),
            'data.delivery.name' => 'required|string|max:191',
            'data.delivery.city' => 'required|string',
        ];

        if ($this->input('cart.promocode')) {
            $rules['cart.promocode'] = ['required', 'string', new SalePromoCode(),];
        }

        if ($this->input('data.delivery.method') == 'cdek') {
            $cdekTarifsKeys = array_keys(Order::$cdekTarifs);
            $rules = array_merge($rules, [
                //'data.delivery.city' => 'required|string',
                'data.delivery.tariff' => 'required|in:'.implode(',', $cdekTarifsKeys),
                'data.delivery.address' => 'required|string',
                'data.delivery.pwz_code' => 'required|string',
            ]);
        } elseif($this->input('data.delivery.method') == 'pickup') {
            //...
        } elseif ($this->input('data.delivery.method') == 'courier') {
            $cntIds = \Auth::check() ? \Auth::user()->contacts->pluck('id')->toArray() : [];

            $rules = array_merge($rules, [
                'data.delivery.city' => 'required_if:contact_id,0|nullable|string',
                'data.delivery.region' => 'required_if:contact_id,0|nullable|string',
                'data.delivery.zip_code' => 'required_if:contact_id,0|nullable|string',
                'data.delivery.address' => 'required_if:contact_id,0|nullable|string',
                'contact_id' => 'required|in:0,'.implode(',', $cntIds)
            ]);
        }

        return $rules;
    }

    /**
     * @return array|void
     */
    public function filters()
    {
        return [
            'data.delivery.phone' => 'digit',
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'data.delivery.city.required_if' => 'Поле обязательно для заполнения.',
            'data.delivery.region.required_if' => 'Поле обязательно для заполнения.',
            'data.delivery.zip_code.required_if' => 'Поле обязательно для заполнения.',
            'data.delivery.address.required_if' => 'Поле обязательно для заполнения.',
        ];
    }
}
