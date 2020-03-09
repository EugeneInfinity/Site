<?php

namespace App\Http\Requests\Front;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class FormsRequest extends BaseFormRequest
{
    public $formTypes = [
        'faq',
        'contacts',
        'cooperation',
        'questions',
        'subscribers',
        'buy_one_click',
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $rules = [];
        switch ($this->request->get('type')) {
            case 'subscribers':
                $rules = [
                    'email' => 'required|email|max:50',
                    'subscribe' => 'nullable|in:0,1',
                    'accept' => 'required|accepted',
                ];
                break;
            case 'contacts':
                $rules = [
                    'name' => 'required|string|max:191',
                    'phone' => 'string|max:20',
                    'email' => 'email|max:50',
                    'message' => 'required|string|max:2048',
                    'accept' => 'required|accepted',
                ];
                $rules = $this->applyReCaptchaRules($rules);
                break;
            case 'faq':
                $rules = [
                    'name' => 'required|string|max:191',
                    'phone' => 'string|max:20',
                    'email' => 'email|max:50',
                    'city' => 'string|max:255',
                    'message' => 'required|string|max:2048',
                    'terms.*' => 'array|max:1',
                    'terms.*.*' => 'numeric|exists:terms,id',
                    'accept' => 'required|accepted',
                ];
                break;
            case 'cooperation':
                $rules = [
                    'name' => 'required|string|max:191',
                    'phone' => 'string|max:20',
                    'email' => 'email|max:50',
                    'city' => 'string|max:255',
                    'message' => 'nullable|string|max:2048',
                    'terms.*' => 'array|max:1',
                    'terms.*.*' => 'numeric|exists:terms,id',
                    'accept' => 'required|accepted',
                    'subscribe' => 'required|in:0,1',
                ];
                break;
            case 'questions':
                $rules = [
                    'name' => 'required|string|max:191',
                    'email' => 'email|max:50',
                    'message' => 'required|string|max:2048',
                ];
                break;
            case 'buy_one_click':
                $rules = [
                    'name' => 'required|string|max:191',
                    'phone' => 'required|string|max:50',
                    'product_id' => 'sometimes|exists:products,id',
                ];
                break;
        }

        return array_merge([
            'type' => 'required|in:'.implode(',', $this->formTypes)
        ], $rules);
    }

    public function messages()
    {
        return [
            'accept.accepted' => 'Для продолжения, Вы должны согласится с условиями',
        ];
    }

    public function filters()
    {
        return [
          'phone' => 'digit',
        ];
    }
}
