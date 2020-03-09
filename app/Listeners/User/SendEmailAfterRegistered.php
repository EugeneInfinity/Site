<?php

namespace App\Listeners\User;

use Illuminate\Auth\Events\Verified;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\URL;

class SendEmailAfterRegistered
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        if (($user = $event->user) && $user instanceof \App\Models\User) {

            // TODO: Verified
            if ($user->markEmailAsVerified()) {
                event(new Verified($user));
            }

            \Mail::to($user)
                ->send(new \App\Mail\CustomMail("Вы успешно зарегистрировались на ".config('app.name'), 'emails.front.after-register',  [
                    'user' => $user,
                    //'verificationUrl' => $this->verificationUrl($user), // TODO: Verified
                ]));

            $this->sendToSendPulse($user);
        }
    }

    protected function verificationUrl($user)
    {
        return URL::temporarySignedRoute(
            'verification.verify', \Carbon\Carbon::now()->addMinutes(60), ['id' => $user->getKey()]
        );
    }

    protected function sendToSendPulse($user)
    {
        if (!empty($user->data['subscriber']) && ($email = $user->email)) {

            $code = '';
            if (variable('sale_id_for_subscribers') && ($sale = \App\Models\Shop\Sale::find(variable('sale_id_for_subscribers')))) {
                $codeGenerator = new \App\Helpers\Sales\PromoCodeGenerator();
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

            $mainParams = [
                [
                    'email' => $email,
                    'variables' => [
                        'phone' => $user->phone ?? '',
                        'name' => $user->user ?? '',
                        'promocode' => $code,
                    ],
                ],
            ];

            try {
                if (variable('sendpulse_address_book_id_order')) {
                    app('SendPulse')->addEmails(variable('sendpulse_address_book_id_order'), $mainParams /*$additionalParams*/);
                }
            } catch (\Exception $exception) {
                \Log::error($exception->getMessage());
            }
        }
    }
}
