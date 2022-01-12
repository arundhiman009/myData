<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendReferralMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    private $user;
    private $email;
    private $link;
    private $adminEmail;
    public function __construct($user,$email, $link, $adminEmail)
    {
        $this->user = $user;
        $this->email = $email;
        $this->link = $link;
        $this->adminEmail = $adminEmail;
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
        $link = $this->link;
        $adminEmail = $this->adminEmail;
        $sub  = '【Notification】'.$user->username.' has invited you to join '.config('app.name').' !';
        $email = $this->to($email)
        ->subject($sub)
        ->view('emails.users.ReferralMail',compact('link','user','adminEmail'));
        return $email;
    }
}
