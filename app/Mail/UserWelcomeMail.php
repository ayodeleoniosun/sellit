<?php

namespace App\Mail;

use App\ApiUtility;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $data;

    public function __construct($data)
    {
        $this->data = (object) $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->withSwiftMessage(function ($message) {
            $message->getHeaders()
                ->addTextHeader('X-SES-CONFIGURATION-SET', 'suetco');
        });

        return $this->from('hello@larachat.test', 'LaraChat')
            ->subject(ApiUtility::mail_subject_by_environment().'Welcome to Larachat')
            ->bcc('mails@ses.mailintel.io', 'Mailintel SES')
            ->view('email.user.welcome', ['data' => $this->data]);
    }
}
