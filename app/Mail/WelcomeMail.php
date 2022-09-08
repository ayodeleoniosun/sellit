<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    protected user $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $appName = config('app.name');

        return $this->from(config('mail.from.address', 'Welcome'))
            ->subject('Welcome to '.$appName)
            ->markdown('email.user.welcome')
            ->with([
                'first_name' => ucfirst($this->user->first_name),
                'app_name' => $appName,
            ]);
    }
}
