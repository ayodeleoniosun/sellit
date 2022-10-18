<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgotPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    protected user $user;

    protected string $link;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, string $link)
    {
        $this->user = $user;
        $this->link = $link;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('mail.from.address', 'Reset Password'))
            ->subject('Hey, You forgot your password')
            ->markdown('email.user.forgot-password')
            ->with([
                'first_name' => ucfirst($this->user->first_name),
                'url' => $this->link,
            ]);
    }
}
