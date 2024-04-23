<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomerRequestSubmit extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $content;

    public function __construct($content)
    {
        $this->content = $content;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // return $this->markdown('emails.AdminForgotPwMail')->with('body',$this->body);
        return $this->markdown('emails.CustomerRequestSubmit')->with(['content' => $this->content]);
    }
}
