<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminNewRequestNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $user_name;
    public $user_email;
    public $user_number;
    public $product_name;
    public $qty;
    public $delivery_address;
    public $callback_date;
    public $additional_info;

    public function __construct($user_name, $user_email, $user_number,$product_name, $qty, $delivery_address, $callback_date, $additional_info)
    {
        $this->user_name = $user_name;
        $this->user_email = $user_email;
        $this->user_number = $user_number;
        $this->product_name = $product_name;
        $this->qty = $qty;
        $this->delivery_address = $delivery_address;
        $this->callback_date = $callback_date;
        $this->additional_info = $additional_info;
    }

    public function build()
    {
        return $this->view('emails.admin_new_request_notification')
                    ->subject('New Product Request Received')
                    ->with([
                        'user_name' => $this->user_name,
                        'user_email' => $this->user_email,
                        'user_number' => $this->user_number,
                        'product_name' => $this->product_name,
                        'qty' => $this->qty,
                        'delivery_address' => $this->delivery_address,
                        'callback_date' => $this->callback_date,
                        'additional_info' => $this->additional_info,
                    ]);
    }
}
