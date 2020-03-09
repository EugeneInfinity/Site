<?php

namespace App\Listeners\Form;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEmailAfterCreated implements ShouldQueue
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

        if (($subject = config("web-forms.$form->type.email.subject")) && variable('mail_to_address')) {
            $mails = array_map(function ($mail) {
                return trim($mail);
            }, explode(',', variable('mail_to_address')));

            \Mail::to($mails)
                ->send(new \App\Mail\CustomMail($subject, 'emails.admin.web-form-created',  [
                    'form' => $form,
                ]));
        }
    }
}
