<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserRequestConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $user_name;
    public $product_name; // Change to $product_id if that is the correct variable
    public $qty;
    public $delivery_address;
    public $callback_date;
    public $additional_info;

    public function __construct($user_name, $product_name, $qty, $delivery_address, $callback_date, $additional_info)
    {
        $this->user_name = $user_name;
        $this->product_name = $product_name; // Change to $product_id if that is the correct variable
        $this->qty = $qty;
        $this->delivery_address = $delivery_address;
        $this->callback_date = $callback_date;
        $this->additional_info = $additional_info;
    }

    public function build()
    {
        return $this->markdown('emails.user_request_confirmation')
            ->subject('Your Product Request Confirmation') // Uncomment and customize if needed
            ->with([
                'user_name' => $this->user_name,
                'product_name' => $this->product_name, // Change to product_id if that is the correct variable
                'qty' => $this->qty,
                'delivery_address' => $this->delivery_address,
                'callback_date' => $this->callback_date,
                'additional_info' => $this->additional_info,
            ])
            ->attach(asset('storage/images/website/logo_1712057963.png'), [
                'as' => 'logo.png',
                'mime' => 'image/png',
            ]);
    }
}
