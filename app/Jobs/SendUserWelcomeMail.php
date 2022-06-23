<?php

namespace App\Jobs;

use App\Mail\UserWelcomeMail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendUserWelcomeMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $user;
    protected $url;
    protected $data;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->url = 'https://larachat.ayodeleoniosun.com';
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info("Sending user welcome mail to ".$this->user->email_address);

        $this->data = [
            'name' => ucfirst($this->user->first_name)." ".ucfirst($this->user->last_name),
            'url' => $this->url
        ];

        $to = ['email' => $this->user->email_address, 'name' => $this->data['name']];
        Mail::to((object) $to)->send(new UserWelcomeMail($this->data));
    }
}
