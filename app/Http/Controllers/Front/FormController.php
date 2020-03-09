<?php

namespace App\Http\Controllers\Front;

use App\Events\Form\Created;
use App\Http\Requests\Front\FormsRequest;
use App\Http\Traits\MediaLibraryManageTrait;
use App\Models\Form;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Event;

class FormController extends Controller
{
    use MediaLibraryManageTrait;

    /**
     * @param \App\Http\Requests\Front\FormsRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function store(FormsRequest $request)
    {
        if ($request->type == 'subscribers' &&  Form::byType('subscribers')->where('data->email', $request->get('email' ,''))->first()) {
            $destination = $request->session()->pull('destination', \URL::previous());
            if ($request->ajax()) {
                return response()->json([
                    'action' => 'reset',
                    'status' => 'warning',
                ]);
            }

            return redirect()->to($destination)
                ->with('success', trans('notifications.store.success'));
        }

        \Log::notice('WebForm', $request->all());

        $form = Form::create(array_merge($this->getVisitorInfo($request), [
            'type' => $request->get('type'),
            'data' => $request->validated(),
        ]));

        if ($request->has('terms')) {
            $form->terms()->sync(array_values_recursive($request->terms));
        }

        $this->manageMedia($form, $request);

        Event::fire(new Created(Form::find($form->id)));

        $destination = $request->session()->pull('destination', \URL::previous());
        if ($request->ajax()) {
            $answer = [
                'message' => 'Ваша заявка успешно принята и будет обработана!',
                'action' => 'reset',    //redirect
                'status' => 'success',  //warning
                'destination' => $destination,
                //'message' => trans('notifications.store.success'),
                //'html' => '<p>example</p>',
            ];
            if (in_array($request->type, ['contacts'])) {
                $answer = array_merge($answer, ['action' => 'redirect', 'destination' => $destination,]);
            }
            return response()->json($answer);
        }

        return redirect()->to($destination)
            ->with('success', trans('notifications.store.success'));
    }

    /**
     * @param $request
     * @return array
     */
    protected function getVisitorInfo($request)
    {
        return [
            'user_id' => optional($request->user())->id,
            'ip' => $request->ip(),
            'referer' => trim($request->headers->get('referer'), '/'),
            'url' => trim($request->fullUrl(), '/'),
        ];
    }
}
