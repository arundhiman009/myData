<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendRegisterUser extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    private $adminData;
    private $email;
    private $user;
    private $password;

    public function __construct($adminData,$email,$user,$password)
    {
        //
        $this->adminData = $adminData;
        $this->email = $email;
        $this->user = $user;
        $this->password = $password;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $adminData = $this->adminData;
        $user = $this->user;
        $email = $this->email;
        $password = $this->password;
        
        $sub  = 'Account Created !';
        $email = $this->to($email)
        ->subject($sub)
        ->view('emails.users.RegisterUserMail',['adminData'=> $adminData, 'user' => $user , 'password' => $password]);
        return $email;
    }
}
