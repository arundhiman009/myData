<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminApproval extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    private $user;
    private $email;

    public function __construct($user,$email)
    {
        //
        $this->user = $user;
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $user = $this->user;
        $email = $this->email;
      
        $sub  = '【Notification】'.$user->username.' has Signup your account to join '.config('app.name').' !';
        $email = $this->to($email)
        ->subject($sub)
        ->view('emails.admin.admin-approval',compact('user'));
        return $email;
    }
}
