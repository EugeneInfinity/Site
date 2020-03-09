<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Fomvasss\Variable\Models\Variable;
use Illuminate\Http\Request;

class VariableController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function forms(Request $request)
    {
        $this->authorize("variable.read");

        return view()->first(["admin.variables.$request->form", "admin.variables.forms"]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function save(Request $request)
    {
        $this->authorize("variable.update");

        $this->validate($request, [
            'vars' => 'array',
            'vars.*' => 'nullable|string',
            'vars_json' => 'array',
        ]);

        //dd($request->all());

        foreach ($request->get('vars', []) as $key => $value) {
            if ($request->group == 'prices' || in_array($key, ['delivery_courier_price'])) {
                $this->updateOrCreate($key, $value * 100);
            } elseif (in_array($key, ['bitrix24_host'])) {
                $this->updateOrCreate($key, trim($value, '/'));
            }else {
                $this->updateOrCreate($key, $value);
            }
        }

        foreach ($request->get('vars_json', []) as $key => $value) {
            $this->updateOrCreate($key, json_encode($value));
        }

        \Cache::forget('laravel.variables.cache');
        \Artisan::call('config:clear');

        $destination = $request->session()->pull('destination', route('admin.variable.forms'));
        return redirect()->to($destination)
            ->with('success', trans('notifications.update.success'));
    }

    protected function updateOrCreate($key, $value)
    {
        Variable::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}
