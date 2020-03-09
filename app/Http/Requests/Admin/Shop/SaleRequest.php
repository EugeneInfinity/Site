<?php

namespace App\Http\Requests\Admin\Shop;

use App\Http\Requests\BaseFormRequest;
use App\Models\Shop\Sale;

class SaleRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'publish' => 'required|boolean',
            'show' => 'required|boolean',
            'name' => 'required|string|max:255',
            'url' => 'nullable|string',
            'description' => 'nullable|string',
            'type' => 'required|in:'.implode(',', array_keys(Sale::$types)),
            'start_at' => 'nullable|date',
            'end_at' => 'nullable|date',
            'dateless' => 'required|boolean',
            'data' => 'nullable|array'
        ];
    }

    public function filters()
    {
        return [
            'start_at' => 'trim|format_date:m/d/Y, Y-m-d',
            'end_at' => 'trim|format_date:m/d/Y, Y-m-d',
        ];
    }
}
