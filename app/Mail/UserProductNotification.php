<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserProductNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $user_name;
    public $product_name; // Change to $product_id if that is the correct variable
    public $email;

    public function __construct($user_name, $product_name, $email)
    {
        $this->user_name = $user_name;
        $this->product_name = $product_name; // Change to $product_id if that is the correct variable
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.user_notify_confirmation')
            ->subject('Your Product Notification') // Uncomment and customize if needed
            ->with([
                'user_name' => $this->user_name,
                'product_name' => $this->product_name,
                'email' => $this->email,
            ]);
    }
}
